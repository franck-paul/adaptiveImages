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
$this->registerModule(
    'adaptiveImages',
    'Implements the 3-layers technique for Adaptive Images generation (by Nursit)',
    'Franck Paul and contributors',
    '1.3',
    [
        'requires'    => [['core', '2.26']],
        'permissions' => dcCore::app()->auth->makePermissions([
            dcAuth::PERMISSION_ADMIN,
        ]),
        'type'     => 'plugin',
        'priority' => 1001,
        'settings' => [
            'blog' => '#params.adaptiveimages_settings',
        ],

        'details'    => 'https://open-time.net/?q=adaptivesImages',
        'support'    => 'https://github.com/franck-paul/adaptivesImages',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/adaptivesImages/master/dcstore.xml',
    ]
);
