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
 * Copyright (c) 2020 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 */

namespace oat\taoTestCenterRostering\model\textConverter;

use oat\oatbox\service\ServiceManager;
use oat\tao\model\textConverter\TextConverterService;

trait TestCentersTextConverterTrait
{
    /**
     * The service to convert text reference
     *
     * @var TextConverterService
     */
    protected $textConverterService;

    /**
     * Method to convert text key by textConverter value
     *
     * @param $key
     * @return string
     */
    protected function convert($key)
    {
        return $this->getTextConverterService()->get($key);
    }

    /**
     * Get the list of words to convert
     *
     * @return array
     */
    protected function getTextRegistry()
    {
        return $this->getTextConverterService()->getTextRegistry();
    }

    /**
     * Get the TextConverterService
     *
     * @return TextConverterService
     */
    protected function getTextConverterService()
    {
        if (! $this->textConverterService) {
            $this->textConverterService = ServiceManager::getServiceManager()->get(TestCentersTextConverter::SERVICE_ID);
        }
        return $this->textConverterService;
    }
}