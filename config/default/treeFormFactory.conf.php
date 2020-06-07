<?php

use oat\taoTestCenterRostering\model\gui\form\formFactory\SubTestCenterFormFactory;
use oat\taoTestCenterRostering\model\gui\form\TreeFormFactory;
use oat\taoTestCenterRostering\model\gui\ProctorUserFormFactory;
use oat\taoTestCenterRostering\model\gui\TestcenterAdministratorUserFormFactory;
use oat\taoTestCenterRostering\model\ProctorManagementService;
use oat\taoTestCenterRostering\model\TestCenterService;

return new TreeFormFactory(array(
    'formFactories' => array(
        new TestcenterAdministratorUserFormFactory(array(
            'property' => ProctorManagementService::PROPERTY_ADMINISTRATOR_URI,
            'title' => 'Assign administrators',
            'isReversed' => true,
        )),
        new ProctorUserFormFactory(array(
            'property' => ProctorManagementService::PROPERTY_ASSIGNED_PROCTOR_URI,
            'title' => 'Assign proctors',
            'isReversed' => true,
        )),
        new SubTestCenterFormFactory(array(
            'property' => TestCenterService::PROPERTY_CHILDREN_URI,
            'title' => 'Define sub-centers',
            'isReversed' => false,
        )),
    )
));
