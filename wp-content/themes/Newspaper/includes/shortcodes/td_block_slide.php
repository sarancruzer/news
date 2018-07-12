<?php

class td_block_slide extends td_block {

    static function cssMedia( $res_ctx ) {

        // fonts
        $res_ctx->load_font_settings( 'f_header' );
        $res_ctx->load_font_settings( 'f_ajax' );
        $res_ctx->load_font_settings( 'f_more' );

        // module slide fonts
        $res_ctx->load_font_settings( 'msf_title' );
        $res_ctx->load_font_settings( 'msf_cat' );
        $res_ctx->load_font_settings( 'msf_meta' );

    }

    public function get_custom_css() {
        // $unique_block_class - the unique class that is on the block. use this to target the specific instance via css
        $unique_block_class = $this->block_uid . '_rand';

        $compiled_css = '';

        $raw_css =
            "<style>

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
				/* @f_more */
				.$unique_block_class .td-load-more-wrap a {
					@f_more
				}
				/* @msf_title */
				.$unique_block_class .td_module_slide .entry-title {
					@msf_title
				}
				/* @msf_cat */
				.$unique_block_class .td_module_slide .slide-meta-cat a {
					@msf_cat
				}
				/* @msf_meta */
				.$unique_block_class .td_module_slide .td-module-meta-info,
				.$unique_block_class .td_module_slide .td-module-comments a {
					@msf_meta
				}
			</style>";


        $td_css_res_compiler = new td_css_res_compiler( $raw_css );
        $td_css_res_compiler->load_settings( __CLASS__ . '::cssMedia', $this->get_all_atts() );

        $compiled_css .= $td_css_res_compiler->compile_css();

        return $compiled_css;
    }


    function render($atts, $content = null){
        parent::render($atts); // sets the live atts, $this->atts, $this->block_uid, $this->td_query (it runs the query)


        extract(shortcode_atts(
            array(
                'autoplay' => ''
            ),$atts));

        $buffy = ''; //output buffer





        if ($this->td_query->have_posts() and $this->td_query->found_posts > 1 ) {

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

                $buffy .= '<div id=' . $this->block_uid . ' class="td_block_inner">';
                    $buffy .= $this->inner($this->td_query->posts, '' , $autoplay);
                $buffy .= '</div>';
            $buffy .= '</div> <!-- ./block1 -->';

        } else if (td_util::tdc_is_live_editor_iframe() or td_util::tdc_is_live_editor_ajax()) {
	        $buffy .= '<div class="td_block_wrap tdc-no-posts"><div class="td_block_inner"></div></div>';
        }
        return $buffy;
    }


    /**
     * @param $posts
     * @param string $td_column_number - get the column number
     * @param string $autoplay - not use via ajax
     * @param bool $is_ajax - if true the script will return the js inline, if not, it will use the td_js_buffer class
     * @return string
     */
    function inner($posts, $td_column_number = '', $autoplay = '', $is_ajax = false) {
        $buffy = '';

        if (empty($td_column_number)) {
            $td_column_number = td_global::vc_get_column_number(); // get the column width of the block from the page builder API
        }

        $td_post_count = 0; // the number of posts rendered

        $td_unique_id_slide = td_global::td_generate_unique_id();

        //@generic class for sliders : td-theme-slider
        $buffy .= '<div id="' . $td_unique_id_slide . '" class="td-theme-slider iosSlider-col-' . $td_column_number . ' td_mod_wrap">';
            $buffy .= '<div class="td-slider ">';
                if (!empty($posts)) {
                    foreach ($posts as $post) {
                        //$buffy .= td_modules::mod_slide_render($post, $td_column_number, $td_post_count);
                        $td_module_slide = new td_module_slide($post);
                        $buffy .= $td_module_slide->render($td_column_number, $td_post_count, $td_unique_id_slide);
                        $td_post_count++;

	                    // Show only the first frame in tagDiv composer
	                    if (td_util::tdc_is_live_editor_iframe() or td_util::tdc_is_live_editor_ajax()) {
		                    break;
	                    }
                    }
                }
            $buffy .= '</div>'; //close slider

            $buffy .= '<i class = "td-icon-left prevButton"></i>';
            $buffy .= '<i class = "td-icon-right nextButton"></i>';

        $buffy .= '</div>'; //close ios

	    // Suppress any iosSlider in tagDiv composer
	    if (td_util::tdc_is_live_editor_iframe() or td_util::tdc_is_live_editor_ajax()) {
		    return $buffy;
	    }

        if (!empty($autoplay)) {
            $autoplay_string =  '
            autoSlide: true,
            autoSlideTimer: ' . $autoplay * 1000 . ',
            ';
        } else {
            $autoplay_string = '';
        }

        //add resize events
        //$add_js_resize = '';
        //if($td_column_number > 1) {
            $add_js_resize = ',
                //onSliderLoaded : td_resize_normal_slide,
                //onSliderResize : td_resize_normal_slide_and_update';
        //}


        $slide_js = '
jQuery(document).ready(function() {
    jQuery("#' . $td_unique_id_slide . '").iosSlider({
        snapToChildren: true,
        desktopClickDrag: true,
        keyboardControls: false,
        responsiveSlideContainer: true,
        responsiveSlides: true,
        ' . $autoplay_string. '

        infiniteSlider: true,
        navPrevSelector: jQuery("#' . $td_unique_id_slide . ' .prevButton"),
        navNextSelector: jQuery("#' . $td_unique_id_slide . ' .nextButton")
        ' . $add_js_resize . '
    });
});
    ';

        if ($is_ajax) {
            $buffy .= '<script>' . $slide_js . '</script>';
        } else {
            td_js_buffer::add_to_footer($slide_js);
        }

        return $buffy;
    }
}