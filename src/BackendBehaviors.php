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

use Dotclear\App;
use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Color;
use Dotclear\Helper\Html\Form\Div;
use Dotclear\Helper\Html\Form\Fieldset;
use Dotclear\Helper\Html\Form\Input;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Legend;
use Dotclear\Helper\Html\Form\Number;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Plugin\adaptiveImages\MaintenanceTask\AdaptiveImagesCache;
use Dotclear\Plugin\maintenance\Maintenance;

class BackendBehaviors
{
    /**
     * dcMaintenanceInit Add cache emptying maintenance task
     * @param  Maintenance $maintenance
     */
    public static function dcMaintenanceInit(Maintenance $maintenance): string
    {
        $maintenance->addTask(AdaptiveImagesCache::class);

        return '';
    }

    /**
     * adminBlogPreferencesForm behaviour callback: display plugin's settings form
     */
    public static function adminBlogPreferencesFormV2(): string
    {
        $settings = My::settings();

        // Add fieldset for plugin options
        echo
        (new Fieldset('adaptiveimages_settings'))
        ->legend((new Legend(__('Adaptive Images'))))
        ->fields([
            (new Para())->items([
                (new Checkbox('adaptiveimages_enabled', $settings->enabled))
                    ->value(1)
                    ->label((new Label(__('Enable Adaptive Images'), Label::INSIDE_TEXT_AFTER))),
            ]),
            (new Div())->class(['two-cols', 'clearfix'])->items([
                (new Div())->class('col')->items([
                    (new Text('h5', __('Options'))),
                    (new Para())->items([
                        (new Number('adaptiveimages_max_width_1x', 0, 9_999_999, (int) $settings->max_width_1x))
                            ->default(640)
                            ->label((new Label(__('Default maximum display width for images:'), Label::INSIDE_TEXT_BEFORE))),
                    ]),
                    (new Para())->class(['form-note', 'clear'])->items([
                        (new Text(null, __('The Default maximum display width for images is 640 pixels.'))),
                    ]),
                    (new Para())->items([
                        (new Number('adaptiveimages_min_width_1x', 0, 9_999_999, (int) $settings->min_width_1x))
                            ->default(320)
                            ->label((new Label(__('Default minimum display width for images:'), Label::INSIDE_TEXT_BEFORE))),
                    ]),
                    (new Para())->class(['form-note', 'clear'])->items([
                        (new Text(null, __('Smaller images will be unchanged (320 pixels by default).'))),
                    ]),

                    (new Para())->items([
                        (new Color('adaptiveimages_lowsrc_jpg_bgcolor', $settings->lowsrc_jpg_bgcolor))
                            ->default('#ffffff')
                            ->label((new Label(__('Backgound color for JPG images produced from transparent one:'), Label::INSIDE_TEXT_BEFORE))),
                    ]),
                    (new Para())->class(['form-note', 'clear'])->items([
                        (new Text(null, __('Usually the same background color as your blog (#ffffff by default).'))),
                    ]),
                ]),
                (new Div())->class('col')->items([
                    (new Text('h5', __('JPEG compression quality (0 to 100):'))),
                    (new Para())->items([
                        (new Number('adaptiveimages_lowsrc_jpg_quality', 0, 100, (int) $settings->lowsrc_jpg_quality))
                            ->default(10)
                            ->label((new Label(__('Preview images (10 by default):'), Label::INSIDE_TEXT_BEFORE))),
                    ]),
                    (new Para())->items([
                        (new Number('adaptiveimages_x10_jpg_quality', 0, 100, (int) $settings->x10_jpg_quality))
                            ->default(75)
                            ->label((new Label(__('Standard images (75 by default):'), Label::INSIDE_TEXT_BEFORE))),
                    ]),
                    (new Para())->items([
                        (new Number('adaptiveimages_x15_jpg_quality', 0, 100, (int) $settings->x15_jpg_quality))
                            ->default(65)
                            ->label((new Label(__('1.5x images (65 by default):'), Label::INSIDE_TEXT_BEFORE))),
                    ]),
                    (new Para())->items([
                        (new Number('adaptiveimages_x20_jpg_quality', 0, 100, (int) $settings->x20_jpg_quality))
                            ->default(45)
                            ->label((new Label(__('2x images: (45 by default):'), Label::INSIDE_TEXT_BEFORE))),
                    ]),
                ]),
            ]),
            (new Text('h5', __('Advanced options'))),
            (new Para())->items([
                (new Checkbox('adaptiveimages_on_demand', $settings->on_demand))
                    ->value(1)
                    ->label((new Label(__('Deliver adaptive images on demand'), Label::INSIDE_TEXT_AFTER))),
            ]),
            (new Para())->class(['form-note', 'clear', 'warn'])->items([
                (new Text(null, __('Warning, this option needs an Apache RewriteRule or equivalent (see README.md)!'))),
            ]),
            (new Para())->items([
                (new Input('adaptiveimages_default_bkpts'))
                    ->size(40)
                    ->maxlength(40)
                    ->value($settings->default_bkpts)
                    ->default('160,320,480,640,960,1440')
                    ->label((new Label(__('Breakpoints (in pixel) for image generation (comma saparated values):'), Label::INSIDE_TEXT_BEFORE))),
            ]),
            (new Para())->class(['form-note', 'clear'])->items([
                (new Text(null, __('By default: 160,320,480,640,960,1440.'))),
            ]),
        ])
        ->render();

        return '';
    }

    /**
     * adminBeforeBlogSettingsUpdate behaviour callback: save plugin's settings
     */
    public static function adminBeforeBlogSettingsUpdate(): string
    {
        $settings = My::settings();

        $settings->put('enabled', !empty($_POST['adaptiveimages_enabled']), App::blogWorkspace()::NS_BOOL);
        $settings->put('max_width_1x', abs((int) $_POST['adaptiveimages_max_width_1x']), App::blogWorkspace()::NS_INT);
        $settings->put('min_width_1x', abs((int) $_POST['adaptiveimages_min_width_1x']), App::blogWorkspace()::NS_INT);
        $settings->put('lowsrc_jpg_bgcolor', self::adjustColor($_POST['adaptiveimages_lowsrc_jpg_bgcolor']), App::blogWorkspace()::NS_STRING);
        $settings->put('on_demand', !empty($_POST['adaptiveimages_on_demand']), App::blogWorkspace()::NS_BOOL);
        $settings->put('default_bkpts', self::adjustBreakpoints($_POST['adaptiveimages_default_bkpts']), App::blogWorkspace()::NS_STRING);
        $settings->put('lowsrc_jpg_quality', self::adjustJPGQuality($_POST['adaptiveimages_lowsrc_jpg_quality']), App::blogWorkspace()::NS_INT);
        $settings->put('x10_jpg_quality', self::adjustJPGQuality($_POST['adaptiveimages_x10_jpg_quality']), App::blogWorkspace()::NS_INT);
        $settings->put('x15_jpg_quality', self::adjustJPGQuality($_POST['adaptiveimages_x15_jpg_quality']), App::blogWorkspace()::NS_INT);
        $settings->put('x20_jpg_quality', self::adjustJPGQuality($_POST['adaptiveimages_x20_jpg_quality']), App::blogWorkspace()::NS_INT);

        return '';
    }

    /**
     * Check and return an hexadecimal web color as string or empty string on error
     *
     * @param  string $c color as input on form
     *
     * @return string    validated color or empty
     */
    private static function adjustColor(string $c): string
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
     *
     * @return string    validated and sorted list of breakpoints separated by comma or empty
     */
    public static function adjustBreakpoints(string $b): string
    {
        if ($b === '') {
            return '';
        }

        $a       = array_map('trim', explode(',', $b));
        $counter = count($a);
        for ($i = 0; $i < $counter; ++$i) {
            $a[$i] = abs((int) $a[$i]);
        }

        $a = array_unique($a);
        sort($a, SORT_NUMERIC);

        return implode(',', $a);
    }

    /**
     * Check and return JPEG compression quality (0 to 100)
     *
     * @param  string $q Compression quality
     *
     * @return integer
     */
    public static function adjustJPGQuality(string $q): int
    {
        if ($q === '') {
            return 0;
        }

        return min(100, abs((int) $q));
    }
}
