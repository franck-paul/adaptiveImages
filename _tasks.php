<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of adaptiveImages, a plugin for Dotclear 2.
#
# Copyright (c) Franck Paul and contributors
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_ADMIN')) { return; }

/**
 * Maintenance class
 */
class dcMaintenanceAdaptiveImages extends dcMaintenanceTask
{
	protected $group = 'purge';

	protected function init()
	{
		$this->task 		= __('Empty adaptive images cache directory');
		$this->success 		= __('Adaptive images cache directory emptied.');
		$this->error 		= __('Failed to empty adaptive images cache directory.');

		$this->description	= __("It may be useful to empty this cache when modifying breakpoints or quality of JPEG compression. Notice : with some hosters, the adaptive images cache cannot be emptied with this plugin.");
	}

	public function execute()
	{
		$cache_dir = $this->core->blog->public_path.'/.adapt-img/';
		if (is_dir($cache_dir)) {
			files::deltree($cache_dir);
		}

		return true;
	}
}
