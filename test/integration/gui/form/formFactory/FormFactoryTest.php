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
 * Copyright (c) 2018 (original work) Open Assessment Technologies SA;
 *
 */

namespace oat\taoTestCenterRostering\test\integration\gui\form\formFactory;

use core_kernel_classes_Resource;
use oat\taoTestCenterRostering\model\gui\form\formFactory\FormFactory;
use oat\taoTestCenterRostering\model\textConverter\TestCentersTextConverter;
use tao_helpers_form_GenerisTreeForm;
use oat\generis\test\TestCase;

class FormFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $factory = $this->getFactory();

        $factory->setOption('property', 'property_string');
        $factory->setOption('title', 'title_string');
        $factory->setOption('isReversed', false);
        $form = $factory->__invoke(
            $this->getMockBuilder(core_kernel_classes_Resource::class)->disableOriginalConstructor()->getMock()
        );

        $this->assertInstanceOf(tao_helpers_form_GenerisTreeForm::class, $form);
    }

    /**
     * @return FormFactory
     */
    protected function getFactory()
    {
        $factory = $this->getMockBuilder(FormFactory::class)
            ->setMethods(['getTextConverterService', 'buildGenerisForm'])
            ->disableOriginalConstructor()->getMock();

        $factory
            ->method('getTextConverterService')
            ->willReturn(
                $this->mockTextConverter()
            );

        $factory
            ->method('buildGenerisForm')
            ->willReturn(
                $this->mockForm()
            );

        return $factory;
    }

    protected function mockForm()
    {
        $form = $this->getMockBuilder(tao_helpers_form_GenerisTreeForm::class)
            ->setMethods(['render'])->disableOriginalConstructor()->getMock();

        $form->method('render')->willReturn('rendered form');

        return $form;
    }

    protected function mockTextConverter()
    {
        return $this->getMockBuilder(TestCentersTextConverter::class)->disableOriginalConstructor()->getMock();
    }
}
