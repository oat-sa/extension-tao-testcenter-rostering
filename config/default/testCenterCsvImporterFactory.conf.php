<?php
/**
 * Default config header created during install
 */

use oat\generis\model\OntologyRdfs;
use oat\generis\model\user\UserRdf;
use oat\tao\model\import\service\ArrayImportValueMapper;
use oat\tao\model\import\service\RdsValidatorValueMapper;
use oat\taoTestCenterRostering\model\import\RdsTestCenterImportService;
use oat\taoTestCenterRostering\model\import\TestCenterCsvImporterFactory;
use oat\taoTestCenterRostering\model\ProctorManagementService;
use oat\taoTestCenterRostering\model\TestCenterService;

return new TestCenterCsvImporterFactory([
    'mappers' => [
        'default' => array(
            'importer' => new RdsTestCenterImportService()
        ),
    ],
    'default-schema' => [
        'mandatory' => [
            'label' => 'http://www.w3.org/2000/01/rdf-schema#label',
        ],
        'optional' => [
            'administrators' =>[
                ProctorManagementService::PROPERTY_ADMINISTRATOR_URI => new ArrayImportValueMapper([
                    'delimiter' => '|',
                    'valueMapper' => new RdsValidatorValueMapper([
                        'class' => UserRdf::CLASS_URI,
                        'property' => UserRdf::PROPERTY_LOGIN
                    ])
                ])
            ],
            'proctors' =>[
                ProctorManagementService::PROPERTY_ASSIGNED_PROCTOR_URI => new ArrayImportValueMapper([
                    'delimiter' => '|',
                    'valueMapper' => new RdsValidatorValueMapper([
                        'class' => UserRdf::CLASS_URI,
                        'property' => UserRdf::PROPERTY_LOGIN
                    ])
                ])
            ],
            'sub centers' => [
                TestCenterService::PROPERTY_CHILDREN_URI => new ArrayImportValueMapper([
                    'delimiter' => '|',
                    'valueMapper' => new RdsValidatorValueMapper([
                        'class' => TestCenterService::CLASS_URI,
                        'property' => OntologyRdfs::RDFS_LABEL
                    ])
                ])
            ]
        ]
    ]
]);
