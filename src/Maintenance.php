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

namespace Dotclear\Plugin\adaptiveImages;

use dcCore;
use Dotclear\Helper\File\Files;
use Dotclear\Plugin\maintenance\MaintenanceTask;

class Maintenance extends MaintenanceTask
{
    protected $group = 'purge';

    protected function init(): void
    {
        $this->task    = __('Empty adaptive images cache directory');
        $this->success = __('Adaptive images cache directory emptied.');
        $this->error   = __('Failed to empty adaptive images cache directory.');

        $this->description = __('It may be useful to empty this cache when modifying breakpoints or quality of JPEG compression. Notice : with some hosters, the adaptive images cache cannot be emptied with this plugin.');
    }

    public function execute()
    {
        $cache_dir = dcCore::app()->blog->public_path . DIRECTORY_SEPARATOR . My::CACHE . DIRECTORY_SEPARATOR;
        if (is_dir($cache_dir)) {
            Files::deltree($cache_dir);
        }

        return true;
    }
}
