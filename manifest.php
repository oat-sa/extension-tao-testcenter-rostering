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
 * Copyright (c) 2015 (original work) Open Assessment Technologies SA;
 *
 *
 */

use oat\taoProctoring\controller\MonitorProctorAdministrator;
use oat\taoTestCenterRostering\controller\Import;
use oat\taoTestCenterRostering\scripts\install\RegisterTestCenterEntryPoint;
use oat\taoTestCenterRostering\controller\TestCenterManager;
use oat\taoTestCenterRostering\controller\TestCenter;
use oat\taoTestCenterRostering\scripts\install\TestCenterOverrideServices;
use oat\taoTestCenterRostering\controller\ProctorManager;
use oat\taoTestCenterRostering\controller\Diagnostic;
use oat\taoTestCenterRostering\controller\RestEligibilities;
use oat\taoTestCenterRostering\controller\RestEligibility;
use oat\taoTestCenterRostering\controller\RestTestCenter;
use oat\taoTestCenterRostering\scripts\install\RegisterTestCenterEvents;
use oat\taoProctoring\model\ProctorService;
use oat\taoTestCenterRostering\model\TestCenterService;
use oat\taoTestCenterRostering\scripts\install\OverrideBreadcrumbsServices;
use oat\taoTestCenterRostering\scripts\install\RegisterClientLibConfig;
use oat\taoTestCenterRostering\controller\RestTestCenterUsers;

return array(
    'name' => 'taoTestcenterRostering',
    'label' => 'Test Center',
    'description' => 'Proctoring via test-centers',
    'license' => 'GPL-2.0',
    'version' => '9.0.3',
    'author' => 'Open Assessment Technologies SA',
    'requires' => array(
        'taoProctoring'  => '>=12.7.0',
        'taoDelivery'    => '>=12.5.0',
        'generis'        => '>=12.5.0',
        'tao'            => '>=35.5.1',
        'taoTestTaker'   => '>=4.0.0',
        'taoDeliveryRdf' => '>=6.0.0',
    ),
    'managementRole' => TestCenterService::ROLE_TESTCENTER_MANAGER,
    'acl' => array(
        array('grant', TestCenterService::ROLE_TESTCENTER_MANAGER, TestCenterManager::class),
        array('grant', TestCenterService::ROLE_TESTCENTER_ADMINISTRATOR, ProctorManager::class),
        array('grant', TestCenterService::ROLE_TESTCENTER_MANAGER, Import::class),
        array('grant', TestCenterService::ROLE_TESTCENTER_ADMINISTRATOR, Import::class),
        array('grant', ProctorService::ROLE_PROCTOR, TestCenter::class),
        array('grant', ProctorService::ROLE_PROCTOR, Diagnostic::class),
        array('grant', TestCenterService::ROLE_TESTCENTER_ADMINISTRATOR, MonitorProctorAdministrator::class),
        array('grant', TestCenterService::ROLE_TESTCENTER_MANAGER, RestEligibility::class),
        array('grant', TestCenterService::ROLE_TESTCENTER_MANAGER, RestEligibilities::class),
        array('grant', TestCenterService::ROLE_TESTCENTER_MANAGER, RestTestCenter::class),
        array('grant', TestCenterService::ROLE_TESTCENTER_MANAGER, RestTestCenterUsers::class),
        //array('grant', TaoRoles::ANONYMOUS, DiagnosticChecker::class),
    ),
    'install' => array(
        'php' => array(
            RegisterTestCenterEntryPoint::class,
            TestCenterOverrideServices::class,
            RegisterTestCenterEvents::class,
            OverrideBreadcrumbsServices::class,
            RegisterClientLibConfig::class,
        ),
        'rdf' => array(
            __DIR__.'/scripts/install/ontology/taotestcenter.rdf',
            __DIR__.'/scripts/install/ontology/eligibility.rdf',
        )
    ),
//    'uninstall' => array(),
    'update' => 'oat\\taoTestCenterRostering\\scripts\\update\\Updater',
    'routes' => array(
        '/taoTestCenterRostering/api' => ['class' => \oat\taoTestCenterRostering\model\routing\ApiRoute::class],
        '/taoTestCenterRostering' => 'oat\\taoTestCenterRostering\\controller',
    ),
    'constants' => array(
        # views directory
        'DIR_VIEWS' => dirname(__FILE__) . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR,
        #BASE URL (usually the domain root)
        'BASE_URL' => ROOT_URL . 'taoTestCenterRostering/',
    ),
    'extra' => array(
        'structures' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'structures.xml',
    )
);
