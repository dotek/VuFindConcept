<?php
/**
 * Admin Tag Controller
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
 * Class controls distribution of tags and resource tags.
 *
 * @category VuFind
 * @package  Controller
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org Main Site
 */
class OverdriveController extends AbstractAdmin
{
    /**
     * Params
     *
     * @var array
     */
    protected $params;

    /**
     * Get the url parameters
     *
     * @param string $param A key to check the url params for
     *
     * @return string
     */
    protected function getParam($param)
    {
        return (isset($this->params[$param]))
            ? $this->params[$param]
            : $this->params()->fromPost(
                $param,
                $this->params()->fromQuery($param, null)
            );
    }

    /**
     * Tag Details
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function homeAction()
    {
        $connector  = $this->serviceLocator
            ->get('VuFind\DigitalContent\OverdriveConnector');

        $view = $this->createViewModel();
        $view->setTemplate('admin/overdrive/home');
        $view->productsKey = $connector->getCollectionToken();
        $view->overdriveConfig = $connector->getConfig();
        $view->hasAccess = $connector->getAccess();


        $i = 0;
        $j = 0;
        $data = array();
        $data[$i]["heading"] = "";
        $data[$i]["items"][$j]["text"] = "Current API Token";
        $data[$i]["items"][$j]["value"] = "%%token%%";
        $i = $i + 1;
        $j = 0;
        $data[$i]["heading"] = "Overdrive Library Information";
        $data[$i]["items"][$j]["text"] = "Product Key";
        $data[$i]["items"][$j]["value"] = $view->productsKey;
        $i = $i + 1;
        $j = 0;
        $data[$i]["heading"] = "Overdrive Configuration";
        $data[$i]["items"][$j]["text"] = "Mode";
        $data[$i]["items"][$j]["value"] = ($view->overdriveConfig->productionMode ? "Production" : "Integration");
        $j = $j + 1;
        $data[$i]["items"][$j]["text"] = "Client Key";
        $data[$i]["items"][$j]["value"] = $view->overdriveConfig->clientKey;
        $j = $j + 1;
        $data[$i]["items"][$j]["text"] = "Library ID";
        $data[$i]["items"][$j]["value"] = $view->overdriveConfig->libraryID;
        $j = $j + 1;
        $data[$i]["items"][$j]["text"] = "Website ID";
        $data[$i]["items"][$j]["value"] = $view->overdriveConfig->websiteID;
        $j = $j + 1;
        $data[$i]["items"][$j]["text"] = "ILS Name";
        $data[$i]["items"][$j]["value"] = $view->overdriveConfig->ILSname;
        $j = $j + 1;
        $data[$i]["items"][$j]["text"] = "Records in Marc Format";
        $data[$i]["items"][$j]["value"] = ($view->overdriveConfig->isMarc ? "Yes" : "No");
        $i = $i + 1;
        $j = 0;
        $data[$i]["heading"] = "User Access";
        $data[$i]["items"][$j]["text"] = "Current User Has Access?";
        $data[$i]["items"][$j]["value"] = ($view->hasAccess->status ? "Yes" : "No");
        if ($this->hasAccess->status) {
          $j = $j + 1;
          $data[$i]["items"][$j]["text"] = "Current User API Token?";
          $data[$i]["items"][$j]["value"] = $view->hasAccess->status;
        }
        $j = $j + 1;
        $data[$i]["items"][$j]["text"] = "Test User";
        $data[$i]["items"][$j]["report"] = "Feature not ready yet";

        $view->data = $data;

        return $view;
    }
}
