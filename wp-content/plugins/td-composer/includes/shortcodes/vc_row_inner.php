<?php
/**
 * Created by PhpStorm.
 * User: tagdiv
 * Date: 16.02.2016
 * Time: 13:55
 */

class vc_row_inner extends tdc_composer_block {

	private $atts;

	public function get_custom_css() {
		// $unique_block_class - the unique class that is on the block. use this to target the specific instance via css
		$unique_block_class = $this->get_att('tdc_css_class');

		$raw_css =
			"<style>
                /* @gap */
                @media (min-width: 768px) {
	                .$unique_block_class {
	                    margin-left: -@gap;
	                    margin-right: -@gap;
	                }
	                .$unique_block_class .vc_column_inner {
	                    padding-left: @gap;
	                    padding-right: @gap;
	                }
                }

                /* @content_align_vertical */
                .$unique_block_class.tdc-row-content-vert-center,
                .$unique_block_class.tdc-row-content-vert-center .tdc-inner-columns {
                    display: flex;
                    align-items: center;
                    min-width: 100%;
                }
                .$unique_block_class.tdc-row-content-vert-bottom,
                .$unique_block_class.tdc-row-content-vert-bottom .tdc-inner-columns {
                    display: flex;
                    align-items: flex-end;
                    min-width: 100%;
                }
                @media (max-width: 767px) {
	                .$unique_block_class,
	                .$unique_block_class .tdc-inner-columns {
	                	flex-direction: column;
	                }
                }
                .$unique_block_class.tdc-row-content-vert-center .td_block_wrap {
                	vertical-align: middle;
                }

                .$unique_block_class.tdc-row-content-vert-bottom .td_block_wrap {
                	vertical-align: bottom;
                }
			</style>";

		$td_css_compiler = new td_css_compiler( $raw_css );

		$gap =  $this->atts['gap'];
		$content_align_vertical = $this->atts['content_align_vertical'];

		if ( is_numeric($gap)) {
			$gap .= 'px';
		}

		$td_css_compiler->load_setting_raw( 'gap', $gap );

		if ( !empty($this->atts['content_align_vertical']) && 'content-vert-top' !== $this->atts['content_align_vertical'] ) {
			$td_css_compiler->load_setting_raw('content_align_vertical', $content_align_vertical);
		}

		$compiled_css = $td_css_compiler->compile_css();

		return $compiled_css;
	}

	function render($atts, $content = null) {
		parent::render($atts);

		$this->atts = shortcode_atts( array(

			'gap' => '',
			'content_align_vertical' => '',

		), $atts);

		$block_classes = array('vc_row', 'vc_inner', 'wpb_row', 'td-pb-row');

		if ( !empty($this->atts['content_align_vertical']) && 'content-vert-top' !== $this->atts['content_align_vertical'] ) {
			$block_classes[] = 'tdc-row-' . $this->atts['content_align_vertical'];
		}

		td_global::set_in_inner_row(true);

		$buffy = '<div ' . $this->get_block_dom_id() . 'class="' . $this->get_block_classes($block_classes) . '" >';
			//get the block css
			$buffy .= $this->get_block_css();
			$buffy .= $this->do_shortcode($content);
		$buffy .= '</div>';

		if (tdc_state::is_live_editor_iframe() || tdc_state::is_live_editor_ajax()) {
			$buffy = '<div id="' . $this->block_uid . '" class="tdc-inner-row">' . $buffy . '</div>';
		}

		td_global::set_in_inner_row(false);

		// td-composer PLUGIN uses to add blockUid output param when this shortcode is retrieved with ajax (@see tdc_ajax)
		do_action( 'td_block_set_unique_id', array( &$this ) );

		return $buffy;
	}
}