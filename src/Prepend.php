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

use Autoloader;
use dcCore;
use Dotclear\Core\Process;

class Prepend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::PREPEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        Autoloader::me()->addNamespace('Nursit', implode(DIRECTORY_SEPARATOR, [My::path(), 'lib']));

        dcCore::app()->url->register('adapt-img', 'adapt-img', '^adapt-img/(.+)?$', FrontendUrl::onDemand(...));

        if (dcCore::app()->plugins->moduleExists('Uninstaller')) {
            // Add cleaners to Uninstaller
            dcCore::app()->addBehavior('UninstallerCleanersConstruct', function (\Dotclear\Plugin\Uninstaller\CleanersStack $cleaners): void {
                $cleaners
                    ->set(new Cleaner\Caches())
                ;
            });
        }

        return true;
    }
}
