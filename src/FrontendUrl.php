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
use dcUrlHandlers;
use Exception;

class FrontendUrl extends dcUrlHandlers
{
    /**
     * URL handler for "on demand" adaptive images
     *
     * @param string $args
     */
    public static function onDemand($args)
    {
        /**
         * @var        Core
         */
        $AdaptiveImages = Core::getInstance();

        /* @phpstan-ignore-next-line */
        $AdaptiveImages->destDirectory = $AdaptiveImages->realPath2relativePath(dcCore::app()->blog->public_path . DIRECTORY_SEPARATOR . My::CACHE . DIRECTORY_SEPARATOR);

        try {
            $AdaptiveImages->deliverBkptImage($args);
        } catch (Exception $e) {
            self::p404();
        }
        exit;
    }
}
