<?php
/**
 * Created by PhpStorm.
 * User: andromeda
 * Date: 2/19/2018
 * Time: 12:23 PM
 */


class tdc_version_check {


    static $theme_versions = array (
        'Newspaper' => '8.7'
    );


    static function is_theme_compatible() {


        if (TD_THEME_VERSION == '__td_deploy_version__' || TDC_DEPLOY_MODE == 'demo' || TDC_DEPLOY_MODE == 'dev') {
            return true;
        }


        if (version_compare(TD_THEME_VERSION, self::$theme_versions[TD_THEME_NAME], '<')) {
            add_action( 'admin_notices', array(__CLASS__, 'on_admin_notice_theme_version'));
            return false;
        }
        return true;
    }


    static function on_admin_notice_theme_version() {
        ?>
        <div class="notice notice-error">
            <p><strong>TD Composer</strong> - This plugin requires <strong><?php echo TD_THEME_NAME?> v<?php echo self::$theme_versions[TD_THEME_NAME] ?></strong> but the current installed version is <strong><?php echo TD_THEME_NAME?> v<?php echo TD_THEME_VERSION?></strong>. </p>

            <p>To fix this:</p>

            <ul>
                <li> - Delete the TD Composer plugin via wp-admin</li>
                <li> - Install the version that is bundeled with the theme from our Plugins Panel</li>
            </ul>
        </div>

        <?php
    }

}