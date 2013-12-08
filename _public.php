<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of adaptiveImages, a plugin for Dotclear 2.
#
# Copyright (c) Franck Paul and contributors
# carnet.franck.paul@gmail.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_RC_PATH')) { return; }

require_once dirname(__FILE__).'/inc/AdaptiveImages.php';

class MyAdaptiveImages extends AdaptiveImages
{
	protected $media_url = '';
	protected $media_path = '';

	protected function __construct(){
		$this->media_url = $GLOBALS['core']->blog->settings->system->public_url;
		$this->media_path = $GLOBALS['core']->blog->public_path;
	}

	static public function getInstance() {
		return parent::getInstance();
	}

	protected function URL2filepath($url)
	{
		$path = parent::URL2filepath($url);
		$base = $this->media_url;
		if (strncmp($path,$base,strlen($base)) == 0) {
			$path = $this->media_path.substr($path,strlen($base));
		}
		return $path;
	}

	protected function filepath2URL($filepath)
	{
		$url = parent::filepath2URL($filepath);
		$base = $this->media_path;
		if (strncmp($url,$base,strlen($base)) == 0) {
			$url = $this->media_url.substr($url,strlen($base));
		}
		return $url;
	}
}

$core->addBehavior('urlHandlerServeDocument',array('dcAdaptiveImages','urlHandlerServeDocument'));

class dcAdaptiveImages
{
	public static function urlHandlerServeDocument($result)
	{
		global $core;

		// Do not transformation for feed and xlmrpc URLs
		$excluded = array('feed','xmlrpc');
		if (in_array($core->url->type,$excluded)) {
			return;
		}

		$core->blog->settings->addNameSpace('adaptiveimages');
		if ($core->blog->settings->adaptiveimages->enabled)
		{
			$max_width_1x = (integer) $core->blog->settings->adaptiveimages->max_width_1x;
			$AdaptiveImages = MyAdaptiveImages::getInstance();

			// Set properties
			$AdaptiveImages->destDirectory = $core->blog->public_path.'/.adapt-img/';
			$cache_dir = path::real($AdaptiveImages->destDirectory,false);
			if (!is_dir($cache_dir)) {
				files::makeDir($cache_dir);
			}
			if (!is_writable($cache_dir)) {
				throw new Exception('Adaptative Images cache directory is not writable.');
			}

			// Do transformation
			$html = $AdaptiveImages->adaptHTMLPage($result['content'],($max_width_1x ? $max_width_1x : null));
			$result['content'] = $html;
		}
	}
}
