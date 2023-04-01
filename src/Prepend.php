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
use dcNsProcess;
use Dotclear\App;

class Prepend extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_RC_PATH');

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        App::autoload()->addNamespace('Nursit', implode(DIRECTORY_SEPARATOR, [My::path(), 'lib']));

        dcCore::app()->url->register('adapt-img', 'adapt-img', '^adapt-img/(.+)?$', [FrontendUrl::class, 'onDemand']);

        return true;
    }
}
