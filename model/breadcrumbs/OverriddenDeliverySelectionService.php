<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2017 (original work) Open Assessment Technologies SA ;
 *
 */

namespace oat\taoTestCenter\model\breadcrumbs;

use oat\taoProctoring\model\breadcrumbs\DeliverySelectionService;

/**
 * Provides breadcrumbs for the DeliverySelection controller.
 * @author Jean-Sébastien Conan <jean-sebastien@taotesting.com>
 */
class OverriddenDeliverySelectionService extends DeliverySelectionService
{
    /**
     * Builds breadcrumbs for a particular route.
     * @param string $route - The route URL
     * @param array $parsedRoute - The parsed URL (@see parse_url), augmented with extension, controller and action
     * @return array|null - The breadcrumb related to the route, or `null` if none. Must contains:
     * - id: the route id
     * - url: the route url
     * - label: the label displayed for the breadcrumb
     * - entries: a list of related links, using the same format as above
     */
    public function breadcrumbs($route, $parsedRoute)
    {
        if (isset($parsedRoute['action'])) {
            switch($parsedRoute['action']) {
                case 'index':
                    return $this->breadcrumbsIndex($route, $parsedRoute);
            }
        }
        return null;
    }

    /**
     * Gets the breadcrumbs for the index page
     * @param string $route
     * @param array $parsedRoute
     * @return array
     */
    protected function breadcrumbsIndex($route, $parsedRoute)
    {
        $breadCrumbs = [];

        $urlContext = [];
        if (isset($parsedRoute['params'])) {
            if (isset($parsedRoute['params']['context'])) {
                $urlContext['context'] = $parsedRoute['params']['context'];
            }
        }

        // Adding the testcenter link.
        $breadCrumbs[] = [
            'id' => 'testCenterSelection',
            'url' => _url('index', 'TestCenter', 'taoTestCenter', ['link-type' => 'direct']),
            'label' => __('Test centers'),
        ];

        // Adding the current testcenter.
        if (!empty($parsedRoute['params']['context'])) {
            $testCenter = new \core_kernel_classes_Class($parsedRoute['params']['context']);
            $breadCrumbs[] = [
                'id' => 'testCenter',
                'url' => _url('testCenter', 'TestCenter', 'taoTestCenter', ['link-type' => 'direct', 'testCenter' => $testCenter->getUri()]),
                'label' => $testCenter->getLabel(),
            ];
        }

        // Adding the original breadcrumb.
        $breadCrumbs[] = parent::breadcrumbsIndex($route, $parsedRoute);

        return $breadCrumbs;
    }
}
