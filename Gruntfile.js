module.exports = function(grunt) {
    grunt.initConfig({
      uglify: {
        my_target: {
          files: {
            'amd/build/vmchat.min.js' : ['amd/src/vmchat.js']
          }
        }
      }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify'); // load the given tasks
    grunt.registerTask('default', ['uglify:my_target']); // Default grunt tasks maps to grunt
};