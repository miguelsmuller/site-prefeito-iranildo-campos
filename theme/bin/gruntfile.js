/*global module:false, require:false*/
module.exports = function(grunt) {

	'use strict';

	require('load-grunt-tasks')(grunt);

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		dirs: {
			root    : '../../public/.',
			styles  : '../assets/styles',
			scripts : '../assets/scripts',
			images  : '../assets/images',
			fonts   : '../assets/fonts'
		},

		// Watch for changes
		watch: {
			options: {
				livereload: false
			},
			styles: {
				files: ['<%= dirs.styles %>/src/**/*.scss'],
				tasks: ['compass']
			},
			scripts: {
				files: ['Gruntfile.js', '<%= dirs.scripts %>/src/**/*.js'],
				tasks: ['jshint', 'uglify']
			}
		},

		// Javascript linting with jshint
		jshint: {
			options: {
				jshintrc: '.jshintrc'
			},
			dist: [
				'Gruntfile.js',
				'<%= dirs.scripts %>/src/**/*.js'
			]
		},

		// Uglify to concat and minify with uglify
		uglify: {
			options: {
				force: true,
				mangle: false
			},
			dist: {
				files: {
					'<%= dirs.scripts %>/dist/javascript.min.js': [
						//BOOTSTRAP SASS
						'../assets/components/bootstrap-sass/vendor/assets/javascripts/bootstrap/transition.js',
						'../assets/components/bootstrap-sass/vendor/assets/javascripts/bootstrap/collapse.js',
						'../assets/components/bootstrap-sass/vendor/assets/javascripts/bootstrap/tab.js',
						'../assets/components/bootstrap-sass/vendor/assets/javascripts/bootstrap/affix.js',
						'../assets/components/bootstrap-sass/vendor/assets/javascripts/bootstrap/dropdown.js',
						'../assets/components/bootstrap-sass/vendor/assets/javascripts/bootstrap/button.js',

						//CUSTOM JS
						'<%= dirs.scripts %>/src/javascript.js'
					],

					'<%= dirs.scripts %>/dist/contact.min.js': [
						'<%= dirs.scripts %>/src/contact.js'
					]
				}
			}
		},

		// Compile scss/sass files to CSS
		compass: {
			dist: {
				options: {
					force: true,

					basePath:'./',
					sassDir: '<%= dirs.styles %>/src/',
					cssDir: '<%= dirs.styles %>/dist/',
					javascriptsDir: '<%= dirs.scripts %>/dist/',
					imagesDir: '<%= dirs.images %>',
					fontsDir: '<%= dirs.fonts %>',

					outputStyle: 'compressed',
					relativeAssets: true,
					noLineComments: true
				}
			}
		},

		// Image optimization
		imagemin: {
			dist: {
				options: {
					optimizationLevel: 7,
					progressive: true
				},
				files: [{
					expand: true,
					cwd: '<%= dirs.images %>/src/',
					src: ['**/*.{png,jpg,gif,svg}'],
					dest: '<%= dirs.images %>/dist/'
				}]
			}
		}
	});

	grunt.registerTask( 'default', [ 'compass', 'jshint', 'uglify' ]);
	grunt.registerTask( 'script', [ 'jshint', 'uglify' ]);
	grunt.registerTask( 'style', [ 'compass' ]);
	grunt.registerTask( 'image', [ 'imagemin' ]);
};
