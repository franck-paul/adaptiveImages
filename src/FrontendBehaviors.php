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

use ArrayObject;
use Dotclear\App;
use Dotclear\Helper\File\Files;
use Dotclear\Helper\File\Path;
use Exception;

class FrontendBehaviors
{
    /**
     * Behaviour called before serving HTML/XML document
     *
     * @param ArrayObject<string, mixed> $result
     */
    public static function urlHandlerServeDocument(ArrayObject $result): string
    {
        // Do not transform for feed and xlmrpc URLs
        $excluded = ['feed', 'xmlrpc'];
        if (in_array(App::url()->type, $excluded)) {
            return '';
        }

        $settings = My::settings();

        if ($settings->enabled) {
            /**
             * @var        Core
             */
            $ai = Core::getInstance();

            // Set properties
            $ai->setDestDirectory($ai->realPath2relativePath(App::blog()->publicPath() . DIRECTORY_SEPARATOR . My::CACHE . DIRECTORY_SEPARATOR));
            $ai->setOnDemandImages((bool) $settings->on_demand);

            // Set options
            if ($min_width_1x = (int) $settings->min_width_1x) {
                $ai->setMinWidth1x($min_width_1x);
            }
            if (($lowsrc_jpg_bgcolor = $settings->lowsrc_jpg_bgcolor) != '') {
                $ai->setLowsrcJpgBgColor($lowsrc_jpg_bgcolor);
            }
            if (($default_bkpts = $settings->default_bkpts) != '') {
                $ai->setDefaultBkpts(explode(',', $default_bkpts));
            }

            // Check cache directory
            $cache_dir = Path::real($ai->getDestDirectory(), false);
            if ($cache_dir !== false && !is_dir($cache_dir)) {
                Files::makeDir($cache_dir);
            }
            if ($cache_dir === false || !is_writable($cache_dir)) {
                throw new Exception('Adaptative Images cache directory is not writable.');
            }

            // Do transformation
            $max_width_1x      = (int) $settings->max_width_1x;
            $html              = $ai->adaptHTMLPage($result['content'], ($max_width_1x ?: null));
            $result['content'] = $html;
        }

        return '';
    }
}
