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
use Nursit\AdaptiveImages;

class Core extends AdaptiveImages
{
    /**
     * URL of public media folder
     */
    protected string $media_url = '';

    /**
     * Path of public media folder
     */
    protected string $media_path = '';

    /**
     * Constructor
     */
    protected function __construct()
    {
        $this->media_url  = rtrim((string) App::blog()->settings()->system->public_url, '/') . '/';
        $this->media_path = App::blog()->publicPath();
        $this->media_path = $this->realPath2relativePath($this->media_path);

        parent::__construct();
    }

    /**
     * Sets the destination directory.
     *
     * @param      string  $dir    The dir
     */
    public function setDestDirectory(string $dir): void
    {
        $this->destDirectory = $dir;
    }

    /**
     * Gets the destination directory.
     *
     * @return     string  The destination directory.
     */
    public function getDestDirectory(): string
    {
        return $this->destDirectory;
    }

    /**
     * Sets the On demand images flag
     *
     * @param      bool  $flag   The flag
     */
    public function setOnDemandImages(bool $flag): void
    {
        $this->onDemandImages = (int) $flag;
    }

    /**
     * Sets the minimum width 1x.
     *
     * @param      int   $value  The value
     */
    public function setMinWidth1x(int $value): void
    {
        $this->minWidth1x = $value;
    }

    /**
     * Sets the lowsrc jpg background color.
     *
     * @param      string  $value  The value
     */
    public function setLowsrcJpgBgColor(string $value): void
    {
        $this->lowsrcJpgBgColor = $value;
    }

    /**
     * Sets the default bkpts.
     *
     * @param      array<array-key, string>  $value  The value
     */
    public function setDefaultBkpts(array $value): void
    {
        $this->defaultBkpts = array_map(static fn ($v): int => (int) $v, $value);
    }

    /**
     * Translate full path to relative path if possible
     *
     * @param string $path
     * @return string
     */
    public function realPath2relativePath($path)
    {
        $dir = dirname((string) $_SERVER['SCRIPT_FILENAME']) . '/';
        if (str_starts_with($path, $dir)) {
            $path = substr($path, strlen($dir));
        }

        return $path;
    }

    /**
     * Convert URL path to file system path
     * By default just remove existing timestamp
     * Should be overriden depending of your URL mapping rules vs DOCUMENT_ROOT
     * can also remap Absolute URL of current website to filesystem path
     *
     * @param string $url
     * @return string
     */
    protected function URL2filepath($url)
    {
        $path = parent::URL2filepath($url);
        $base = $this->media_url;
        if (str_starts_with($path, $base)) {
            $path = $this->media_path . '/' . ltrim(substr($path, strlen($base)), '/');
            $path = str_replace('//', '/', $path);
        } elseif (str_starts_with($url, '/') && isset($_SERVER['DOCUMENT_ROOT'])) {
            $root = rtrim((string) $_SERVER['DOCUMENT_ROOT'], '/');
            $path = $root . $path;
        }

        return $path;
    }

    /**
     * Convert file system path to URL path
     * By default just add timestamp for webperf issue
     * Should be overriden depending of your URL mapping rules vs DOCUMENT_ROOT
     * can map URL on specific domain (domain sharding for Webperf purpose)
     *
     * @param string $filepath
     * @return string
     */
    protected function filepath2URL($filepath, $relative = false)
    {
        $url  = parent::filepath2URL($filepath);
        $base = $this->media_path;
        if (str_starts_with($url, $base)) {
            $url = $this->media_url . substr($url, strlen($base));
            $url = str_replace('//', '/', $url);
        } elseif (isset($_SERVER['DOCUMENT_ROOT'])) {
            $root = rtrim((string) $_SERVER['DOCUMENT_ROOT'], '/');
            if (str_starts_with($url, $root)) {
                $url = substr($url, strlen($root));
            }
        }

        return $url;
    }

    /**
     * This hook allows to personalize markup depending on source img style and class attributes
     * This do-noting method should be adapted to source markup generated by your CMS
     *
     * For instance : <img style="display:block;float:right"> could be adapted in
     * <span style="display:block;float:right"><span class="adapt-img-wrapper"><img class="adapt-img"></span></span>
     *
     * @param string $markup
     * @param string $originalClass
     * @param string $originalStyle
     */
    protected function imgMarkupHook(&$markup, $originalClass, $originalStyle): string
    {
        if ((!str_contains((string) $originalStyle, 'display:block;')) && (!str_contains((string) $originalStyle, 'display: block;'))) {
            // Inline image
            $wrapper = 'span';
            $style   = '';
        } else {
            // Block image
            $wrapper = 'div';
            $style   = ' text-align:center;';
        }

        $markup = sprintf('<%1$s class="%2$s" style="%3$s">%4$s</%1$s>', $wrapper, $originalClass, $originalStyle . $style, $markup);

        return $markup;
    }
}
