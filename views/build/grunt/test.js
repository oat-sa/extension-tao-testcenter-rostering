module.exports = function(grunt) {
    'use strict';

    var watch       = grunt.config('watch') || {};
    var qunit       = grunt.config('qunit') || {};
    var testUrl     = 'http://127.0.0.1:' + grunt.option('testPort');
    var root        = grunt.option('root');

    var testRunners = root + '/taoTestCenterRostering/views/js/test/**/test.html';
    var testFiles = root + '/taoTestCenterRostering/views/js/test/**/test.js';

    //extract unit tests
    var extractTests = function extractTests(){
        return grunt.file.expand([testRunners]).map(function(path){
            return path.replace(root, testUrl);
        });
    };

    /**
     * tests to run
     */
    qunit.taotestcenter = {
        options : {
            console : true,
            urls : extractTests()
        }
    };


    watch.taotestcenter = {
        files : [testRunners, testFiles],
        tasks : ['qunit:taotestcenter'],
        options : {
            debounceDelay : 10000
        }
    };

    grunt.config('qunit', qunit);
    grunt.config('watch', watch);

    // bundle task
    grunt.registerTask('taotestcentertest', ['qunit:taotestcenter']);
};
