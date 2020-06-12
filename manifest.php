<?php

use oat\taoTestCenterRostering\scripts\update\Updater;

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
 * Copyright (c) 2020 (original work) Open Assessment Technologies SA;
 *
 */

use oat\taoTestCenterRostering\controller\Import;
use oat\taoTestCenterRostering\model\routing\ApiRoute;
use oat\taoTestCenterRostering\controller\TestCenterManager;
use oat\taoTestCenterRostering\scripts\install\TestCenterOverrideServices;
use oat\taoTestCenterRostering\controller\RestEligibilities;
use oat\taoTestCenterRostering\controller\RestEligibility;
use oat\taoTestCenterRostering\controller\RestTestCenter;
use oat\taoTestCenterRostering\scripts\install\RegisterTestCenterEvents;
use oat\taoTestCenterRostering\model\TestCenterService;
use oat\taoTestCenterRostering\scripts\install\RegisterClientLibConfig;
use oat\taoTestCenterRostering\controller\RestTestCenterUsers;

return [
    'name' => 'taoTestcenterRostering',
    'label' => 'Test Center',
    'description' => 'Test-centers',
    'license' => 'GPL-2.0',
    'version' => '1.0.0',
    'author' => 'Open Assessment Technologies SA',
    'requires' => [
        'taoDelivery'    => '>=12.5.0',
        'generis'        => '>=12.5.0',
        'tao'            => '>=35.5.1',
        'taoTestTaker'   => '>=4.0.0',
        'taoDeliveryRdf' => '>=6.0.0',
        'taoClientDiagnostic' => '>=7.5.0',
    ],
    'managementRole' => TestCenterService::ROLE_TESTCENTER_MANAGER,
    'acl' => [
        ['grant', TestCenterService::ROLE_TESTCENTER_MANAGER, TestCenterManager::class],
        ['grant', TestCenterService::ROLE_TESTCENTER_MANAGER, Import::class],
        ['grant', TestCenterService::ROLE_TESTCENTER_ADMINISTRATOR, Import::class],
        ['grant', TestCenterService::ROLE_TESTCENTER_MANAGER, RestEligibility::class],
        ['grant', TestCenterService::ROLE_TESTCENTER_MANAGER, RestEligibilities::class],
        ['grant', TestCenterService::ROLE_TESTCENTER_MANAGER, RestTestCenter::class],
        ['grant', TestCenterService::ROLE_TESTCENTER_MANAGER, RestTestCenterUsers::class],
    ],
    'install' => [
        'php' => [
            TestCenterOverrideServices::class,
            RegisterTestCenterEvents::class,
            RegisterClientLibConfig::class,
        ],
        'rdf' => [
            __DIR__ . '/scripts/install/ontology/taotestcenterrostering.rdf',
            __DIR__ . '/scripts/install/ontology/eligibility.rdf',
        ]
    ],
//    'uninstall' => array(),
    'update' => Updater::class,
    'routes' => [
        '/taoTestCenterRostering/api' => ['class' => ApiRoute::class],
        '/taoTestCenterRostering' => 'oat\\taoTestCenterRostering\\controller',
    ],
    'constants' => [
        # views directory
        'DIR_VIEWS' => __DIR__ . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR,
        #BASE URL (usually the domain root)
        'BASE_URL' => ROOT_URL . 'taoTestCenterRostering/',
    ],
    'extra' => [
        'structures' => __DIR__ . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'structures.xml',
    ]
];
