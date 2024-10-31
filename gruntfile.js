module.exports = function (grunt) {

  grunt.initConfig({

    version: grunt.file.readJSON('package.json').version,

      karma: {
          options: {
              frameworks: ['jasmine', 'commonjs'],
              files: [
                  'admin/scripts/dev/vendor/jquery-1.11.3.js',
                  'admin/scripts/dev/vendor/angular.js',
                  'admin/scripts/dev/vendor/angular-route.js',
                  'admin/scripts/dev/vendor/angular-ui.min.js',
                  'admin/scripts/dev/vendor/bootstrap-colorpicker-module.min.js',

                  'admin/test/angular-mock.js',
                  'admin/test/index.js',
                  'admin/test/wp.js',

                  'front/scripts/dev/faster-slider.js',
                  'admin/scripts/dev/general/*.js',
                  'admin/scripts/dev/general/*.js',
                  'admin/scripts/dev/index/*.js',
                  'admin/scripts/dev/media-streamer/*.js',
                  'admin/scripts/dev/slider/*.js',
                  'admin/scripts/dev/slides/*.js',

                  'admin/test/unit/**/*.js'
              ],
              preprocessors: {
                  'admin/test/unit/**/*.js': ['commonjs']
              },
              singleRun: true
          },
          browsers: {
              options: {
                  browsers: ['Chrome'],
                  reporters: ['progress']
              }
          },
          coverage: {
              options: {
                  browsers: ['PhantomJS'],
                  reporters: ['progress', 'coverage'],
                  preprocessors: {
                      'admin/scripts/dev/**/*.js': ['commonjs', 'coverage'],
                      'test/unit/*.js': ['commonjs']
                  },
                  coverageReporter: {
                      reporters: [
                          { type: 'lcov', subdir: '.' },
                          { type: 'text-summary', subdir: '.' }
                      ]
                  }
              }
          }
      }

  });

  // load npm tasks
  grunt.loadNpmTasks('grunt-eslint');
  grunt.loadNpmTasks('grunt-karma');

  // load custom tasks
  //require('./build/grunt-tasks/build')(grunt)
  //require('./build/grunt-tasks/casper')(grunt)
  //require('./build/grunt-tasks/codecov')(grunt)
  //require('./build/grunt-tasks/release')(grunt)
  //require('./build/grunt-tasks/open')(grunt)

  // register composite tasks
  grunt.registerTask('unit-test', ['karma:browsers']);

  // CI
  //if (process.env.CI_PULL_REQUEST) {
  //  grunt.registerTask('ci', ['eslint', 'cover', 'build', 'casper'])
  //} else {
  //  grunt.registerTask('ci', ['eslint', 'cover', 'codecov', 'build', 'casper', 'sauce'])
  //}

};
