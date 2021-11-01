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

$this->registerModule(
    'adaptiveImages',                                                               // Name
    'Implements the 3-layers technique for Adaptive Images generation (by Nursit)', // Description
    'Franck Paul and contributors',                                                 // Author
    '0.9',                                                                          // Version
    [
        'requires'    => [['core', '2.16']],                               // Dependencies
        'permissions' => 'admin',                                          // Permissions
        'type'        => 'plugin',                                         // Type
        'settings'    => [                                                 // Settings
            'blog' => '#params.adaptiveimages_settings'
        ],

        'details'    => 'https://open-time.net/?q=adaptivesImages',       // Details URL
        'support'    => 'https://github.com/franck-paul/adaptivesImages', // Support URL
        'repository' => 'https://raw.githubusercontent.com/franck-paul/adaptivesImages/master/dcstore.xml'
    ]
);
