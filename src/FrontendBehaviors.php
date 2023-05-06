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
use Dotclear\Helper\File\Files;
use Dotclear\Helper\File\Path;
use Exception;

class FrontendBehaviors
{
    /**
     * Behaviour called before serving HTML/XML document
     *
     * @param ArrayObject $result
     */
    public static function urlHandlerServeDocument($result)
    {
        // Do not transform for feed and xlmrpc URLs
        $excluded = ['feed', 'xmlrpc'];
        if (in_array(dcCore::app()->url->type, $excluded)) {
            return;
        }

        /**
         * @var        dcNamespace
         */
        $settings = dcCore::app()->blog->settings->get(My::id());

        if ($settings->enabled) {
            /**
             * @var        AdaptiveImages
             */
            $ai = Core::getInstance();

            // Set properties
            $ai->destDirectory  = $ai->realPath2relativePath(dcCore::app()->blog->public_path . '/.adapt-img/');
            $ai->onDemandImages = (bool) $settings->on_demand;

            // Set options
            if ($min_width_1x = (int) $settings->min_width_1x) {
                $ai->minWidth1x = $min_width_1x;
            }
            if (($lowsrc_jpg_bgcolor = $settings->lowsrc_jpg_bgcolor) != '') {
                $ai->lowsrcJpgBgColor = $lowsrc_jpg_bgcolor;
            }
            if (($default_bkpts = $settings->default_bkpts) != '') {
                $ai->defaultBkpts = explode(',', $default_bkpts);
            }

            // Check cache directory
            $cache_dir = Path::real($ai->destDirectory, false);
            if (!is_dir($cache_dir)) {
                Files::makeDir($cache_dir);
            }
            if (!is_writable($cache_dir)) {
                throw new Exception('Adaptative Images cache directory is not writable.');
            }

            // Do transformation
            $max_width_1x      = (int) $settings->max_width_1x;
            $html              = $ai->adaptHTMLPage($result['content'], ($max_width_1x ?: null));
            $result['content'] = $html;
        }
    }
}
