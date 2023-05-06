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

use dcCore;
use dcNamespace;
use dcNsProcess;
use Exception;

class Install extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_CONTEXT_ADMIN')
            && My::phpCompliant()
            && dcCore::app()->newVersion(My::id(), dcCore::app()->plugins->moduleInfo(My::id(), 'version'));

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        try {
            $old_version = dcCore::app()->getVersion(My::id());
            if (version_compare((string) $old_version, '1.0', '<')) {
                // Rename settings namespace
                if (dcCore::app()->blog->settings->exists('adaptiveimages')) {
                    dcCore::app()->blog->settings->renNamespace('adaptiveimages', My::id());
                }

                // Change settings names (remove adaptiveimages_ prefix in them)
                $rename = function (string $name, dcNamespace $settings): void {
                    if ($settings->settingExists('adaptiveimages_' . $name, true)) {
                        $settings->rename('adaptiveimages_' . $name, $name);
                    }
                };

                $settings = dcCore::app()->blog->settings->get(My::id());

                $rename('enabled', $settings);
                $rename('max_width_1x', $settings);
                $rename('min_width_1x', $settings);
                $rename('lowsrc_jpg_bgcolor', $settings);
                $rename('on_demand', $settings);
                $rename('default_bkpts', $settings);
                $rename('lowsrc_jpg_quality', $settings);
                $rename('x10_jpg_quality', $settings);
                $rename('x15_jpg_quality', $settings);
                $rename('x20_jpg_quality', $settings);
            }

            return true;
        } catch (Exception $e) {
            dcCore::app()->error->add($e->getMessage());
        }

        return true;
    }
}
