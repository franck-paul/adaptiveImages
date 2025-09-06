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
use Dotclear\Core\Url;
use Exception;

class FrontendUrl extends Url
{
    /**
     * URL handler for "on demand" adaptive images
     */
    public static function onDemand(?string $args): void
    {
        /**
         * @var        Core
         */
        $AdaptiveImages = Core::getInstance();

        $AdaptiveImages->setDestDirectory($AdaptiveImages->realPath2relativePath(App::blog()->publicPath() . DIRECTORY_SEPARATOR . My::CACHE . DIRECTORY_SEPARATOR));

        try {
            $AdaptiveImages->deliverBkptImage($args);
        } catch (Exception) {
            self::p404();
        }

        exit;
    }
}
