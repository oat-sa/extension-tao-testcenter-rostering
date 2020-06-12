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
 * Copyright (c) 2018 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 */

namespace oat\taoTestCenterRostering\controller;

use common_exception_Error;
use common_exception_RestApi;
use core_kernel_classes_Property;
use Exception;
use oat\generis\model\OntologyRdfs;
use oat\taoTestCenterRostering\model\TestCenterService;

/**
 * Class RestTestCenter
 * @package oat\taoTestCenterRoastering\controller
 * @author Aleh Hutnikau, <hutnikau@1pt.com>
 */
class RestTestCenter extends AbstractRestController
{

    const PARAMETER_TEST_CENTER_CLASS = 'class';
    const PARAMETER_TEST_CENTER_LABEL = 'label';

    /**
     * @var array
     */
    protected $parametersMap = [
        self::PARAMETER_TEST_CENTER_LABEL => OntologyRdfs::RDFS_LABEL
    ];

    /**
     * @OA\Put(
     *     path="/taoOffline/api/testCenter",
     *     tags={"testCenter"},
     *     summary="Update test center",
     *     description="Update center label",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="testCenter",
     *                     type="string",
     *                     description="Test center uri",
     *                 ),
     *                 @OA\Property(
     *                     property="label",
     *                     type="string",
     *                     description="Test center label",
     *                 ),
     *                 required={"testCenter"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Test center URI",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="success",
     *                     type="boolean",
     *                     description="`false` on failure, `true` on success",
     *                 ),
     *                 @OA\Property(
     *                     property="uri",
     *                     type="string",
     *                     description="Updated test center URI",
     *                 ),
     *                 example={
     *                     "success": true,
     *                     "uri": "http://sample/first.rdf#i1536680377163171"
     *                 }
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invalid class uri",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "success": false,
     *                     "errorCode": 404,
     *                     "errorMsg": "Test Center `http://sample/first.rdf#i1536680377163171` does not exist.",
     *                     "version": "3.3.0-sprint106"
     *                 }
     *             )
     *         ),
     *     )
     * )
     */
    public function put()
    {
        try {
            $testCenter = $this->getTCFromRequest();

            $data = $this->validateRequestParameters(
                array_merge(
                    $this->getParametersFromRequestData($this->getRequestPutData()),
                    ['uri' => $testCenter->getUri()]
                )
            );

            foreach ($data as $propertyUri => $value) {
                $testCenter->editPropertyValues(new core_kernel_classes_Property($propertyUri), $value);
            }

            $this->returnJson([
                'success' => true,
                'uri' => $testCenter->getUri()
            ]);
        } catch (Exception $e) {
            $this->returnFailure($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/taoTestCenterRostering/api/testCenter",
     *     tags={"testCenter"},
     *     summary="Create new test center",
     *     description="Create new test center",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="class-uri",
     *                     type="string",
     *                     description="Class uri to import item. If not specified root class will be used.",
     *                 ),
     *                 @OA\Property(
     *                     property="class-label",
     *                     type="string",
     *                     description="Label of class to import item. If not specified root class will be used.
     * If label is not unique first match will be used.",
     *                 ),
     *                 @OA\Property(
     *                     property="label",
     *                     type="string",
     *                     description="Test center label",
     *                 ),
     *                 required={"label"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Created test center URI",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="success",
     *                     type="boolean",
     *                     description="`false` on failure, `true` on success",
     *                 ),
     *                 @OA\Property(
     *                     property="uri",
     *                     type="string",
     *                     description="Created test center URI",
     *                 ),
     *                 example={
     *                     "success": true,
     *                     "uri": "http://sample/first.rdf#i1536680377163171"
     *                 }
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid class uri",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "success": false,
     *                     "errorCode": 0,
     *                     "errorMsg": "Class does not exist. Please use valid class-uri or class-label",
     *                     "version": "3.3.0-sprint85"
     *                 }
     *             )
     *         ),
     *     )
     * )
     */
    public function post()
    {
        try {
            $properties = $this->validateRequestParameters(
                $this->getParametersFromRequestData($this->getRequestPostData(), true)
            );

            $resource = $this->getClassFromRequest(
                $this->getTestCenterService()->getRootClass()
            )->createInstanceWithProperties($properties);

            $this->returnJson([
                'success' => true,
                'uri' => $resource->getUri(),
            ]);
        } catch (Exception $e) {
            $this->returnFailure($e);
        }
    }

    /**
     * @OA\Get(
     *     path="/taoTestCenterRostering/api/testCenter",
     *     tags={"testCenter"},
     *     summary="Get test center data",
     *     description="Get test center data",
     *     @OA\Parameter(
     *         name="testCenter",
     *         in="query",
     *         description="testCenter Uri (Url encoded)",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Test center data",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="label",
     *                     type="string",
     *                     description="Test center label",
     *                 ),
     *                 @OA\Property(
     *                     property="class",
     *                     type="string",
     *                     description="Test center class URI",
     *                 ),
     *                 example={
     *                     "label": "Test Center ABC",
     *                     "class": "http://sample/first.rdf#i15367360596713165"
     *                 }
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invalid test center Uri",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "success": false,
     *                     "errorCode": 404,
     *                     "errorMsg": "Test Center `http://sample/first.rdf#i15367360596713165` does not exist.",
     *                     "version": "3.3.0-sprint85"
     *                 }
     *             )
     *         ),
     *     ),
     * )
     */
    public function get()
    {
        try {
            $tc = $this->getTCFromRequest();
            $class = current($tc->getTypes());
            $this->returnJson([
                'label' => $tc->getLabel(),
                'class' => $class->getUri(),
            ]);
        } catch (\Exception $e) {
            return $this->returnFailure($e);
        }
    }

    /**
     * @param array $data
     * @param bool $isRequired
     * @return array
     * @throws common_exception_RestApi
     */
    protected function getParametersFromRequestData(array $data, $isRequired = false)
    {
        $values = [];

        foreach (array_keys($this->parametersMap) as $name) {
            if (array_key_exists($name, $data)) {
                $values[$name] = $data[$name];
            } elseif ($isRequired) {
                throw new common_exception_RestApi(__('Missed required parameter: `%s`', $name), 400);
            }
        }
        return $values;
    }

    /**
     * @param array $values
     * @return array
     * @throws common_exception_RestApi|common_exception_Error
     */
    protected function validateRequestParameters(array $values)
    {
        $propertiesValues = [];
        foreach ($values as $name => $value) {
            if (array_key_exists($name, $this->parametersMap)) {
                if (empty($value)) {
                    throw new common_exception_RestApi(__('Missed required parameter: `%s`', $name), 400);
                }
                $propertiesValues[$this->parametersMap[$name]] = $value;
            }
        }
        return $propertiesValues;
    }

    /**
     * @return TestCenterService
     */
    protected function getTestCenterService()
    {
        return $this->getServiceLocator()->get(TestCenterService::SERVICE_ID);
    }
}
