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
if (!defined('DC_RC_PATH')) {
    return;
}

dcCore::app()->url->register('adapt-img', 'adapt-img', '^adapt-img/(.+)?$', ['urlAdaptiveImages', 'onDemand']);

Clearbricks::lib()->autoload(['dcMaintenanceAdaptiveImages' => __DIR__ . '/_tasks.php']);
