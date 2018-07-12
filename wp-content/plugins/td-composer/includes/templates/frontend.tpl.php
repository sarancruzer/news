<?php
/**
 * Created by PhpStorm.
 * User: tagdiv
 * Date: 11.02.2016
 * Time: 13:04
 */

/*
 * frontend.tpl.php can't be used without 'tdc' class
 */


global $post;

$post = tdc_state::get_post();

// check if we have a post set in the state.
if (empty($post)) {
	tdc_util::error(__FILE__, __FUNCTION__, 'Invalid post ID, or permission denied');
	die;
}



add_thickbox();
wp_enqueue_media( array( 'post' => $post->ID ) );

require_once( ABSPATH . 'wp-admin/admin-header.php' );

$postContent = str_replace( '\'', '\\\'', $post->post_content );

/*
 * Important!
 * 'vc_column_text' and 'td_block_with_title' are not self enclosed shortcodes, having their contents inside of them.
 * So, because this content has \r,\n or \r\n characters inside, it can't be used as it is for window.tdcPostSettings.postContent. It needs to be formatted as wordpress does,
 * changing these characters to paragraphs.
 */
function td_replace_vc_column_text($matches) {
	return '[vc_column_text' . $matches[1] . ']' . wpautop( htmlentities( preg_replace( '/<\/?p\>/', "\n", $matches[2] ) . "\n", ENT_QUOTES, "UTF-8" ) ) . '[/vc_column_text]';
	//return '[vc_column_text' . $matches[1] . ']' . wpautop( preg_replace( '/<\/?p\>/', "\n", $matches[2] ) . "\n" ) . '[/vc_column_text]';
}

if ( shortcode_exists( 'vc_column_text' ) && has_shortcode( $postContent, 'vc_column_text' ) ) {
	$postContent = preg_replace_callback("/\[vc_column_text(.*)\](.*)\[\/vc_column_text\]/sU", 'td_replace_vc_column_text', $postContent);
}

function td_replace_td_block_text_with_title($matches) {
	return '[td_block_text_with_title' . $matches[1] . ']' . wpautop( htmlentities( preg_replace( '/<\/?p\>/', "\n", $matches[2] ) . "\n", ENT_QUOTES, "UTF-8" ) ) . '[/td_block_text_with_title]';
	//return '[td_block_text_with_title' . $matches[1] . ']' . wpautop( preg_replace( '/<\/?p\>/', "\n", $matches[2] ) . "\n" ) . '[/td_block_text_with_title]';
}

if ( shortcode_exists( 'td_block_text_with_title' ) && has_shortcode( $postContent, 'td_block_text_with_title' ) ) {
	$postContent = preg_replace_callback("/\[td_block_text_with_title(.*)\](.*)\[\/td_block_text_with_title\]/sU", 'td_replace_td_block_text_with_title', $postContent);
}



$postContent = str_replace( array( "\r\n", "\n", "\r" ), array( "\r\n'+'" ), $postContent );


//@todo - refactorizare aici json_encode
//<link rel="stylesheet" href="http://basehold.it/22">

// Add shortcodes name to be displayed into sidebar panel
$shortcodes = array();
foreach (tdc_mapper::get_mapped_shortcodes() as $mapped_shortcode ) {
	$shortcodes[ $mapped_shortcode[ 'base' ] ] = $mapped_shortcode[ 'name' ];
}

//var_dump(wp_get_sidebars_widgets());





function get_data_shortcode_settings( $mapped_shortcode) {
	$data_shortcode_settings = '';

	if ( isset( $mapped_shortcode['tdc_start_values'] ) ) {
		$data_shortcode_settings .= 'data-start-values="' . $mapped_shortcode['tdc_start_values'] . '" ';
	}
	if ( isset( $mapped_shortcode['tdc_row_start_values'] ) ) {
		$data_shortcode_settings .= ' data-row-start-values="' . $mapped_shortcode['tdc_row_start_values'] . '" ';
	}
	return $data_shortcode_settings;
}

/**
 * Get the url in format //domain.com/.. without 'http' and 'https', because url of iframe does not redirect from http to https
 *
 * @param $post_id
 *
 * @return mixed
 */
function get_post_url( $post_id ) {
	 return str_replace( array( 'http:', 'https:' ), '', get_permalink( $post_id ) );
}





?>


	<script type="text/javascript">

		// Add 'td_composer' class to html element
		window.document.documentElement.className += ' td_composer';

		// "Starting in Chrome 51, a custom string will no longer be shown to the user. Chrome will still show a dialog to prevent users from losing data, but it’s contents will be set by the browser instead of the web page."
		// https://developers.google.com/web/updates/2016/04/chrome-51-deprecations?hl=en#remove-custom-messages-in-onbeforeload-dialogs
		window.onbeforeunload = function ( event) {
			if ( ! tdcMain.getContentModified() ) {
				return;
			}
			return 'Dialog text here';
		}

		window.tdcPostSettings = {
			postId: '<?php echo $post->ID; ?>',
			postUrl: '<?php echo get_post_url($post->ID); ?>',
			postContent: '<?php echo $postContent; ?>',
			postMetaDirtyContent: '<?php echo get_post_meta( $post->ID, 'tdc_dirty_content', true ) ?>',
			postMetaVcJsStatus: '<?php echo get_post_meta( $post->ID, '_wpb_vc_js_status', true ) ?>',
			shortcodes: <?php echo json_encode( $shortcodes ) ?>,
            tdbLoadDataFromId: <?php echo json_encode(tdc_util::get_get_val('tdbLoadDataFromId')) ?>,
            tdbTemplateType: <?php echo json_encode(tdc_util::get_get_val('tdbTemplateType')) ?>
       };

		// Set the local storage to show inline the iframe wrapper and the sidebar
		window.localStorage.setItem( 'tdc_live_iframe_wrapper_inline', 1 );




	</script>

	<?php
			// tdc-icon-sidebar-open is outside of the sidebar, because the sidebar has overflow hidden
	?>

	<!-- the composer sidebar -->

	<div class="tdc-sidebar-open" title="Show sidebar">
		<span class="tdc-icon-sidebar-open"></span>
	</div>

	<div id="tdc-sidebar" class="tdc-sidebar-inline">
		<div class="tdc-top-buttons">
			<div class="tdc-add-element" title="Add new element in the viewport">
				Add element
				<span class="tdc-icon-add"></span>
			</div>
			<?php
            // load the preview for the current content if we're editing a template with content
            $tdbLoadDataFromId = tdc_util::get_get_val('tdbLoadDataFromId');
            if ($tdbLoadDataFromId !== false) {
                $preview_url = get_permalink($tdbLoadDataFromId);
            } else {
                $preview_url = get_permalink($post->ID);
            }
            ?>
			<a class="tdc-view-page" href="<?php echo $preview_url ?>" target="_blank" title="View the page. Save the content before it">
				<span class="tdc-icon-view"></span>
			</a>
			<a class="tdc-save-page" href="#" title="Save the page content">
				<span class="tdc-icon-save"></span>
				</a>
			<a class="tdc-close-page" href="#" title="Close the composer and switch to backend">
				<span class="tdc-icon-close"></span>
			</a>
		</div>

		<div class="tdc-empty-sidebar" style="text-align: left">
			<div class="tdc-start-tips">
				<img src="<?php echo TDC_URL ?>/assets/images/sidebar/tagdiv-composer.png">
				<span>Welcome to <br>tagDiv Composer!</span>
				<p>Get started by adding elements, go to <span>Add Element</span> and begin dragging your items. You can edit by clicking on any element in the preview area.</p>
			</div>
			<div class="tdc-add-element" title="Add new element in the viewport">Add element</div>
		</div>


		<!-- the inspector -->
		<div class="tdc-inspector-wrap">
			<div class="tdc-inspector">
				<!-- breadcrumbs browser -->
				<div class="tdc-breadcrumbs">
					<div id="tdc-breadcrumb-row">
						<a href="#" title="The parent row.">row</a>
					</div>
					<div id="tdc-breadcrumb-column">
						<span class="tdc-breadcrumb-arrow"></span>
						<a href="#" title="The parent column.">column</a>
					</div>
					<div id="tdc-breadcrumb-inner-row">
						<span class="tdc-breadcrumb-arrow"></span>
						<a href="#" title="The parent inner row.">inner-row</a>
					</div>
					<div id="tdc-breadcrumb-inner-column">
						<span class="tdc-breadcrumb-arrow"></span>
						<a href="#" title="The parent inner column.">inner-column</a>
					</div>
				</div>
				<div class="tdc-current-element-head" title="This is the type (shortcode) of the current selected element.">
				</div>
				<div class="tdc-current-element-siblings">
				</div>
				<div class="tdc-tabs-wrapper">
				</div>
			</div>
		</div>


		<div class="tdc-sidebar-bottom">
			<div class="tdc-sidebar-bottom-button tdc-sidebar-close" title="Hide sidebar">
				<span class="tdc-icon-sidebar-close"></span>
			</div>
			<div class="tdc-sidebar-bottom-button tdc-bullet" title="On/Off full viewport">
				<span class="tdc-icon-bullet"></span>
			</div>
			<div class="tdc-sidebar-info"></div>
			<div class="tdc-extends">

				<?php
					// Extensions add button in sidebar (to open content)
					do_action( 'tdc_extension_sidebar_bottom' );
				?>

			</div>
			<div class="tdc-sidebar-bottom-button tdc-main-menu" title="Show site wide settings">
				<span class="tdc-icon-view"></span>
			</div>
		</div>

		<div id="tdc-restore">
			Restore
		</div>

		<div id="tdc-restore-content">
		</div>

		<!-- modal window -->
		<div class="tdc-sidebar-modal tdc-sidebar-modal-elements" data-button_class="tdc-add-element">
			<div class="tdc-sidebar-modal-search" title="Search for elements in list">
				<input type="text" placeholder="Search" name="Search">
				<span class="tdc-modal-magnifier"></span>
			</div>
			<div class="tdc-sidebar-modal-content">
				<!-- sidebar elements list -->
				<div class="tdc-sidebar-elements">
					<?php

					$top_mapped_shortcodes = array();

					$block_mapped_shortcodes = array();
					$big_grids_mapped_shortcodes = array();
					$extended_mapped_shortcodes = array();
					$external_mapped_shortcodes = array();
					$multipurpose_mapped_shortcodes = array();
					$template_shortcodes = array(
//						'template_1' => array(
//							'name' => 'Template 1',
//							'content' => base64_encode(json_encode('[vc_row full_width="stretch_row"][vc_column width="1/4"][vc_row_inner][vc_column_inner width="1/2"][td_block_2][/vc_column_inner][vc_column_inner width="1/2"][td_block_1][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="1/4"][/vc_column][vc_column width="1/4"][/vc_column][vc_column width="1/4"][/vc_column][/vc_row]')),
//						),
					);

					$template_shortcodes = apply_filters( 'tdc_template_shortcodes', $template_shortcodes );

					$mapped_shortcodes = tdc_mapper::get_mapped_shortcodes();

					foreach ($mapped_shortcodes as &$mapped_shortcode ) {

						$shortcode_base = $mapped_shortcode['base'];

						if ( 'vc_column' === $shortcode_base || 'vc_column_inner' === $shortcode_base ) {
							continue;
						}

						if ( 'vc_row' === $shortcode_base ||
						     'vc_row_inner' === $shortcode_base ||
						     'vc_empty_space' === $shortcode_base ) {
							$top_mapped_shortcodes[$shortcode_base] = $mapped_shortcode;

							continue;
						}

						if ( isset( $mapped_shortcode['tdc_category'] ) ) {
							switch( $mapped_shortcode['tdc_category'] ) {
								case 'Blocks':
									$block_mapped_shortcodes[] = $mapped_shortcode;
									break;
								case 'Big Grids':
									$big_grids_mapped_shortcodes[] = $mapped_shortcode;
									break;
								case 'Extended':
									$extended_mapped_shortcodes[] = $mapped_shortcode;
									break;
								case 'Multipurpose':
									$multipurpose_mapped_shortcodes[] = $mapped_shortcode;
									break;
								case 'External':
									$external_mapped_shortcodes[] = $mapped_shortcode;
									break;
							}
						}
					}

					function tdc_sort_name( $mapped_shortcode_1, $mapped_shortcode_2 ) {
						return strcmp( $mapped_shortcode_1['name'], $mapped_shortcode_2['name'] );
					}

					usort( $extended_mapped_shortcodes, 'tdc_sort_name');
					usort( $external_mapped_shortcodes, 'tdc_sort_name');
					usort( $multipurpose_mapped_shortcodes, 'tdc_sort_name');


					// Row
					$data_shortcode_settings = get_data_shortcode_settings(  $top_mapped_shortcodes['vc_row'] );

					echo '<div class="tdc-sidebar-element tdc-row-temp" data-shortcode-name="' . $top_mapped_shortcodes['vc_row']['base'] . '" ' . $data_shortcode_settings . '>' .
							'<div class="tdc-element-ico tdc-ico-' . $top_mapped_shortcodes['vc_row']['base'] . '"></div>' .
							'<div class="tdc-element-id">' . $top_mapped_shortcodes['vc_row']['name'] . '</div>' .
				        '</div>';

					// Inner Row
					$data_shortcode_settings = get_data_shortcode_settings(  $top_mapped_shortcodes['vc_row_inner'] );

					echo
						'<div class="tdc-sidebar-element tdc-element-inner-row-temp" data-shortcode-name="' . $top_mapped_shortcodes['vc_row_inner']['base'] . '" ' . $data_shortcode_settings . '>' .
							'<div class="tdc-element-ico tdc-ico-' . $top_mapped_shortcodes['vc_row_inner']['base'] . '"></div>' .
							'<div class="tdc-element-id">' . $top_mapped_shortcodes['vc_row_inner']['name'] . '</div>' .
					    '</div>';

					// Empty space
					$data_shortcode_settings = get_data_shortcode_settings(  $top_mapped_shortcodes['vc_empty_space'] );

					echo
						'<div class="tdc-sidebar-element tdc-element" data-shortcode-name="' . $top_mapped_shortcodes['vc_empty_space']['base'] . '" ' . $data_shortcode_settings . '>' .
							'<div class="tdc-element-ico tdc-ico-' . $top_mapped_shortcodes['vc_empty_space']['base'] . '"></div>' .
							'<div class="tdc-element-id">' . $top_mapped_shortcodes['vc_empty_space']['name'] . '</div>' .
						'</div>';


					if ( ! empty( $block_mapped_shortcodes ) ) {

						// Separator
						echo '<div class="tdc-sidebar-separator">Block shortcodes</div>';

						foreach ( $block_mapped_shortcodes as $mapped_shortcode ) {
							if ( isset( $mapped_shortcode['map_in_td_composer'] ) && true === $mapped_shortcode['map_in_td_composer'] ) {

								$data_row_start_values = '';

								if ( isset( $mapped_shortcode['tdc_in_row'] ) && true === $mapped_shortcode['tdc_in_row'] ) {
									$tdc_class = 'tdc-element-with-row tdc-row-temp';
								} else {
									$tdc_class = 'tdc-element';
								}

								$data_shortcode_settings = get_data_shortcode_settings( $mapped_shortcode );

								$buffer =
									'<div class="tdc-sidebar-element ' . $tdc_class . '" data-shortcode-name="' . $mapped_shortcode['base'] . '" ' . $data_shortcode_settings . '>' .
									'<div class="tdc-element-ico tdc-ico-' . $mapped_shortcode['base'] . '"></div>' .
									'<div class="tdc-element-id">' . $mapped_shortcode['name'] . '</div>' .
									'</div>';

								echo $buffer;
							}
						}
					}


					if ( ! empty( $big_grids_mapped_shortcodes ) ) {

						// Separator
						echo '<div class="tdc-sidebar-separator">Big Grid Shortcodes</div>';

						foreach ( $big_grids_mapped_shortcodes as $mapped_shortcode ) {
							if ( isset( $mapped_shortcode['map_in_td_composer'] ) && true === $mapped_shortcode['map_in_td_composer'] ) {

								$data_row_start_values = '';

								if ( isset( $mapped_shortcode['tdc_in_row'] ) && true === $mapped_shortcode['tdc_in_row'] ) {
									$tdc_class = 'tdc-element-with-row tdc-row-temp';
									if ( isset( $mapped_shortcode['tdc_row_start_values'] ) ) {
										$data_row_start_values = ' data-row-start-values="' . $mapped_shortcode['tdc_row_start_values'] . '" ';
									}
								} else {
									$tdc_class = 'tdc-element';
								}

								$data_shortcode_settings = get_data_shortcode_settings( $mapped_shortcode );

								$buffer =
									'<div class="tdc-sidebar-element ' . $tdc_class . '" data-shortcode-name="' . $mapped_shortcode['base'] . '" ' . $data_shortcode_settings . '>' .
									'<div class="tdc-element-ico tdc-ico-' . $mapped_shortcode['base'] . '"></div>' .
									'<div class="tdc-element-id">' . $mapped_shortcode['name'] . '</div>' .
									'</div>';

								echo $buffer;
							}
						}
					}


					if ( ! empty( $extended_mapped_shortcodes ) ) {

						// Separator
						echo '<div class="tdc-sidebar-separator">Extended shortcodes</div>';

						// Here will be displayed the extended shortcodes
						foreach ( $extended_mapped_shortcodes as $mapped_shortcode ) {

							$data_row_start_values = '';

							if ( isset( $mapped_shortcode['tdc_in_row'] ) && true === $mapped_shortcode['tdc_in_row'] ) {
								$tdc_class = 'tdc-element-with-row tdc-row-temp';
								if ( isset( $mapped_shortcode['tdc_row_start_values'] ) ) {
									$data_row_start_values = ' data-row-start-values="' . $mapped_shortcode['tdc_row_start_values'] . '" ';
								}
							} else {
								$tdc_class = 'tdc-element';
							}

							$data_shortcode_settings = get_data_shortcode_settings( $mapped_shortcode );

							$buffer =
								'<div class="tdc-sidebar-element ' . $tdc_class . '" data-shortcode-name="' . $mapped_shortcode['base'] . '" ' . $data_shortcode_settings . '>' .
								'<div class="tdc-element-ico tdc-ico-' . $mapped_shortcode['base'] . '"></div>' .
								'<div class="tdc-element-id">' . $mapped_shortcode['name'] . '</div>' .
								'</div>';

							echo $buffer;
						}
					}


					if ( ! empty( $external_mapped_shortcodes ) ) {

						// Separator
						echo '<div class="tdc-sidebar-separator">External shortcodes</div>';

						// Here will be displayed the external shortcodes
						foreach ( $external_mapped_shortcodes as $mapped_shortcode ) {

							$data_row_start_values = '';

							if ( isset( $mapped_shortcode['tdc_in_row'] ) && true === $mapped_shortcode['tdc_in_row'] ) {
								$tdc_class = 'tdc-element-with-row tdc-row-temp';
							} else {
								$tdc_class = 'tdc-element';
							}

							$data_shortcode_settings = get_data_shortcode_settings( $mapped_shortcode );

							$buffer =
								'<div class="tdc-sidebar-element ' . $tdc_class . '" data-shortcode-name="' . $mapped_shortcode['base'] . '" ' . $data_shortcode_settings . '>' .
								'<div class="tdc-element-ico tdc-ico-' . $mapped_shortcode['base'] . '"></div>' .
								'<div class="tdc-element-id">' . $mapped_shortcode['name'] . '</div>' .
								'</div>';

							echo $buffer;
						}
					}


					if ( ! empty( $multipurpose_mapped_shortcodes ) ) {

						// Separator
						echo '<div class="tdc-sidebar-separator">Multipurpose shortcodes</div>';

						// Here will be displayed the multipurpose shortcodes
						foreach ( $multipurpose_mapped_shortcodes as $mapped_shortcode ) {

							$data_row_start_values = '';

							if ( isset( $mapped_shortcode['tdc_in_row'] ) && true === $mapped_shortcode['tdc_in_row'] ) {
								$tdc_class = 'tdc-element-with-row tdc-row-temp';
							} else {
								$tdc_class = 'tdc-element';
							}

							$data_shortcode_settings = get_data_shortcode_settings( $mapped_shortcode );

							$buffer =
								'<div class="tdc-sidebar-element ' . $tdc_class . '" data-shortcode-name="' . $mapped_shortcode['base'] . '" ' . $data_shortcode_settings . '>' .
								'<div class="tdc-element-ico tdc-ico-' . $mapped_shortcode['base'] . '"></div>' .
								'<div class="tdc-element-id">' . $mapped_shortcode['name'] . '</div>' .
								'</div>';

							echo $buffer;
						}
					}


					if ( ! empty( $template_shortcodes ) ) {

						// Separator
						echo '<div class="tdc-sidebar-separator">Template shortcodes</div>';

						// Here will be displayed the template shortcodes
						foreach ( $template_shortcodes as $template_id => $template_shortcode ) {

							$buffer =
								'<div class="tdc-sidebar-element tdc-row-temp" data-template-content="' . $template_shortcode['content'] . '" data-shortcode-name="vc_row">' .
								'<div class="tdc-element-ico tdc-ico-template"></div>' .
								'<div class="tdc-element-id">' . $template_shortcode['name'] . '</div>' .
								'</div>';

							echo $buffer;
						}
					}

					// Separator
					echo '<div class="tdc-sidebar-separator tdc-sidebar-saved-shortcodes">Saved shortcodes</div>';

					?>
				</div>
			</div>
		</div>


		<!-- modal window -->
		<div class="tdc-sidebar-modal tdc-sidebar-modal-menu" data-button_class="tdc-main-menu">
			<div class="tdc-sidebar-modal-content">
				<div id="tdc-theme-panel">
					<?php
						require_once( plugin_dir_path( __FILE__ ) . '../panel/tdc_header.php');
					?>
				</div>
			</div>
		</div>

		<div id="tdc-icon-selector">
			<div class="tdc-icon-selector-head">
				<select class="tdc-icon-selector-lib"><option value="">All</option>

					<?php
					foreach ( tdc_config::$font_settings as $font_id => $font_settings ) {
						echo '<option value="' . $font_id . '">' . $font_settings['name'] . '</option>';
					}
					?>

				</select>
				<div class="tdc-icon-selector-wrap">
					<input class="tdc-icon-selector-filter" placeholder="Search icon..." type="text"/>
				</div>
			</div>
			<div class="tdc-icon-selector-content-wrap">
				<div class="tdc-icon-selector-content">

					<?php
					$buffer = '';

					foreach ( tdc_config::$font_settings as $font_id => $font_settings ) {
						$buffer .= '<div class="tdc-font-separator" data-font_id="' . $font_id . '">' . $font_settings['name'] . '</div>';

						ob_start();
							include_once( $font_settings['template_file'] );
						$buffer .= ob_get_clean();
					}

					echo $buffer;
					?>

				</div>
			</div>
		</div>

		<div id="tdc-palette">
			<input type="text" val="" id="tdc-palette-color-picker"/>
		</div>

		<div id="tdc-gradient">
			<input type="text" val="" id="tdc-gradient-color-picker"/>
		</div>

		<?php

		// Extensions add content
		do_action( 'tdc_extension_content' );

		?>

	</div>


	<!-- The live iFrame loads in this wrapper :) -->
	<div id="tdc-live-iframe-wrapper" class="tdc-live-iframe-wrapper-inline"></div>

	<div id="tdc-iframe-cover"></div>

	<div style="height: 1px; visibility: hidden; overflow: hidden;">

		<?php
		//$is_IE = false;   // used by wp-admin/edit-form-advanced.php
		//require_once ABSPATH . 'wp-admin/edit-form-advanced.php';
		?>

	</div>


	<div id="tdc-menu-settings">
		<header>
			<div class="title"></div>
			<div class="tdc-iframe-close-button"></div>
		</header>
		<div class="content"></div>
		<footer>
			<div class="tdc-iframe-apply-button"></div>
			<div class="tdc-iframe-ok-button"></div>
		</footer>
	</div>

	<div id="tdc-wpeditor">
		<header>
			<div id="title">WP Editor</div>
			<div class="tdc-iframe-close-button"></div>
		</header>
		<div class="content"></div>
	</div>

	<div id="tdc-page-settings">
		<header>
			<div class="title"></div>
			<div class="tdc-iframe-close-button"></div>
		</header>
		<div class="content"></div>
		<footer>
			<div class="tdc-iframe-apply-button"></div>
			<div class="tdc-iframe-ok-button"></div>
		</footer>
	</div>




<?php
require_once( ABSPATH . 'wp-admin/admin-footer.php' );


