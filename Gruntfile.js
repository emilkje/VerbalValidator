
module.exports = function(grunt) {
	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

		phpunit: {
	      classes: {
	        dir: './test/'
	      },
	      options: {
	        bin: 'vendor/bin/phpunit',
	        bootstrap: 'vendor/autoload.php',
	        configuration: 'phpunit.xml',
	        colors: true
	      }
	    },

	    watch: {
	      files: ['test/**/*.php', 'src/**/*.php'],
	      tasks: ['default']
	    }
	});

	var growl = require('growl');
	['warn', 'fatal'].forEach(function(level) {
	  grunt.util.hooker.hook(grunt.fail, level, function(opt) {
	    growl(opt.name, {
	      title: opt.message,
	      image: 'Console'
	    });
	  });
	});

	grunt.loadNpmTasks('grunt-phpunit');
	grunt.loadNpmTasks('grunt-contrib-watch');

	grunt.registerTask('default', ['phpunit']);
}