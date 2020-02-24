/*
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
 * Copyright (c) 2016 (original work) Open Assessment Technologies SA ;
 *
 */

/**
 * The eligibility table component.
 *
 * Manages a list of eligibilties.
 *
 * As this is mainly a refactoring, I've kept the data as they were and
 * the format used is inconsistent across the different calls...
 *
 * @author Bertrand Chevrier <bertrand@taotesting.com>
 */
define([
    'jquery',
    'lodash',
    'i18n',
    'ui/component',
    'helpers',
    'tpl!taoTestCenterRostering/component/eligibilityTable/status',
    'tpl!taoTestCenterRostering/component/eligibilityTable/actions',
    'css!taoTestCenterCss/eligibilityTable.css',
    'ui/datatable'
], function($, _, __, component, helpers, statusTpl, actionsTpl){
    'use strict';


    /**
     * Creates the eligibilityTable component
     *
     * @param {String} testCenterId - the test center URI
     * @param {Object|boolean} permissions - permissions to work with if DAC enabled
     * @returns {eligibilityTable} the component
     * @throws {TypeError} without a test center
     */
    var eligibilityTableFactory = function eligibilityTableFactory(testCenterId, permissions){
        //excessive variable for component - needs to be there, for actions to work normally (and for unit testing)
        var tableComponent;

        var eligibilities = [];
        var tools = [];
        var allowedToWrite = false;

        if(_.isEmpty(testCenterId)){
            throw new TypeError('The eligibility provider needs to be initialized with a test center');
        }

        if (!permissions || (permissions && permissions.WRITE)) {
            allowedToWrite = true;

            tools = [
                {
                    id : 'add',
                    icon : 'add',
                    title : __('Add'),
                    label : __('Add'),
                    action : function(){

                        /**
                         * Add action
                         * @event eligibilityTable#add
                         * @param {Object} eligibilities
                         */
                        tableComponent.trigger('add', eligibilities);
                    }
                },{
                    id : 'import',
                    icon : 'import',
                    title : __('Import'),
                    label : __('Import'),
                    action : function(){
                        /**
                         * Add action
                         * @event eligibilityTable#add
                         * @param {Object} eligibilities
                         */
                        tableComponent.trigger('import', eligibilities);
                    }
                }
            ];
        }

        /**
         * The component.
         *
         * @typedef eligibilityTable
         * @see ui/component
         * @throws eligibilityTable#loading while loading something
         * @throws eligibilityTable#loaded when loading is done
         * @throws eligibilityTable#render once mounted to the DOM
         * @throws eligibilityTable#add action
         * @throws eligibilityTable#edit action
         * @throws eligibilityTable#remove action
         * @throws eligibilityTable#shield action
         * @throws eligibilityTable#unshield action
         */
        tableComponent = component({}, {
                //config can be changed
                dataUrl : helpers._url('getEligibilities', 'TestCenterManager', 'taoTestCenter', { uri : testCenterId })
            })
            .on('render', function(){
                var self = this;

                //set up the ui/datatable
                this.$component
                    .on('query.datatable', function(){
                        self.trigger('loading');
                    })
                    .on('load.datatable', function(e){
                        self.trigger('loaded');
                    })
                    .on('beforeload.datatable', function(e, dataSet){
                        if(dataSet && dataSet.data){
                            eligibilities = dataSet.data;
                        }
                    })
                    .datatable({
                        url : this.config.dataUrl,
                        status : {
                            empty:     __('No Eligible Delivery yet'),
                            available: __('Eligible Deliveries'),
                            loading:   __('Loading')
                        },
                        tools : tools,
                        model : [{
                            id : 'status',
                            label : '',
                            transform: function(value, row){
                                return statusTpl(row);
                            }
                        }, {
                            id : 'deliveryLabel',
                            label : __('Delivery'),
                            transform : function(value, row){
                                return row.delivery.label;
                            }
                        }, {
                            id : 'testTakersCount',
                            label : __('Eligible Test Takers'),
                            transform : function(value, row){
                                return row.testTakers && row.testTakers.length ? row.testTakers.length : 0;
                            }
                        }, {
                            id: 'actions',
                            label: __('Actions'),
                            transform: function(value, row){
                                row['allowedToWrite'] = allowedToWrite
                                return actionsTpl(row);
                            }
                        }],
                        selectable : false
                    }).on('click', '.actions button', function(e){
                        e.preventDefault();

                        var $button = $(this);
                        var itemId = $button.parents('[data-item-identifier]').data('item-identifier');
                        var action = $button.data('action');

                        self.trigger(action, itemId, eligibilities);
                    });

            })
            .on('reload', function(){
                if(this.$component){
                    this.$component.datatable('refresh');
                }
            });

        return tableComponent;
    };

    return eligibilityTableFactory;
});
