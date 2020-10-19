<?php
/**
 * Admin Controller
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
 * Class controls VuFind administration.
 *
 * @category VuFind
 * @package  Controller
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org Main Site
 */
class AdminController extends AbstractAdmin
{

    /**
     * Display disabled message.
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function disabledAction()
    {
        return $this->createViewModel();
    }

    /**
     * Admin home.
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function homeAction()
    {
        $config = $this->getConfig();
        $xml = false;
        if (isset($config->Index->url)) {
            $response = $this->serviceLocator->get(\VuFindHttp\HttpService::class)
                ->get($config->Index->url . '/admin/cores?wt=xml');
            $xml = $response->isSuccess() ? $response->getBody() : false;
        }
        $view = $this->createViewModel();
        $view->xml = $xml ? simplexml_load_string($xml) : false;

        $i = 0;
        $data = array();
        $cores = (is_object($view->xml) ? $view->xml->xpath('/response/lst[@name="status"]/lst') : []);
        foreach ($cores as $core) {
        	$coreName = (string)$core['name'];
        	$coreLabel = $coreLabels[$coreName] ?? ucwords($coreName) . " Index";
        	$data[$i]["heading"] = $coreLabel;
            // If a core is offline or hasn't been initialized yet, it won't have valid
            // uptime data; use this knowledge to display an appropriate message.
        	$uptimeData = $core->xpath('//lst[@name="' . $coreName . '"]/long[@name="uptime"]');
        	$uptime = (string)array_pop($uptimeData);
        	$data[$i]["report"] = "";
        	if (!is_numeric($uptime)) {
        		$data[$i]["report"] = "System Unavailable";
        	} else {
        		$j = 0;

        		$j = 1 + $j;
                $recordCount = $core->xpath('//lst[@name="' . $coreName . '"]/lst/int[@name="numDocs"]');
        		$data[$i]["items"][$j]["text"] = "Record Count";
        		$data[$i]["items"][$j]["value"] = (string)array_pop($recordCount);

        		$j = 1 + $j;
                $startTime = $core->xpath('//lst[@name="' . $coreName . '"]/date[@name="startTime"]');
        		$data[$i]["items"][$j]["text"] = "Start Time";
        		$data[$i]["items"][$j]["value"] = strftime("%b %d, %Y %l:%M:%S%p", strtotime((string)array_pop($startTime)));

        		$j = 1 + $j;
                $lastModified = $core->xpath('//lst[@name="' . $coreName . '"]/lst/date[@name="lastModified"]');
        		$data[$i]["items"][$j]["text"] = "Last Modified";
        		$data[$i]["items"][$j]["value"] = strftime("%b %d, %Y %l:%M:%S%p", strtotime((string)array_pop($lastModified)));

        		$j = 1 + $j;
        		$data[$i]["items"][$j]["text"] = "Uptime";
        		$data[$i]["items"][$j]["value"] = $uptime;

        		$data[$i]["count"] = $j;
        	}
        	$i++;
        }

        $view->data = $data;

        return $view;
    }
}
