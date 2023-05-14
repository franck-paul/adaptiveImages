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
declare(strict_types=1);

namespace Dotclear\Plugin\adaptiveImages\Cleaner;

use dcCore;
use Dotclear\Helper\File\Files;
use Dotclear\Plugin\adaptiveImages\My;
use Dotclear\Plugin\Uninstaller\{
    ActionDescriptor,
    CleanerDescriptor,
    CleanerParent,
    ValueDescriptor
};

/**
 * Cleaner for Adaptive images cache directory.
 */
class Caches extends CleanerParent
{
    public function __construct()
    {
        parent::__construct(new CleanerDescriptor(
            id:   'ai_caches',
            name: __('Adaptive images cache'),
            desc: __('Adaptive images cache directory'),
            actions: [
                // delete a $ns folder and thier files.
                new ActionDescriptor(
                    id:      'delete',
                    select:  __('delete Adaptive images cache directory'),
                    query:   __('delete Adaptive images cache directory'),
                    success: __('Adaptive images cache directory deleted'),
                    error:   __('Failed to delete Adaptive images cache directory'),
                    default: true
                ),
            ]
        ));
    }

    public function distributed(): array
    {
        return [];
    }

    public function values(): array
    {
        $res = [];
        /* Seems not necessary:
        $path = implode(DIRECTORY_SEPARATOR, [dcCore::app()->blog->public_path, My::CACHE]);
        if (is_dir($path)) {
            $res[] = new ValueDescriptor(
                ns:    My::CACHE,
                count: 1
            );
        }
        */

        return $res;
    }

    public function execute(string $action, string $ns): bool
    {
        $cache_dir = implode(DIRECTORY_SEPARATOR, [dcCore::app()->blog->public_path, My::CACHE]);
        if ($action == 'delete') {
            if (is_dir($cache_dir)) {
                Files::deltree($cache_dir);
            }

            return true;
        }

        return false;
    }
}
