module.exports = function (grunt) {

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		uglify: {
			options: {
				banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
			},
			build: {
				files: {
					'public/dist/todos.min.js': [
					'public/js/vendor/bootstrap.min.js',
					'public/js/vendor/jquery-ui-1.10.4.custom.min.js',
					'public/js/vendor/masonry.pkgd.min.js',
					'public/js/vendor/colorpicker.js',
					'public/js/vendor/notify.min.js',
					'public/js/functions.js',
					'public/js/dashboard.js'
					]
				}
			}
		},
		cssmin: {
			combine: {
				options: {
					banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
				},
				files: {
					'public/dist/todos.min.css': [
					'public/css/bootstrap.css',
					'public/css/font-awesome.min.css',
					'public/css/jquery-ui-1.10.4.custom.min.css',
					'public/css/colorpicker.css',
					'public/css/dashboard.css'
					]
				}
			}
		}
	});


grunt.loadNpmTasks('grunt-contrib-uglify');
grunt.loadNpmTasks('grunt-contrib-cssmin');

grunt.registerTask('default', ['uglify','cssmin']);

};
