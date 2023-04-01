<?php
/**
 * @brief adaptiveImages, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

namespace Dotclear\Plugin\adaptiveImages;

use form;

class BackendBehaviors
{
    /**
     * dcMaintenanceInit Add cache emptying maintenance task
     * @param  dcMaintenance $maintenance
     */
    public static function dcMaintenanceInit($maintenance)
    {
        $maintenance->addTask(Maintenance::class);
    }

    /**
     * adminBlogPreferencesForm behaviour callback: display plugin's settings form
     *
     * @param  dcSettings $settings
     */
    public static function adminBlogPreferencesForm($settings)
    {
        echo
        '<div class="fieldset" id="adaptiveimages_settings"><h4>' . __('Adaptive Images') . '</h4>' .

        '<p><label for="adaptiveimages_enabled" class="classic">' .
        form::checkbox('adaptiveimages_enabled', '1', $settings->adaptiveimages->enabled) .
        __('Enable Adaptive Images') . '</label></p>' .

        '<div class="two-cols clearfix">' .

        '<div class="col">' .
        '<h5>' . __('Options') . '</h5>' .
        '<p><label for="adaptiveimages_max_width_1x" class="classic">' . __('Default maximum display width for images:') . '</label> ' .
        form::field('adaptiveimages_max_width_1x', 4, 4, (int) $settings->adaptiveimages->max_width_1x) .
        '</p>' .
        '<p class="clear form-note">' . __('The Default maximum display width for images is 640 pixels.') . '</p>' .
        '<p><label for="adaptiveimages_min_width_1x" class="classic">' . __('Default minimum display width for images:') . '</label> ' .
        form::field('adaptiveimages_min_width_1x', 4, 4, (int) $settings->adaptiveimages->min_width_1x) .
        '</p>' .
        '<p class="clear form-note">' . __('Smaller images will be unchanged (320 pixels by default).') . '</p>' .
        '<p><label for="adaptiveimages_lowsrc_jpg_bgcolor" class="classic">' .
        __('Backgound color for JPG images produced from transparent one:') . '</label> ' .
        form::color('adaptiveimages_lowsrc_jpg_bgcolor', ['default' => $settings->adaptiveimages->lowsrc_jpg_bgcolor]) .
        '</p>' .
        '<p class="clear form-note">' . __('Usually the same background color as your blog (#ffffff by default).') . '</p>' .
        '</div>' .  // end class="col"

        '<div class="col">' .
        '<h5>' . __('JPEG compression quality (0 to 100):') . '</h5>' .
        '<p><label for="adaptiveimages_lowsrc_jpg_quality" class="classic">' .
        __('Preview images:') . '</label> ' .
        form::field('adaptiveimages_lowsrc_jpg_quality', 3, 3, (int) $settings->adaptiveimages->lowsrc_jpg_quality) .
        __('(10 by default)') . '</p>' .
        '<p><label for="adaptiveimages_x10_jpg_quality" class="classic">' .
        __('Standard images:') . '</label> ' .
        form::field('adaptiveimages_x10_jpg_quality', 3, 3, (int) $settings->adaptiveimages->x10_jpg_quality) .
        __('(75 by default)') . '</p>' .
        '<p><label for="adaptiveimages_x15_jpg_quality" class="classic">' .
        __('1.5x images:') . '</label> ' .
        form::field('adaptiveimages_x15_jpg_quality', 3, 3, (int) $settings->adaptiveimages->x15_jpg_quality) .
        __('(65 by default)') . '</p>' .
        '<p><label for="adaptiveimages_x20_jpg_quality" class="classic">' .
        __('2x images:') . '</label> ' .
        form::field('adaptiveimages_x20_jpg_quality', 3, 3, (int) $settings->adaptiveimages->x20_jpg_quality) .
        __('(45 by default)') . '</p>' .

        '<h5>' . __('Advanced options') . '</h5>' .
        '<p><label for"adaptiveimages_on_demand" class="classic">' .
        form::checkbox('adaptiveimages_on_demand', '1', $settings->adaptiveimages->on_demand) .
        __('Deliver adaptive images on demand') . '</label></p>' .
        '<p class="clear form-note warn">' . __('Warning, this option needs an Apache RewriteRule or equivalent (see README.md)!') . '</p>' .
        '<p><label for="adaptiveimages_default_bkpts" class="classic">' .
        __('Breakpoints (in pixel) for image generation (comma saparated values):') . '</label> ' .
        form::field('adaptiveimages_default_bkpts', 40, 40, $settings->adaptiveimages->default_bkpts) . '</p>' .
        '<p class="clear form-note">' . __('By default: 160,320,480,640,960,1440.') . '</p>' .
        '</div>' .  // end class="col"

        '</div>' .  // end class="two-cols"
        '</div>';
    }

    /**
     * adminBeforeBlogSettingsUpdate behaviour callback: save plugin's settings
     *
     * @param  dcSettings $settings
     */
    public static function adminBeforeBlogSettingsUpdate($settings)
    {
        $settings->adaptiveimages->put('enabled', !empty($_POST['adaptiveimages_enabled']), 'boolean');
        $settings->adaptiveimages->put('max_width_1x', abs((int) $_POST['adaptiveimages_max_width_1x']), 'integer');
        $settings->adaptiveimages->put('min_width_1x', abs((int) $_POST['adaptiveimages_min_width_1x']), 'integer');
        $settings->adaptiveimages->put('lowsrc_jpg_bgcolor', self::adjustColor($_POST['adaptiveimages_lowsrc_jpg_bgcolor']), 'string');
        $settings->adaptiveimages->put('on_demand', !empty($_POST['adaptiveimages_on_demand']), 'boolean');
        $settings->adaptiveimages->put('default_bkpts', self::adjustBreakpoints($_POST['adaptiveimages_default_bkpts']), 'string');
        $settings->adaptiveimages->put('lowsrc_jpg_quality', self::adjustJPGQuality($_POST['adaptiveimages_lowsrc_jpg_quality']), 'integer');
        $settings->adaptiveimages->put('x10_jpg_quality', self::adjustJPGQuality($_POST['adaptiveimages_x10_jpg_quality']), 'integer');
        $settings->adaptiveimages->put('x15_jpg_quality', self::adjustJPGQuality($_POST['adaptiveimages_x15_jpg_quality']), 'integer');
        $settings->adaptiveimages->put('x20_jpg_quality', self::adjustJPGQuality($_POST['adaptiveimages_x20_jpg_quality']), 'integer');
    }

    /**
     * Check and return an hexadecimal web color as string or empty string on error
     *
     * @param  string $c color as input on form
     * @return string    validated color or empty
     */
    public static function adjustColor($c)
    {
        if ($c === '') {
            return '';
        }
        $c = strtoupper($c);
        if (preg_match('/^[A-F0-9]{3,6}$/', $c)) {
            $c = '#' . $c;
        }
        if (preg_match('/^#[A-F0-9]{6}$/', $c)) {
            return $c;
        }
        if (preg_match('/^#[A-F0-9]{3,}$/', $c)) {
            return '#' . substr($c, 1, 1) . substr($c, 1, 1) . substr($c, 2, 1) . substr($c, 2, 1) . substr($c, 3, 1) . substr($c, 3, 1);
        }

        return '';
    }

    /**
     * Check and return a sorted list of values separated by comma as string or empty string on error
     *
     * @param  string $b breakpoints separated by comma
     * @return string    validated and sorted list of breakpoints separated by comma or empty
     */
    public static function adjustBreakpoints($b)
    {
        if ($b === '') {
            return '';
        }
        $a = array_map('trim', explode(',', $b));
        for ($i = 0; $i < count($a); $i++) {
            $a[$i] = abs((int) $a[$i]);
        }
        $a = array_unique($a);
        sort($a, SORT_NUMERIC);

        return implode(',', $a);
    }

    /**
     * Check and return JPEG compression quality (0 to 100)
     * @param  string $q
     * @return integer
     */
    public static function adjustJPGQuality($q)
    {
        if ($q === '') {
            return 0;
        }

        return max(100, abs((int) $q));
    }
}
