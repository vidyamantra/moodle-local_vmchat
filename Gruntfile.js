module.exports = function(grunt) {
    grunt.initConfig({
      uglify: {
        my_target: {
          files: {
          	'bundle/io/build/iolib.js' : ['bundle/io/src/iolib.js'],
            'build/footer.js' : ['src/footer.js'],
            'build/chatboxManager.js' : ['src/chatboxManager.js'],
            'build/footerToggel.js' : ['src/footerToggel.js'],
            'build/lib.js' : ['src/lib.js'],
            'build/uichatbox.js' : ['src/uichatbox.js'],
            'build/uichatlist.js' : ['src/uichatlist.js'],
            'build/uichatroom.js' : ['src/uichatroom.js'],
            'build/window.js' : ['src/window.js'],
            'build/lang.en.js' : ['src/lang.en.js']
          }
        }
      }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify'); // load the given tasks
    grunt.registerTask('default', ['uglify:my_target']); // Default grunt tasks maps to grunt
};