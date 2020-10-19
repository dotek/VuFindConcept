<?php
/**
 * Admin Social Statistics Controller
 *
 * PHP version 7
 *
 * Copyright (C) Villanova University 2010.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category VuFind
 * @package  Controller
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org Main Site
 */
namespace VuFindAdmin\Controller;

/**
 * Class controls VuFind social statistical data.
 *
 * @category VuFind
 * @package  Controller
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org Main Site
 */
class SocialstatsController extends AbstractAdmin
{
    /**
     * Social statistics reporting
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function homeAction()
    {
        $view = $this->createViewModel();
        $view->setTemplate('admin/socialstats/home');
        $view->comments = $this->getTable('comments')->getStatistics();
        $view->favorites = $this->getTable('userresource')->getStatistics();
        $view->tags = $this->getTable('resourcetags')->getStatistics();

        $i = 0;
        $j = 0;
        $data = array();
        $data[$i]["heading"] = "Comments";
        $data[$i]["headers"][$j]["text"] = "Total Users";
        $data[$i]["values"][$j]["value"] = $view->comments['users'];
        $j = $j + 1;
        $data[$i]["headers"][$j]["text"] = "Total Resources";
        $data[$i]["values"][$j]["value"] = $view->comments['resources'];
        $j = $j + 1;
        $data[$i]["headers"][$j]["text"] = "Total Comments";
        $data[$i]["values"][$j]["value"] = $view->comments['total'];
        $i = $i + 1;
        $j = 0;
        $data[$i]["heading"] = "Favorites";
        $data[$i]["headers"][$j]["text"] = "Total Users";
        $data[$i]["values"][$j]["value"] = $view->favorites['users'];
        $j = $j + 1;
        $data[$i]["headers"][$j]["text"] = "Total Resources";
        $data[$i]["values"][$j]["value"] = $view->favorites['resources'];
        $j = $j + 1;
        $data[$i]["headers"][$j]["text"] = "Total list";
        $data[$i]["values"][$j]["value"] = $view->favorites['total'];
        $j = $j + 1;
        $data[$i]["headers"][$j]["text"] = "Total Saved Items";
        $data[$i]["values"][$j]["value"] = $view->favorites['total'];
        $i = $i + 1;
        $j = 0;
        $data[$i]["heading"] = "Tags";
        $data[$i]["headers"][$j]["text"] = "Total Users";
        $data[$i]["values"][$j]["value"] = $view->tags['users'];
        $j = $j + 1;
        $data[$i]["headers"][$j]["text"] = "Total Resources";
        $data[$i]["values"][$j]["value"] = $view->tags['resources'];
        $j = $j + 1;
        $data[$i]["headers"][$j]["text"] = "Total Tags";
        $data[$i]["values"][$j]["value"] = $view->tags['total'];

        $view->data = $data;

        return $view;
    }
}
