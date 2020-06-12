module.exports = function(grunt) {
    'use strict';

    var sass    = grunt.config('sass') || {};
    var watch   = grunt.config('watch') || {};
    var notify  = grunt.config('notify') || {};
    var root    = grunt.option('root') + '/taoTestCenterRostering/views/';

    sass.taotestcenterrostering = { };
    sass.taotestcenterrostering.files = { };
    sass.taotestcenterrostering.files[root + 'css/testcenter.css'] = root + 'scss/testcenter.scss';
    sass.taotestcenterrostering.files[root + 'css/eligibilityEditor.css'] = root + 'scss/eligibilityEditor.scss';
    sass.taotestcenterrostering.files[root + 'css/eligibilityTable.css']  = root + 'scss/eligibilityTable.scss';

    watch.taotestcenterrosteringsass = {
        files : [root + 'scss/*.scss'],
        tasks : ['sass:taotestcenterrostering', 'notify:taotestcenterrosteringsass'],
        options : {
            debounceDelay : 1000
        }
    };

    notify.taotestcenterrosteringsass = {
        options: {
            title: 'Grunt SASS',
            message: 'SASS files compiled to CSS'
        }
    };

    grunt.config('sass', sass);
    grunt.config('watch', watch);
    grunt.config('notify', notify);

    //register an alias for main build
    grunt.registerTask('taotestcenterrosteringsass', ['sass:taotestcenterrostering']);
};
