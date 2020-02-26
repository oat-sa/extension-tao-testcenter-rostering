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
 * @author Jean-Sébastien Conan <jean-sebastien.conan@vesperiagroup.com>
 */
define([
    'jquery',
    'i18n',
    'util/url',
    'layout/loading-bar',
    'ui/listbox',
    'ui/button',
    'taoTestCenterRostering/helper/textConverter'
], function ($, __, urlHelper, loadingBar, listBox, buttonFactory, textConverter) {
    'use strict';

    /**
     * The CSS scope
     * @type {String}
     */
    var cssScope = '.testcenters-index';

    // the page is always loading data when starting
    loadingBar.start();

    /**
     * Controls the taoTestCenter index page
     *
     * @type {Object}
     */
    return {
        /**
         * Entry point of the page
         */
        start : function start() {

            textConverter().then(function(labels) {

                var $container = $(cssScope);
                var admin = $container.data('administrator');
                var boxes = $container.data('list');
                var list = listBox({
                    title: __("My Test sites"),
                    textEmpty: __("No test site available"),
                    textNumber: __("Available"),
                    textLoading: __("Loading"),
                    renderTo: $container.find('.content'),
                    replace: true
                });
                var serviceUrl = urlHelper.route('index', 'TestCenter', 'taoTestCenterRostering');
                var adminUrl = urlHelper.route('index', 'ProctorManager', 'taoTestCenterRostering');

                // update the index from a JSON array
                var update = function(boxes) {
                    list.update(boxes);
                    loadingBar.stop();
                };

                // refresh the index
                var refresh = function() {
                    loadingBar.start();
                    list.setLoading(true);

                    $.ajax({
                        url: serviceUrl,
                        cache: false,
                        dataType : 'json',
                        type: 'GET'
                    }).done(function(response) {
                        boxes = response && response.list;
                        update(boxes);
                    });
                };

                if (!boxes) {
                    refresh();
                } else {
                    update(boxes);
                }

                if(admin){
                    //add test center admin link
                    buttonFactory({
                        id: 'manage-proctors',
                        type: 'info',
                        icon: 'users',
                        cls: 'manage-button',
                        label: labels.get('Manage Proctors'),
                        renderTo: $container.find('.header')
                    }).on('click', function () {
                        window.location.href = adminUrl;
                    });
                }
            }).catch(function (err) {
                console.log(err);
            });
        }
    };
});
