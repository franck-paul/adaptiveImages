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

// dead but useful code, in order to have translations
__('adaptiveImages').__('Implements the 3-layers technique for Adaptive Images generation (by Nursit)');

$core->addBehavior('adminBlogPreferencesForm',array('adaptiveImagesBehaviors','adminBlogPreferencesForm'));
$core->addBehavior('adminBeforeBlogSettingsUpdate',array('adaptiveImagesBehaviors','adminBeforeBlogSettingsUpdate'));

class adaptiveImagesBehaviors
{
	public static function adminBlogPreferencesForm($core,$settings)
	{
		$settings->addNameSpace('adaptiveimages');
		echo
		'<div class="fieldset"><h4>'.__('Adaptive Images').'</h4>'.
		'<p><label for"adaptiveimages_enabled" class="classic">'.
		form::checkbox('adaptiveimages_enabled','1',$settings->adaptiveimages->enabled).
		__('Enable Adaptive Images').'</label></p>'.
		'<p><label for="adaptiveimages_max_width_1x" class="classic">'.__('Maximum display width for images 1x:').'</label> '.
		form::field('adaptiveimages_max_width_1x',4,4,(integer) $settings->adaptiveimages->max_width_1x).
		'</p>'.
		'<p class="clear form-note">'.__('The default maximum display width for image 1x is 640 pixels.').'</p>'.
		'</div>';
	}

	public static function adminBeforeBlogSettingsUpdate($settings)
	{
		$settings->addNameSpace('adaptiveimages');
		$settings->adaptiveimages->put('enabled',!empty($_POST['adaptiveimages_enabled']),'boolean');
		$settings->adaptiveimages->put('max_width_1x',abs((integer) $_POST['adaptiveimages_max_width_1x']),'integer');
	}
}
