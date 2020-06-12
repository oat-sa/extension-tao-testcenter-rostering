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

use common_exception_MissingParameter;
use common_exception_RestApi;
use common_exception_ResourceNotFound;
use Exception;
use oat\taoTestCenterRostering\model\exception\TestCenterException;
use oat\taoTestCenterRostering\model\TestCenterService;

/**
 * Class RestTestCenterUsers
 * @package oat\taoTestCenterRoastering\controller
 * @author Aleh Hutnikau, <hutnikau@1pt.com>
 */
class RestTestCenterUsers extends AbstractRestController
{
    const PARAMETER_USER_URI = 'user';
    const PARAMETER_USER_ROLE = 'role';

    /**
     * @OA\Post(
     *     path="/taoTestCenterRostering/api/testCenterUsers",
     *     tags={"testCenter"},
     *     summary="Assign user to the test center",
     *     description="Assign user to the test center",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="testCenter",
     *                     type="string",
     *                     description="Test center id",
     *                 ),
     *                 @OA\Property(
     *                     property="user",
     *                     type="string",
     *                     description="User id",
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     type="string",
     *                     description="The role to which the user should be assigned",
     *                     enum={"http://www.tao.lu/Ontologies/TAOProctor.rdf#TestCenterAdministratorRole", "http://www.tao.lu/Ontologies/TAOProctor.rdf#ProctorRole", "http://www.tao.lu/Ontologies/generis.rdf#taoSyncManager"},
     *                 ),
     *                 required={"testCenter", "user", "role"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Assign user to the test center",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="success",
     *                     type="boolean",
     *                     description="`false` on failure, `true` on success",
     *                 ),
     *                 example={
     *                     "success": true
     *                 }
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="User is not allowed to be assigned to given role",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "success": false,
     *                     "errorCode": 400,
     *                     "errorMsg": "User with given role cannot be assigned to the test center.",
     *                     "version": "3.3.0-sprint85"
     *                 }
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Test center, user or role not found",
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
     *     )
     * )
     */
    public function post()
    {
        try {
            $testCenter = $this->getTCFromRequest();
            $user = $this->getUserFromRequest();
            $role = $this->getRoleFromRequest();

            $this->returnJson([
                'success' => $this->getService()->assignUser($testCenter, $user, $role)
            ]);
        } catch (TestCenterException $e) {
            return $this->returnFailure(new common_exception_RestApi($e->getMessage(), 400));
        } catch (Exception $e) {
            return $this->returnFailure($e);
        }
    }

    /**
     * @OA\Delete(
     *     path="/taoTestCenterRostering/api/testCenterUsers",
     *     tags={"testCenter"},
     *     summary="Remove user from the test center",
     *     description="Remove user from the test center",
     *     @OA\Parameter(
     *       name="testCenter",
     *       in="path",
     *       description="The test center id",
     *       required=true,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="user",
     *       in="path",
     *       description="User id",
     *       required=true,
     *       @OA\Schema(
     *           type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="role",
     *       in="path",
     *       description="Role id",
     *       required=true,
     *       @OA\Schema(
     *           enum={"http://www.tao.lu/Ontologies/TAOProctor.rdf#TestCenterAdministratorRole", "http://www.tao.lu/Ontologies/TAOProctor.rdf#ProctorRole", "http://www.tao.lu/Ontologies/generis.rdf#taoSyncManager"},
     *           type="string"
     *       )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Remove user from the test center",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="success",
     *                     type="boolean",
     *                     description="`false` on failure, `true` on success",
     *                 ),
     *                 example={
     *                     "success": true
     *                 }
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="User can not be unassigned from the test center with given role",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *                     "success": false,
     *                     "errorCode": 400,
     *                     "errorMsg": "User is not assigned to the test center with given role.",
     *                     "version": "3.3.0-sprint85"
     *                 }
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Test center, user or role not found",
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
     *     )
     * )
     */
    public function delete()
    {
        try {
            $testCenter = $this->getTCFromRequest();
            $user = $this->getUserFromRequest();
            $role = $this->getRoleFromRequest();

            $this->returnJson([
                'success' => $this->getService()->unassignUser($testCenter, $user, $role)
            ]);
        } catch (TestCenterException $e) {
            return $this->returnFailure(new common_exception_RestApi($e->getMessage(), 400));
        } catch (Exception $e) {
            return $this->returnFailure($e);
        }
    }

    /**
     * TODO:
     */
    public function get()
    {
        //todo: implement retrieving test center users
    }

    /**
     * @return \oat\oatbox\user\User
     * @throws common_exception_RestApi
     * @throws \common_exception_Error
     * @throws common_exception_ResourceNotFound
     */
    private function getUserFromRequest()
    {
        try {
            $userUri = $this->getParameterFromRequest(self::PARAMETER_USER_URI);
        } catch (common_exception_MissingParameter $e) {
            throw new common_exception_RestApi(__('Missed required parameter: `%s`', self::PARAMETER_USER_URI), 400);
        }
        $user = $this->getUserService()->getUserById($userUri);
        if (!$user || !$this->getResource($user->getIdentifier())->exists()) {
            throw new common_exception_ResourceNotFound(__('User `%s` does not exist.', $userUri), 404);
        }

        return $user;
    }

    /**
     * @return \core_kernel_classes_Resource
     * @throws common_exception_RestApi
     * @throws common_exception_ResourceNotFound
     */
    private function getRoleFromRequest()
    {
        $roleUri = '';
        try {
            $roleUri = $this->getParameterFromRequest(self::PARAMETER_USER_ROLE);

            return $this->getAndCheckResource($roleUri, 'http://www.tao.lu/Ontologies/generis.rdf#UserRole');
        } catch (common_exception_MissingParameter $e) {
            throw new common_exception_RestApi(__('Missed required parameter: `%s`', self::PARAMETER_USER_ROLE), 400);
        } catch (common_exception_ResourceNotFound $e) {
            throw new common_exception_ResourceNotFound(__('User Role `%s` does not exist.', $roleUri), 404);
        }
    }

    /**
     * @return TestCenterService
     */
    protected function getService()
    {
        return $this->getServiceLocator()->get(TestCenterService::SERVICE_ID);
    }

    /**
     * @return \tao_models_classes_UserService
     */
    protected function getUserService()
    {
        return $this->getServiceLocator()->get(\tao_models_classes_UserService::SERVICE_ID);
    }
}
