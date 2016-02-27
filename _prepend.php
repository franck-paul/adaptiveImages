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

$core->url->register('adapt-img','adapt-img','^adapt-img/(.+)?$',array('urlAdaptiveImages','onDemand'));

$__autoload['dcMaintenanceAdaptiveImages'] = dirname(__FILE__).'/_tasks.php';