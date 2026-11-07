<?php

/**
 * @brief adaptiveImages, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul contact@open-time.net
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

if (isset($this) && is_object($this) && method_exists($this, 'registerModule') && isset($this->id) && is_string($this->id)) {
    $this->registerModule(
        'adaptiveImages',
        'Implements the 3-layers technique for Adaptive Images generation (by Nursit)',
        'Franck Paul and contributors',
        '6.1',
        [
            'date'        => '2026-11-07T16:39:02+0100',
            'requires'    => [['core', '2.39']],
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
}
