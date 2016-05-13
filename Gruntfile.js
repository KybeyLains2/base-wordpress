module.exports = function (grunt) {

	'use strict';

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		copy: {
			source: {
			    files: [
					{
						expand: true,
						cwd: 'src/',
						src: [
							"**",
							"!*.git",
							"!**/themes/twenty**/**",
							"!**/plugins/**/**",
							'!**/_assets/**'
						],
						dest: 'dist/',
						rename: function(dest, src){
							var new_src = src.split('/');
							new_src = new_src.slice(1, Number.MAX_VALUE);
							new_src = new_src.join('/');
							grunt.log.writeln(new_src);
							return dest + new_src.replace('BasicThemeName', '<%= pkg.name %>');
						}
					}
				]
			},
			configCompass: {
				src: 'config-sample.rb',
				dest: 'config.rb',
				options: {
					processContent: function( content, srcpath ) {
						return content.split('\{name\}').join(grunt.template.process('<%= pkg.name %>'));
					}
				}
			},
			initialTheme: {
				files: [
					{
						expand: true,
						cwd: 'src/BasicTheme/wp-content/themes/BasicThemeName/',
						src: ['**'],
						dest: 'dev/themes/'
					}
				],
				options: {
					processContent: function (content, srcpath) {
						var style_file = srcpath.search('BasicThemeName/style.css');

						if ( style_file != -1 ) {
							content = content.replace('\{theme-name\}', grunt.template.process('<%= pkg.themeName %>'));
							content = content.replace('\{name\}', grunt.template.process('<%= pkg.name %>'));
							content = content.replace('\{description\}', grunt.template.process('<%= pkg.description %>'));
							content = content.replace('\{version\}', grunt.template.process('<%= pkg.version %>'));
							content = content.replace('\{repository\}', grunt.template.process('<%= pkg.repository.url %>'));
							content = content.replace('\{author\}', grunt.template.process('<%= pkg.author %>'));
							content = content.replace('\{author-uri\}', grunt.template.process('<%= pkg.authorUrl %>'));
						}
						return content;
					}
				}
			},
			themes: {
				files: [
					{
						expand: true,
						cwd: 'dev/themes/',
						src: [
							"**",
							'!**/_assets/**'
						],
						dest: 'dist/wp-content/themes/<%= pkg.name %>/'
					}
				]
			}
		},

		uglify: {
			application: {
				options: {
					sourceMap: true
				},
				files: { 'dist/wp-content/themes/<%= pkg.name %>/assets/js/main.js' : [ 'dev/themes/_assets/js/*.js' ] }
			},
			plugins: {
				options: {
					sourceMap: true
				},
				files: { 'dist/wp-content/themes/<%= pkg.name %>/assets/js/plugins.js' : [ 'dev/themes/_assets/js/plugins/*.js' ] }
			}
		},

		compass: {
			build: {
				options: {
					config: 'config.rb'
				}
			}
		}, // compass

		imagemin: {
			build: {
				options: {
					optimizationLevel: 7
				},
				files: [{
					expand: true,
					cwd: 'dev/themes/_assets/img/',
					src: ['**/*.{png,jpg,gif}', '!icon/*.{png,jpg,gif}'],
					dest: 'dist/wp-content/themes/<%= pkg.name %>/assets/img/'
				}]
			} // build
		}, // imagemin

		watch: {
			options: {
				spawn: false,
				livereload: true
			},
			css: {
				files: [ 'dev/themes/_assets/sass/**/*.scss', 'dev/themes/_assets/img/icon/*.{png,jpg,gif}' ],
				tasks: [ 'compass' ]
			},
			img: {
				files: [ '!dev/themes/_assets/img/icon/', 'dev/themes/_assets/img/**/*.{png,jpg,gif}' ],
				tasks: [ 'imagemin' ]
			},
			js: {
				files: [ 'dev/themes/_assets/js/**/*.js' ],
				tasks: [ 'uglify' ]
			},
			textFiles: {
				files: [ 'dev/themes/**/*.{php,html,css}' ],
				tasks: [ 'copy:themes' ]
			}
		}
	});

	// Load all plugins
	require('load-grunt-tasks')(grunt);

	//Tasks
	grunt.registerTask( 'start', ['copy:source', 'copy:configCompass', 'copy:initialTheme', 'imagemin', 'uglify', 'compass'] );
	grunt.registerTask( 'default', ['copy:themes', 'imagemin', 'uglify', 'compass', 'watch'] );

};
