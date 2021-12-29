/*global module:false, require:false*/

module.exports = function(grunt) {

	require('load-grunt-tasks')(grunt);

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		dirs: {
			js      : '../assets/js',
			css     : '../assets/css',
			sass    : '../assets/sass'
		},

    	// Watch for changes
		watch: {
			compass: {
				files: [
					'<%= compass.dist.options.sassDir %>/**'
				],
				tasks: ['compass']
			},
			js: {
				files: [
					'<%= jshint.all %>'
				],
				tasks: ['jshint', 'uglify']
			}
		},

		// Javascript linting with jshint
		jshint: {
			options: {
				jshintrc: '.jshintrc'
			},
			all: [
				'Gruntfile.js',
				'<%= dirs.js %>/*.js',
				'!<%= dirs.js %>/*.min.js'
			]
		},

		// Uglify to concat and minify
		uglify: {
			options: {
				force: true,
				mangle: false
			},
			dist: {
				files: [{
					expand: true,
					cwd: '<%= dirs.js %>/',
					src: [
						'*.js',
						'!*.min.js'
					],
					dest: '<%= dirs.js %>/',
					ext: '.min.js'
				}]
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
					cwd: '<%= dirs.images %>/',
					src: ['**/*.{png,jpg,gif}'],
					dest: '<%= dirs.images %>/'
				}]
			}
		}
	});

	grunt.registerTask( 'default', [ 'compass', 'jshint', 'uglify' ]);
	grunt.registerTask( 'script', [ 'jshint', 'uglify' ]);
	grunt.registerTask( 'style', [ 'compass' ]);
	grunt.registerTask( 'image', [ 'imagemin' ]);
};
