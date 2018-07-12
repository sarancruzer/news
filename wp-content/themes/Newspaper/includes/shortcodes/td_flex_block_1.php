<?php

class td_flex_block_1 extends td_block {

    static function cssMedia( $res_ctx ) {


	    // container_width
	    $container_width = $res_ctx->get_shortcode_att('container_width');
	    if ( is_numeric( $container_width ) ) {
		    $res_ctx->load_settings_raw( 'container_width', $container_width . '%' );
	    } else {
		    $res_ctx->load_settings_raw( 'container_width', $container_width );
	    }

	    // image_height
	    $image_height = $res_ctx->get_shortcode_att('image_height');
	    if ( is_numeric( $image_height ) ) {
		    $res_ctx->load_settings_raw( 'image_height', $image_height . '%' );
	    } else {
		    $res_ctx->load_settings_raw( 'image_height', $image_height );
	    }

		// image_width
	    $image_width = $res_ctx->get_shortcode_att('image_width');
	    if ( is_numeric( $image_width ) ) {
		    $res_ctx->load_settings_raw( 'image_width', $image_width . '%' );
	    } else {
		    $res_ctx->load_settings_raw( 'image_width', $image_width );
	    }

	    // image_floated
	    $image_floated = $res_ctx->get_shortcode_att('image_floated');
	    if ( $image_floated == '' ||  $image_floated == 'no_float' ) {
		    $image_floated = 'no_float';
		    $res_ctx->load_settings_raw( 'no_float',  1 );
        }
        if ( $image_floated == 'float_left' ) {
	        $res_ctx->load_settings_raw( 'float_left',  1 );
        }
        if ( $image_floated == 'float_right' ) {
	        $res_ctx->load_settings_raw( 'float_right',  1 );
        }
        if ( $image_floated == 'hidden' ) {
	        if ( $res_ctx->is( 'all' ) && !$res_ctx->is_responsive_att( 'image_floated' ) ) {
		        $res_ctx->load_settings_raw( 'hide_desktop',  1 );
	        } else {
		        $res_ctx->load_settings_raw( 'hide',  1 );
	        }
        }

	    // meta info margin
	    $meta_margin = $res_ctx->get_shortcode_att('meta_margin');
	    $res_ctx->load_settings_raw( 'meta_margin', $meta_margin );
	    if ( is_numeric( $meta_margin ) ) {
		    $res_ctx->load_settings_raw( 'meta_margin', $meta_margin . 'px' );
	    }
	    // meta info padding
	    $meta_padding = $res_ctx->get_shortcode_att('meta_padding');
	    $res_ctx->load_settings_raw( 'meta_padding', $meta_padding );
	    if ( is_numeric( $meta_padding ) ) {
		    $res_ctx->load_settings_raw( 'meta_padding', $meta_padding . 'px' );
	    }

	    // meta info align
        $meta_info_align = $res_ctx->get_shortcode_att('meta_info_align');
	    $res_ctx->load_settings_raw( 'meta_info_align', $meta_info_align );
	    // meta info align to fix top when no float is selected
        if ( $meta_info_align == 'initial' && $image_floated == 'no_float' ) {
	        $res_ctx->load_settings_raw( 'meta_info_align_top',  1 );
        }
        // meta info align top/bottom - align category
        if ( $meta_info_align == 'initial' ) {
	        $res_ctx->load_settings_raw( 'align_category_top',  1 );
        }
        if ( $meta_info_align == 'flex-end' ) {
	        $res_ctx->load_settings_raw( 'align_category_bottom',  1 );
        }

	    // meta_info_border_size
	    $meta_info_border_size = $res_ctx->get_shortcode_att('meta_info_border_size');
	    $res_ctx->load_settings_raw( 'meta_info_border_size', $meta_info_border_size );
	    if ( is_numeric( $meta_info_border_size ) ) {
		    $res_ctx->load_settings_raw( 'meta_info_border_size', $meta_info_border_size . 'px' );
	    }
	    // meta info border style
	    $res_ctx->load_settings_raw( 'meta_info_border_style', $res_ctx->get_shortcode_att('meta_info_border_style') );
	    // meta info border color
	    $res_ctx->load_settings_raw( 'meta_info_border_color', $res_ctx->get_shortcode_att('meta_info_border_color') );

	    // modules per row
	    $modules_on_row = $res_ctx->get_shortcode_att('modules_on_row');
	    $res_ctx->load_settings_raw( 'modules_on_row', $modules_on_row );
	    if ( $modules_on_row == '' ) {
		    $modules_on_row = '100%';
	    }

	    // modules space
	    $modules_space = $res_ctx->get_shortcode_att('all_modules_space');
	    $res_ctx->load_settings_raw( 'all_modules_space', $modules_space );
	    if ( $modules_space == '' ) {
		    $res_ctx->load_settings_raw( 'all_modules_space', '18px');
	    } else if ( is_numeric( $modules_space ) ) {
		    $res_ctx->load_settings_raw( 'all_modules_space', $modules_space / 2 .'px' );
	    }

	    // modules clearfix
	    $clearfix = 'clearfix';
	    $padding = 'padding';
	    if ( $res_ctx->is( 'all' ) ) {
		    $clearfix = 'clearfix_desktop';
		    $padding = 'padding_desktop';
	    }
	    switch ($modules_on_row) {
		    case '100%':
			    $res_ctx->load_settings_raw( $padding,  '1' );
			    break;
		    case '50%':
			    $res_ctx->load_settings_raw( $clearfix,  '2n+1' );
			    $res_ctx->load_settings_raw( $padding,  '-n+2' );
			    break;
		    case '33.33333333%':
			    $res_ctx->load_settings_raw( $clearfix,  '3n+1' );
			    $res_ctx->load_settings_raw( $padding,  '-n+3' );
			    break;
		    case '25%':
			    $res_ctx->load_settings_raw( $clearfix,  '4n+1' );
			    $res_ctx->load_settings_raw( $padding,  '-n+4' );
			    break;
		    case '20%':
			    $res_ctx->load_settings_raw( $clearfix,  '5n+1' );
			    $res_ctx->load_settings_raw( $padding,  '-n+5' );
			    break;
		    case '16.66666667%':
			    $res_ctx->load_settings_raw( $clearfix,  '6n+1' );
			    $res_ctx->load_settings_raw( $padding,  '-n+6' );
			    break;
		    case '14.28571428%':
			    $res_ctx->load_settings_raw( $clearfix,  '7n+1' );
			    $res_ctx->load_settings_raw( $padding,  '-n+7' );
			    break;
		    case '12.5%':
			    $res_ctx->load_settings_raw( $clearfix,  '8n+1' );
			    $res_ctx->load_settings_raw( $padding,  '-n+8' );
			    break;
		    case '11.11111111%':
			    $res_ctx->load_settings_raw( $clearfix,  '9n+1' );
			    $res_ctx->load_settings_raw( $padding,  '-n+9' );
			    break;
		    case '10%':
			    $res_ctx->load_settings_raw( $clearfix,  '10n+1' );
			    $res_ctx->load_settings_raw( $padding,  '-n+10' );
			    break;
	    }

	    // modules gap
	    $modules_gap = $res_ctx->get_shortcode_att('modules_gap');
	    $res_ctx->load_settings_raw( 'modules_gap', $modules_gap );
	    if ( $modules_gap == '' ) {
		    $res_ctx->load_settings_raw( 'modules_gap', '20px');
	    } else if ( is_numeric( $modules_gap ) ) {
		    $res_ctx->load_settings_raw( 'modules_gap', $modules_gap / 2 .'px' );
	    }
	    // modules padding
	    $m_padding = $res_ctx->get_shortcode_att('m_padding');
	    $res_ctx->load_settings_raw( 'm_padding', $m_padding );
	    if ( is_numeric( $m_padding ) ) {
		    $res_ctx->load_settings_raw( 'm_padding', $m_padding . 'px' );
	    }

	    // modules divider
	    $res_ctx->load_settings_raw( 'modules_divider', $res_ctx->get_shortcode_att('modules_divider') );
	    // modules divider color
	    $res_ctx->load_settings_raw( 'modules_divider_color', $res_ctx->get_shortcode_att('modules_divider_color') );

		// image radius
	    $image_radius = $res_ctx->get_shortcode_att('image_radius');
	    $res_ctx->load_settings_raw( 'image_radius', $image_radius );
	    if ( is_numeric( $image_radius ) ) {
		    $res_ctx->load_settings_raw( 'image_radius', $image_radius . 'px' );
	    }

	    // article title space
	    $art_title = $res_ctx->get_shortcode_att('art_title');
	    $res_ctx->load_settings_raw( 'art_title', $art_title );
	    if ( is_numeric( $art_title ) ) {
		    $res_ctx->load_settings_raw( 'art_title', $art_title . 'px' );
	    }
	    // article excerpt space
	    $art_excerpt = $res_ctx->get_shortcode_att('art_excerpt');
	    $res_ctx->load_settings_raw( 'art_excerpt', $art_excerpt );
	    if ( is_numeric( $art_excerpt ) ) {
		    $res_ctx->load_settings_raw( 'art_excerpt', $art_excerpt . 'px' );
	    }
	    // article button space
	    $art_btn = $res_ctx->get_shortcode_att('art_btn');
	    $res_ctx->load_settings_raw( 'art_btn', $art_btn );
	    if ( is_numeric( $art_btn ) ) {
		    $res_ctx->load_settings_raw( 'art_btn', $art_btn . 'px' );
	    }

	    // category tag space
	    $res_ctx->load_settings_raw( 'modules_category_padding', $res_ctx->get_shortcode_att('modules_category_padding') );
	    // show meta info details
	    $res_ctx->load_settings_raw( 'show_cat', $res_ctx->get_shortcode_att('show_cat') );
	    $res_ctx->load_settings_raw( 'show_excerpt', $res_ctx->get_shortcode_att('show_excerpt') );
	    $res_ctx->load_settings_raw( 'show_btn', $res_ctx->get_shortcode_att('show_btn') );
	    $res_ctx->load_settings_raw( 'show_author', $res_ctx->get_shortcode_att('show_author') );
	    $res_ctx->load_settings_raw( 'show_date', $res_ctx->get_shortcode_att('show_date') );
	    $res_ctx->load_settings_raw( 'show_com', $res_ctx->get_shortcode_att('show_com') );

	    // colors
	    $res_ctx->load_settings_raw( 'm_bg', $res_ctx->get_shortcode_att('m_bg') );
	    $res_ctx->load_settings_raw( 'meta_bg', $res_ctx->get_shortcode_att('meta_bg') );
	    $res_ctx->load_settings_raw( 'cat_bg', $res_ctx->get_shortcode_att('cat_bg') );
	    $res_ctx->load_settings_raw( 'cat_txt', $res_ctx->get_shortcode_att('cat_txt') );
	    $res_ctx->load_settings_raw( 'cat_bg_hover', $res_ctx->get_shortcode_att('cat_bg_hover') );
	    $res_ctx->load_settings_raw( 'cat_txt_hover', $res_ctx->get_shortcode_att('cat_txt_hover') );
	    $res_ctx->load_settings_raw( 'title_txt', $res_ctx->get_shortcode_att('title_txt') );
	    $res_ctx->load_settings_raw( 'title_txt_hover', $res_ctx->get_shortcode_att('title_txt_hover') );
	    $res_ctx->load_settings_raw( 'author_txt', $res_ctx->get_shortcode_att('author_txt') );
	    $res_ctx->load_settings_raw( 'author_txt_hover', $res_ctx->get_shortcode_att('author_txt_hover') );
	    $res_ctx->load_settings_raw( 'date_txt', $res_ctx->get_shortcode_att('date_txt') );
	    $res_ctx->load_settings_raw( 'ex_txt', $res_ctx->get_shortcode_att('ex_txt') );
	    $res_ctx->load_settings_raw( 'com_bg', $res_ctx->get_shortcode_att('com_bg') );
	    $res_ctx->load_settings_raw( 'com_txt', $res_ctx->get_shortcode_att('com_txt') );
	    $res_ctx->load_settings_raw( 'btn_bg', $res_ctx->get_shortcode_att('btn_bg') );
	    $res_ctx->load_settings_raw( 'btn_bg_hover', $res_ctx->get_shortcode_att('btn_bg_hover') );
	    $res_ctx->load_settings_raw( 'btn_txt', $res_ctx->get_shortcode_att('btn_txt') );
	    $res_ctx->load_settings_raw( 'btn_txt_hover', $res_ctx->get_shortcode_att('btn_txt_hover') );


	    // fonts
	    $res_ctx->load_font_settings( 'f_header' );
	    $res_ctx->load_font_settings( 'f_ajax' );
	    $res_ctx->load_font_settings( 'f_title' );
	    $res_ctx->load_font_settings( 'f_cat' );
	    $res_ctx->load_font_settings( 'f_meta' );
	    $res_ctx->load_font_settings( 'f_ex' );
	    $res_ctx->load_font_settings( 'f_btn' );
	    $res_ctx->load_font_settings( 'f_more' );

    }

    public function get_custom_css() {
        // $unique_block_class - the unique class that is on the block. use this to target the specific instance via css
        $unique_block_class = $this->block_uid . '_rand';

        $compiled_css = '';

        $raw_css =
            "<style>

				/* @container_width */
				.$unique_block_class .td-mc1-wrap {
					width: @container_width; float: left;
				}
				/* @image_height */
				.$unique_block_class .td-mc1-wrap .td-image-wrap {
					padding-bottom: @image_height;
				}
				/* @image_width */
				.$unique_block_class .td-mc1-wrap .td-image-container {
				 	flex: 0 0 @image_width;
				 	width: @image_width;
			    }
				/* @no_float */
				.$unique_block_class .td-module-container {
					flex-direction: column;
				}
                .$unique_block_class .td-mc1-wrap .td-image-container {
                	display: block; order: 0;
                }
				/* @float_left */
				.$unique_block_class .td-module-container {
					flex-direction: row;
				}
                .$unique_block_class .td-mc1-wrap .td-image-container {
                	display: block; order: 0;
                }
				/* @float_right */
				.$unique_block_class .td-module-container {
					flex-direction: row;
				}
                .$unique_block_class .td-mc1-wrap .td-image-container {
                	display: block; order: 1;
                }
                /* @hide_desktop */
                .$unique_block_class .td-mc1-wrap .td-image-container {
                	display: none;
                }
                .$unique_block_class .td-mc1-wrap .entry-thumb {
                	background-image: none !important;
                }
				/* @hide */
				.$unique_block_class .td-mc1-wrap .td-image-container {
					display: none;
				}
				/* @meta_info_align_top */
				.$unique_block_class .td-mc1-wrap .td-image-container {
					order: 1;
				}
				/* @meta_margin */
				.$unique_block_class .td-mc1-wrap .td-module-meta-info {
					margin: @meta_margin;
				}
				/* @meta_padding */
				.$unique_block_class .td-mc1-wrap .td-module-meta-info {
					padding: @meta_padding;
				}
				/* @meta_info_align */
				.$unique_block_class .td-module-container {
					align-items: @meta_info_align;
				}
				/* @align_category_top */
				.$unique_block_class .td-category-pos-image .td-post-category {
					top: 0;
					bottom: auto;
				}
				/* @align_category_bottom */
				.$unique_block_class .td-category-pos-image .td-post-category {
					top: auto;
				 	bottom: 0;
			    }
				/* @meta_info_border_size */
				.$unique_block_class .td-mc1-wrap .td-module-meta-info {
					border-width: @meta_info_border_size;
				}
				/* @meta_info_border_style */
				.$unique_block_class .td-mc1-wrap .td-module-meta-info {
					border-style: @meta_info_border_style;
				}
				/* @meta_info_border_color */
				.$unique_block_class .td-mc1-wrap .td-module-meta-info {
					border-color: @meta_info_border_color;
				}
				/* @modules_on_row */
				.$unique_block_class .td-mc1-wrap .td_module_wrap {
					width: @modules_on_row;
					float: left;
				}
				/* @modules_gap */
				.$unique_block_class .td-mc1-wrap .td_module_wrap {
					padding-left: @modules_gap;
					padding-right: @modules_gap;
				}
				.$unique_block_class .td-mc1-wrap {
					margin-left: -@modules_gap;
					margin-right: -@modules_gap;
				}
				/* @all_modules_space */
				.$unique_block_class .td-mc1-wrap .td_module_wrap {
					padding-bottom: @all_modules_space;
					margin-bottom: @all_modules_space;
				}
				.$unique_block_class .td-mc1-wrap .td-module-container:before {
					bottom: -@all_modules_space;
				}
				/* @m_padding */
				.$unique_block_class .td-mc1-wrap .td-module-container {
					padding: @m_padding;
				}
				/* @modules_divider */
				.$unique_block_class .td-mc1-wrap .td-module-container:before {
					border-width: 0 0 1px 0;
					border-style: @modules_divider;
					border-color: #eaeaea;
				}
				/* @modules_divider_color */
				.$unique_block_class .td-mc1-wrap .td-module-container:before {
					border-color: @modules_divider_color;
				}
				/* @image_radius */
				.$unique_block_class .td-mc1-wrap .entry-thumb {
					border-radius: @image_radius;
				}
				/* @modules_category_padding */
				.$unique_block_class .td-mc1-wrap .td-post-category {
					margin: @modules_category_padding;
				}
				/* @show_cat */
				.$unique_block_class .td-mc1-wrap .td-post-category {
					display: @show_cat;
				}
				/* @show_excerpt */
				.$unique_block_class .td-mc1-wrap .td-excerpt {
					display: @show_excerpt;
				}
				/* @show_btn */
				.$unique_block_class .td-mc1-wrap .td-read-more {
					display: @show_btn;
				}
				/* @show_author */
				.$unique_block_class .td-mc1-wrap .td-post-author-name {
					display: @show_author;
				}
				/* @show_date */
				.$unique_block_class .td-mc1-wrap .td-post-date,
				.$unique_block_class .td-mc1-wrap .td-post-author-name span {
					display: @show_date;
				}
				/* @show_com */
				.$unique_block_class .td-mc1-wrap .td-module-comments {
					display: @show_com;
				}
				/* @clearfix_desktop */
				.$unique_block_class .td-mc1-wrap .td_module_wrap:nth-child(@clearfix_desktop) {
					clear: both;
				}
				/* @clearfix */
				.$unique_block_class .td-mc1-wrap .td_module_wrap {
					clear: none !important;
				}
				.$unique_block_class .td-mc1-wrap .td_module_wrap:nth-child(@clearfix) {
					clear: both !important;
				}
				/* @padding_desktop */
				.$unique_block_class .td-mc1-wrap .td_module_wrap:nth-last-child(@padding_desktop) {
					margin-bottom: 0;
					padding-bottom: 0;
				}
				.$unique_block_class .td-mc1-wrap .td_module_wrap:nth-last-child(@padding_desktop) .td-module-container:before {
					display: none;
				}
				/* @padding */
				.$unique_block_class .td-mc1-wrap .td_module_wrap {
					padding-bottom: @all_modules_space !important;
					margin-bottom: @all_modules_space !important;
				}
				.$unique_block_class .td-mc1-wrap .td_module_wrap:nth-last-child(@padding) {
					margin-bottom: 0 !important;
					padding-bottom: 0 !important;
				}
				.$unique_block_class .td-mc1-wrap .td_module_wrap .td-module-container:before {
					display: block !important;
				}
				.$unique_block_class .td-mc1-wrap .td_module_wrap:nth-last-child(@padding) .td-module-container:before {
					display: none !important;
				}
				/* @m_bg */
				.$unique_block_class .td-mc1-wrap .td-module-container {
					background-color: @m_bg;
				}
				/* @meta_bg */
				.$unique_block_class .td-mc1-wrap .td-module-meta-info {
					background-color: @meta_bg;
				}
				/* @cat_bg */
				.$unique_block_class .td-mc1-wrap .td-post-category {
					background-color: @cat_bg;
				}
				/* @cat_bg_hover */
				.$unique_block_class .td-mc1-wrap .td-post-category:hover {
					background-color: @cat_bg_hover;
				}
				/* @cat_txt */
				.$unique_block_class .td-mc1-wrap .td-post-category {
					color: @cat_txt;
				}
				/* @cat_txt_hover */
				.$unique_block_class .td-mc1-wrap .td-post-category:hover {
					color: @cat_txt_hover;
				}
				/* @title_txt */
				.$unique_block_class .td-mc1-wrap .td-module-title a {
					color: @title_txt;
				}
				/* @title_txt_hover */
				.$unique_block_class .td-mc1-wrap .td_module_wrap:hover .td-module-title a {
					color: @title_txt_hover;
				}
				/* @author_txt */
				.$unique_block_class .td-mc1-wrap .td-post-author-name a {
					color: @author_txt;
				}
				/* @author_txt_hover */
				.$unique_block_class .td-mc1-wrap .td-post-author-name:hover a {
					color: @author_txt_hover;
				}
				/* @date_txt */
				.$unique_block_class .td-mc1-wrap .td-post-date,
				.$unique_block_class .td-mc1-wrap .td-post-author-name span {
					color: @date_txt;
				}
				/* @ex_txt */
				.$unique_block_class .td-mc1-wrap .td-excerpt {
					color: @ex_txt;
				}
				/* @com_bg */
				.$unique_block_class .td-mc1-wrap .td-module-comments a {
					background-color: @com_bg;
				}
				.$unique_block_class .td-mc1-wrap .td-module-comments a:after {
					border-color: @com_bg transparent transparent transparent;
				}
				/* @com_txt */
				.$unique_block_class .td-mc1-wrap .td-module-comments a {
					color: @com_txt;
				}
				/* @btn_bg */
				.$unique_block_class .td-mc1-wrap .td-read-more a {
					background-color: @btn_bg;
				}
				/* @btn_bg_hover */
				.$unique_block_class .td-mc1-wrap .td-read-more:hover a {
					background-color: @btn_bg_hover !important;
				}
				/* @btn_txt */
				.$unique_block_class .td-mc1-wrap .td-read-more a {
					color: @btn_txt;
				}
				/* @btn_txt_hover */
				.$unique_block_class .td-mc1-wrap .td-read-more:hover a {
					color: @btn_txt_hover;
				}
				/* @art_title */
				.$unique_block_class .td-mc1-wrap .entry-title {
					margin: @art_title;
				}
				/* @art_excerpt */
				.$unique_block_class .td-mc1-wrap .td-excerpt {
					margin: @art_excerpt;
				}
				/* @art_btn */
				.$unique_block_class .td-mc1-wrap .td-read-more {
					margin: @art_btn;
				}

				/* @f_header */
				.$unique_block_class .td-block-title a,
				.$unique_block_class .td-block-title span {
					@f_header
				}
				/* @f_ajax */
				.$unique_block_class .td-subcat-list a,
				.$unique_block_class .td-subcat-dropdown span,
				.$unique_block_class .td-subcat-dropdown a {
					@f_ajax
				}
				/* @f_title */
				.$unique_block_class .td-mc1-wrap .entry-title {
					@f_title
				}
				/* @f_cat */
				.$unique_block_class .td-post-category {
					@f_cat
				}
				/* @f_meta */
				.$unique_block_class .td-editor-date,
				.$unique_block_class .td-module-comments a {
					@f_meta
				}
				/* @f_ex */
				.$unique_block_class .td-excerpt {
					@f_ex
				}
				/* @f_btn */
				.$unique_block_class .td-read-more a {
					@f_btn
				}
				/* @f_more */
				.$unique_block_class .td-load-more-wrap a {
					@f_more
				}
			</style>";


	    $td_css_res_compiler = new td_css_res_compiler( $raw_css );
	    $td_css_res_compiler->load_settings( __CLASS__ . '::cssMedia', $this->get_all_atts() );

		$compiled_css .= $td_css_res_compiler->compile_css();

		return $compiled_css;
    }

    function render($atts, $content = null) {

        parent::render($atts); // sets the live atts, $this->atts, $this->block_uid, $this->td_query (it runs the query)

        $buffy = ''; //output buffer

        $buffy .= '<div class="' . $this->get_block_classes() . '" ' . $this->get_block_html_atts() . '>';

		    //get the block js
		    $buffy .= $this->get_block_css();

		    //get the js for this block
		    $buffy .= $this->get_block_js();

            // block title wrap
            $buffy .= '<div class="td-block-title-wrap">';
                $buffy .= $this->get_block_title(); //get the block title
                $buffy .= $this->get_pull_down_filter(); //get the sub category filter for this block
            $buffy .= '</div>';

            $buffy .= '<div id=' . $this->block_uid . ' class="td_block_inner td-mc1-wrap">';
	                $buffy .= $this->inner($this->td_query->posts);//inner content of the block
            $buffy .= '</div>';

            //get the ajax pagination for this block
            $buffy .= $this->get_block_pagination();
        $buffy .= '</div> <!-- ./block -->';
        return $buffy;
    }

    function inner($posts) {

        $buffy = '';
        $td_block_layout = new td_block_layout();

            if (!empty($posts)) {
                foreach ($posts as $post) {

                    $td_module_flex_1 = new td_module_flex_1($post, $this->get_all_atts());
                    $buffy .= $td_module_flex_1->render($post);
                }
            }
            $buffy .= $td_block_layout->close_all_tags();

        return $buffy;
    }
}