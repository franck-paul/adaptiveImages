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

$core->addBehavior('urlHandlerServeDocument',array('dcAdaptativeImages','urlHandlerServeDocument'));

class dcAdaptiveImages
{
	public static function urlHandlerServeDocument($result)
	{
		global $core;

		$core->blog->settings->addNameSpace('adaptiveimages');
		if ($core->blog->settings->adaptiveimages->enabled)
		{
			$max_width_1x = (integer) $core->blog->settings->adaptiveimages->max_width_1x;

			$AdaptiveImage = AdaptiveImages::getInstance();

			// Set properties
			$AdaptiveImage->destDirectory = $core->blog->public_path.'/.adapt-img';
			$cache_dir = path::real($AdaptiveImage->destDirectory,false);
			if (!is_dir($cache_dir)) {
				files::makeDir($cache_dir);
			}
			if (!is_writable($cache_dir)) {
				throw new Exception('Adaptative Images cache directory is not writable.');
			}

			// Do transformation
			$html = $AdaptiveImage->adaptHTMLPage($result['content'],($max_width_1x ? $max_width_1x : null));
			$result['content'] = $html;
		}
	}
}
