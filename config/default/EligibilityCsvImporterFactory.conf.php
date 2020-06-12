<?php

/**
 * Default config header created during install
 */

use oat\generis\model\user\UserRdf;
use oat\tao\model\import\service\ArrayImportValueMapper;
use oat\tao\model\import\service\RdsValidatorValueMapper;
use oat\taoDeliveryRdf\model\DeliveryAssemblyService;
use oat\taoTestCenterRostering\model\EligibilityService;
use oat\taoTestCenterRostering\model\import\RdsEligibilityImportService;
use oat\taoTestCenterRostering\model\TestCenterService;
use oat\taoTestCenterRostering\model\import\EligibilityCsvImporterFactory;

return new EligibilityCsvImporterFactory(
    [
    'default-schema' => [
        'mandatory' => [
            'test center' => [
                EligibilityService::PROPERTY_TESTCENTER_URI => new RdsValidatorValueMapper([
                    RdsValidatorValueMapper::OPTION_CLASS  => TestCenterService::CLASS_URI,
                ])
            ],
            'delivery' => [
                EligibilityService::PROPERTY_DELIVERY_URI => new RdsValidatorValueMapper([
                    RdsValidatorValueMapper::OPTION_CLASS => DeliveryAssemblyService::CLASS_URI,
                ])
            ],
            'test takers' => [
                EligibilityService::PROPERTY_TESTTAKER_URI => new ArrayImportValueMapper([
                    ArrayImportValueMapper::OPTION_DELIMITER => '|',
                    ArrayImportValueMapper::OPTION_VALUE_MAPPER => new RdsValidatorValueMapper([
                        RdsValidatorValueMapper::OPTION_CLASS => UserRdf::CLASS_URI,
                        RdsValidatorValueMapper::OPTION_PROPERTY  => UserRdf::PROPERTY_LOGIN
                    ])
                ])
            ],
        ],
        'optional' => [
            'is proctored' => EligibilityService::PROPERTY_BYPASSPROCTOR_URI
        ]
    ],
    'mappers' => [
        'default' => [
            'importer' => new RdsEligibilityImportService()
        ]
    ]
    ]
);
