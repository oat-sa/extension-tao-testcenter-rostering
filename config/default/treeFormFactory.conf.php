<?php

use oat\taoTestCenterRostering\model\gui\form\formFactory\SubTestCenterFormFactory;
use oat\taoTestCenterRostering\model\gui\form\TreeFormFactory;
use oat\taoTestCenterRostering\model\TestCenterService;

return new TreeFormFactory(array(
    'formFactories' => array(
        new SubTestCenterFormFactory(array(
            'property' => TestCenterService::PROPERTY_CHILDREN_URI,
            'title' => 'Define sub-centers',
            'isReversed' => false,
        )),
    )
));
