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
 * Copyright (c) 2015 (original work) Open Assessment Technologies SA ;
 */
/**
 * @author Sam <sam@taotesting.com>
 */
define([
    'lodash',
    'jquery',
    'i18n',
    'helpers',
    'users',
    'ui/feedback',
    'ui/component',
    'taoTestCenterRostering/helper/textConverter',
    'tpl!taoTestCenterRostering/component/proctorForm/form'
], function(_, $, __, helpers, users, feedback, component, textConverter, formTpl){
    'use strict';

    var _ns = '.proctor-form';

    //service urls:
    var proctorFormUrl = helpers._url('createProctorForm', 'ProctorManager', 'taoTestCetaoTestCenterRosteringnter');
    var proctorLoginCheckUrl = helpers._url('checkLogin', 'ProctorManager', 'taoTestCenterRostering');

    //initialize legacy components
    helpers.init();

    /**
     * Render the form from the server provided data
     *
     * @param {JQuery} $container
     * @param {Object} formData
     */
    function renderFormFromData($container, formData){
        $container.html(formTpl({
            form : formData.form
        }));
        users.checkLogin(formData.loginId, proctorLoginCheckUrl);
    }

    /**
     * Create a proctor creation form
     *
     * @param {type} config
     * @param {JQuery} config.renderTo - the jQuery container it should be rendered to
     * @param {Object} config.testCenterList - the test center list component
     * @returns {proctorForm}
     */
    return function proctorFormFactory(config){

        return component()
            .on('destroy', function(){
                this.getElement().off(_ns).empty();
            })
            .on('render', function(){

                var self = this;
                var $element = this.getElement();

                textConverter().then(function(labels) {
                    $.get(proctorFormUrl, function(formData){

                        renderFormFromData($element, formData);

                        $element.on('submit' + _ns, 'form', function(e){

                            var $form = $(this);
                            var fields = $form.serializeArray();
                            var list = self.config.testCenterList;
                            var data = {
                                testCenters : list && list.getSelection()
                            };
                            _.each(fields, function(field){
                                data[field.name] = field.value;
                            });

                            $.post(proctorFormUrl, data, function(res){
                                if(res.created){
                                    feedback().success(labels.get('Proctor created'));
                                    self.destroy();
                                }else{
                                    renderFormFromData($element, res);
                                }
                            });

                            e.preventDefault();
                        }).on('click', 'button.btn-diasble', function() {
                            self.destroy();
                        });
                    });
                }).catch(function(err) {
                   console.log(err);
                });

            })
            .init(config);
    };
});
