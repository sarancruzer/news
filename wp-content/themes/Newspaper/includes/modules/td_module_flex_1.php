<?php

class td_module_flex_1 extends td_module {

    function __construct($post, $module_atts = array()) {
        //run the parrent constructor
        parent::__construct($post, $module_atts);
    }

    function render() {
        ob_start();

        $image_size = $this->get_shortcode_att('image_size');
        $category_position = $this->get_shortcode_att('modules_category');
        $btn_title = $this->get_shortcode_att('btn_title');
        $title_length = $this->get_shortcode_att('mc1_tl');
        $excerpt_length = $this->get_shortcode_att('mc1_el');
        $excerpt_position = $this->get_shortcode_att('excerpt_middle');

        if (empty($image_size)) {
            $image_size = 'td_696x0';
        }
        if (empty($btn_title)) {
            $btn_title = 'Read more';
        }

        $td_category_on = '';
        if (td_util::get_option('tds_category_module_flex_1') == 'yes') {
            $td_category_on = true;
        }

        $excerpt = '<div class="td-excerpt">';
            $excerpt .= $this->get_excerpt($excerpt_length);
        $excerpt .= '</div>';

        ?>

        <div class="<?php echo $this->get_module_classes();?>">
            <div class="td-module-container td-category-pos-<?php echo $category_position; ?>">
                <div class="td-image-container">
                    <?php if ($td_category_on == true && $category_position == 'image') { echo $this->get_category(); }?>
                    <?php echo $this->get_image($image_size, true);?>
                </div>

                <div class="td-module-meta-info">
                    <?php if ($td_category_on == true && $category_position == 'above') { echo $this->get_category(); }?>

                    <?php echo $this->get_title($title_length);?>

                    <?php if ($excerpt_position == 'yes') { echo $excerpt; } ?>

                    <div class="td-editor-date">
                        <?php if ($td_category_on == true && $category_position == '') { echo $this->get_category(); }?>

                        <span class="td-author-date">
                            <?php echo $this->get_author();?>
                            <?php echo $this->get_date();?>
                            <?php echo $this->get_comments();?>
                        </span>
                    </div>

                    <?php if ($excerpt_position == '') { echo $excerpt; } ?>

                    <div class="td-read-more">
                        <a href="<?php echo $this->href;?>"><?php echo __td($btn_title, TD_THEME_NAME);?></a>
                    </div>
                </div>
            </div>
        </div>

        <?php return ob_get_clean();
    }
}