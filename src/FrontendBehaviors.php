<?php

/**
 * @brief adaptiveImages, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul contact@open-time.net
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
        if (App::url()->isType(['feed', 'xmlrpc'])) {
            return '';
        }

        $settings = My::settings();

        if ($settings->getBool('enabled', false)) {
            /**
             * @var        Core
             */
            $ai = Core::getInstance();

            // Set properties
            $ai->setDestDirectory($ai->realPath2relativePath(App::blog()->publicPath() . DIRECTORY_SEPARATOR . My::CACHE . DIRECTORY_SEPARATOR));
            $ai->setOnDemandImages($settings->getBool('on_demand', false));

            // Set options
            if (($min_width_1x = $settings->getInt('min_width_1x', false)) !== 0) {
                $ai->setMinWidth1x($min_width_1x);
            }

            if (($lowsrc_jpg_bgcolor = $settings->getStr('lowsrc_jpg_bgcolor', false)) !== '') {
                $ai->setLowsrcJpgBgColor($lowsrc_jpg_bgcolor);
            }

            if (($default_bkpts = $settings->getStr('default_bkpts', false)) !== '') {
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
            $max_width_1x      = $settings->getInt('max_width_1x', false);
            $content           = is_string($content = $result['content']) ? $content : '';
            $html              = $ai->adaptHTMLPage($content, ($max_width_1x ?: null));
            $result['content'] = $html;
        }

        return '';
    }
}
