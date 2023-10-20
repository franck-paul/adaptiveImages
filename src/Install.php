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
use Dotclear\Core\Process;
use Dotclear\Interface\Core\BlogWorkspaceInterface;
use Exception;

class Install extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::INSTALL));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        try {
            $old_version = App::version()->getVersion(My::id());
            if (version_compare((string) $old_version, '1.0', '<')) {
                // Rename settings namespace
                if (App::blog()->settings()->exists('adaptiveimages')) {
                    App::blog()->settings()->delWorkspace(My::id());
                    App::blog()->settings()->renWorkspace('adaptiveimages', My::id());
                }

                // Change settings names (remove adaptiveimages_ prefix in them)
                $rename = function (string $name, BlogWorkspaceInterface $settings): void {
                    if ($settings->settingExists('adaptiveimages_' . $name, true)) {
                        $settings->rename('adaptiveimages_' . $name, $name);
                    }
                };
                $settings = My::settings();
                foreach (['enabled', 'max_width_1x', 'min_width_1x', 'lowsrc_jpg_bgcolor', 'on_demand', 'default_bkpts', 'lowsrc_jpg_quality', 'x10_jpg_quality', 'x15_jpg_quality', 'x20_jpg_quality'] as $name) {
                    $rename($name, $settings);
                }
            }
        } catch (Exception $e) {
            App::error()->add($e->getMessage());
        }

        return true;
    }
}
