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
    '5.1',
    [
        'date'        => '2025-09-07T18:40:20+0200',
        'requires'    => [['core', '2.36']],
        'permissions' => 'My',
        'type'        => 'plugin',
        'priority'    => 1010,  // Must be higher than dcLegacyEditor/dcCKEditor priority (ie 1000)
        'settings'    => [
            'blog' => '#params.adaptiveimages_settings',
        ],

        'details'    => 'https://open-time.net/?q=adaptivesImages',
        'support'    => 'https://github.com/franck-paul/adaptivesImages',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/adaptivesImages/main/dcstore.xml',
        'license'    => 'gpl2',
    ]
);
