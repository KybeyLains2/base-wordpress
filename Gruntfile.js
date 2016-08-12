module.exports = function (grunt) {

	'use strict';

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		sync: grunt.file.readJSON('sync-setup.json'),

		curl: {
			WordPress: {
				dest: "zip/wordpress-latest-pt_BR.zip",
				src: "https://br.wordpress.org/latest-pt_BR.zip"
			}
		},

		unzip: {
			'src/': 'zip/wordpress-latest-pt_BR.zip'
		},

		copy: {
			options : {
				processContentExclude: ['**/*.{png,gif,jpg,ico,psd}']
			},
			source: {
				files: [
					{
						expand: true,
						cwd: 'src/',
						src: [
							"**",
							"!*.git",
							"!**/themes/twenty**/**",
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
						content = content.split('\{theme-name\}').join(grunt.template.process('<%= pkg.themeName %>'));
						content = content.split('\{name\}').join(grunt.template.process('<%= pkg.name %>'));
						content = content.split('\{description\}').join(grunt.template.process('<%= pkg.description %>'));
						content = content.split('\{version\}').join(grunt.template.process('<%= pkg.version %>'));
						content = content.split('\{repository\}').join(grunt.template.process('<%= pkg.repository.url %>'));
						content = content.split('\{author\}').join(grunt.template.process('<%= pkg.author %>'));
						content = content.split('\{author-uri\}').join(grunt.template.process('<%= pkg.authorUrl %>'));

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

		sync: {
			themes: {
				files: [
					{
						cwd: 'dev/themes/',
						src: [
							"**",
							'!**/_assets/**'
						],
						dest: 'dist/wp-content/themes/<%= pkg.name %>/'
					}, // makes all src relative to cwd 
				],
				verbose: true, // Default: false
				// pretend: true, // Don't do any disk operations - just write log. Default: false 
				failOnError: true, // Fail the task when copying is not possible. Default: false 
				// ignoreInDest: "**/*.js", // Never remove js files from destination. Default: none 
				updateAndDelete: true, // Remove all files from dest that are not found in src. Default: false 
				compareUsing: "md5" // compares via md5 hash of file contents, instead of file modification time. Default: "mtime" 
			}
		},
		
		sftp: {
			stage: {
				files: {
					"./": ["dist/**/*", "!dist/wp-config.php", "!dist/**/wp-config.php"]
				},
				options: {
					srcBasePath: "dist/",
					path: '<%= sync.stage.dest %>',
					host: '<%= sync.stage.host %>',
					username: '<%= sync.stage.user %>',
					password: '<%= sync.stage.pwd %>',
					showProgress: true,
					createDirectories: true
				}
			},
			prod: {
				files: {
					"./": ["dist/**/*", "!dist/wp-config.php", "!dist/**/wp-config.php"]
				},
				options: {
					srcBasePath: "dist/",
					path: '<%= sync.prod.dest %>',
					host: '<%= sync.prod.host %>',
					username: '<%= sync.prod.user %>',
					password: '<%= sync.prod.pwd %>',
					showProgress: true,
					createDirectories: true
				}
			}
		},

		sshexec: {
			stage: {
				command: 'ls -la',
				options: {
					host: '<%= sync.stage.host %>',
					username: '<%= sync.stage.user %>',
					password: '<%= sync.stage.pwd %>',
				}
			}
		},

		uglify: {
			application: {
				options: {
					sourceMap: true
				},
				files: { 'dev/themes/assets/js/main.js' : [ 'dev/themes/_assets/js/*.js' ] }
			},
			plugins: {
				options: {
					sourceMap: true
				},
				files: { 'dev/themes/assets/js/plugins.js' : [ 'dev/themes/_assets/js/plugins/*.js' ] }
			}
		},

		// javascript linting with jshint
		jshint: {
			options: {
				jshintrc: '.jshintrc',
				"force": true
			},
			all: [
				'Gruntfile.js',
				'dev/themes/_assets/js/**/*.js'
			]
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
					dest: 'dev/themes/assets/img/'
				}]
			} // build
		}, // imagemin
 
		webshot: {
			homepage: {
				options: {
					siteType: 'url',
					site: 'http://www.mecalor.com.br',
					savePath: 'src/screenshot.jpg',
					windowSize: {
						width: 1920,
						height: 1080
					},
					errorIfStatusIsNot200: true
				}
			}
		},

		// image_resize: {
		// 	no_overwrite: {
		// 		options: {
		// 			width: 600,
		// 			overwrite: false
		// 		},
		// 		files: {
		// 			'src/screenshot1.jpg': 'src/screenshot.jpg'
		// 		}
		// 	}
		// },
		image_resize: {
			resize: {
				options: {
					width: 100,
				},
				src: 'src/*.jpg',
				dest: 'src/screenshot/'
			}
		},

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
				files: [ 'dev/themes/_assets/img/**/*.{png,jpg,gif}','!dev/themes/_assets/img/icon/**/*.{png,jpg,gif}' ],
				tasks: [ 'imagemin' ]
			},
			js: {
				files: [ 'dev/themes/_assets/js/**/*.js' ],
				tasks: [ 'uglify' ]
			},
			textFiles: {
				files: [ 'dev/themes/**/*','!dev/themes/_assets/**/*' ],
				tasks: [ 'sync:themes' ]
			}
		}
	});

	// Load all plugins
	require('load-grunt-tasks')(grunt);

	//Tasks
	grunt.registerTask( 'initial', ['curl', 'unzip', 'copy:initialTheme', 'copy:source', 'copy:configCompass', 'sync:themes'] );
	grunt.registerTask( 'start', ['initial', 'imagemin', 'uglify', 'compass'] );
	
	grunt.registerTask( 'basic', ['imagemin', 'uglify', 'compass', 'sync:themes'] );
	grunt.registerTask( 'default', [ 'basic', 'watch'] );

	grunt.registerTask('stage', function() {
		grunt.config( 'watch.css.tasks', [ 'compass', 'sftp:stage' ] );
		grunt.config( 'watch.img.tasks', [ 'imagemin', 'sftp:stage' ] );
		grunt.config( 'watch.js.tasks', [ 'uglify', 'sftp:stage' ] );
		grunt.config( 'watch.textFiles.tasks', [ 'sync:themes', 'sftp:stage' ] );

		grunt.task.run('watch');
	});

	grunt.registerTask('prod', function() {
		grunt.config( 'watch.css.tasks', [ 'compass', 'sftp:prod' ] );
		grunt.config( 'watch.img.tasks', [ 'imagemin', 'sftp:prod' ] );
		grunt.config( 'watch.js.tasks', [ 'uglify', 'sftp:prod' ] );
		grunt.config( 'watch.textFiles.tasks', [ 'sync:themes', 'sftp:prod' ] );

		grunt.task.run('watch');
	});

	grunt.event.on('watch', function(action, filepath, target) {
		var path = require('path'),
			pkg = grunt.file.readJSON('package.json');

		grunt.log.writeln(target + ': ' + filepath + ' might have ' + action);

		switch ( target ){
			case 'js':
				var siteDirectory = 'dist/wp-content/themes/' + pkg.name + '/assets/' + target + '/**/*';
				siteDirectory = siteDirectory.split('\\').join('/');
			case 'css':
				var siteDirectory = [
					'dist/wp-content/themes/' + pkg.name + '/assets/' + target + '/**/*',
					'dist/wp-content/themes/' + pkg.name + '/assets/img/icon.png'
				];
				break;
			case 'textFiles':
			case 'img':
				var siteDirectory = filepath.split('\\').join('/').replace( 'dev/themes', 'dist/wp-content/themes/' + pkg.name ).replace( '_assets', 'assets' );
				break;
		}

		var option = 'sftp.stage.files';
		grunt.log.writeln(option + ' changed to ' + siteDirectory );
		grunt.config( option, { './' : siteDirectory } );
		option = 'sftp.prod.files';
		grunt.log.writeln(option + ' changed to ' + siteDirectory );
		grunt.config( option, { './' : siteDirectory } );
	});

};
