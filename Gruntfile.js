module.exports = function (grunt) {

	'use strict';

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		sync_data: grunt.file.readJSON('sync-setup.json'),

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
			configBackup: {
				src: '.git/config',
				dest: '.git/config-bkp'
			},
			configRename: {
				src: '.git/config',
				dest: '.git/config',
				options: {
					process: function( content, srcpath ) {
						return content.split('url = git@gitlab.com:jawsdigital/wpbase.git').join( 'url = voce_precisa_mudar' );
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
					process: function (content, srcpath) {
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
			}
		},

		sync: {
			themes: {
				files: [
					{
						cwd: 'dev/themes/',
						src: [
							"**/*",
							'**/assets/**',
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
					path: '<%= sync_data.stage.dest %>',
					host: '<%= sync_data.stage.host %>',
					username: '<%= sync_data.stage.user %>',
					password: '<%= sync_data.stage.pwd %>',
					port: '<%= sync_data.stage.port %>',
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
					path: '<%= sync_data.prod.dest %>',
					host: '<%= sync_data.prod.host %>',
					username: '<%= sync_data.prod.user %>',
					password: '<%= sync_data.prod.pwd %>',
					port: '<%= sync_data.prod.port %>',
					showProgress: true,
					createDirectories: true
				}
			}
		},

		ftpush: {
			stage: {
				auth: {
					host: '<%= sync_data.stage.host %>',
					port: '<%= sync_data.stage.port %>',
					username: '<%= sync_data.stage.user %>',
					password: '<%= sync_data.stage.pwd %>',
				},
				src: ["dist/**/*", "!dist/wp-config.php", "!dist/**/wp-config.php"],
				dest: "./",
				exclusions: ['dist/**/.DS_Store', 'dist/**/Thumbs.db', 'dist/tmp']
			},
			prod: {
				auth: {
					host: '<%= sync_data.prod.host %>',
					port: '<%= sync_data.prod.port %>',
					username: '<%= sync_data.prod.user %>',
					password: '<%= sync_data.prod.pwd %>',
				},
				src: ["dist/**/*", "!dist/wp-config.php", "!dist/**/wp-config.php"],
				dest: '<%= sync_data.prod.dest %>',
				exclusions: ['dist/**/.DS_Store', 'dist/**/Thumbs.db', 'dist/tmp']
			}
		},

		sshexec: {
			stage: {
				command: 'ls -la',
				options: {
					host: '<%= sync_data.stage.host %>',
					username: '<%= sync_data.stage.user %>',
					password: '<%= sync_data.stage.pwd %>',
				}
			}
		},

		uglify: {
			application: {
				options: {
					sourceMap: true
				},
				files: { 'dev/themes/assets/js/main.js' : [ 'dev/themes/_assets/js/main.js' ] }
			},
			admin: {
				options: {
					sourceMap: true
				},
				files: { 'dev/themes/assets/js/admin.js' : [ 'dev/themes/_assets/js/admin.js' ] }
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

		notify_hooks: {
			options: {
				enabled: true,
				max_jshint_notifications: 5, // maximum number of notifications from jshint output 
				title: "<%= pkg.themeName %>", // defaults to the name in package.json, or will use project directory's name 
				success: true, // whether successful grunt executions should be notified automatically 
				duration: 5 // the duration of notification in seconds, for `notify-send only 
			}
		},

		notify: {
			options: {
				title: '<%= pkg.themeName %>'
			},
			watch: {
				options: {
					message: 'Watch waiting...', //required
				}
			},
			css: {
				options: {
					message: 'CSS compilado...'
				}
			},
			img: {
				options: {
					message: 'Imagens processadas...'
				}
			},
			js: {
				options: {
					message: 'Javascript minificado...'
				}
			},
			textFiles: {
				options: {
					message: 'Arquivos sincronizados...'
				}
			}
		},

		watch: {
			options: {
				spawn: false,
				livereload: true
			},
			css: {
				files: [ 'dev/themes/_assets/sass/**/*.scss', 'dev/themes/_assets/img/icon/*.{png,jpg,gif}' ],
				tasks: [ 'compass', 'sync:themes', 'notify:css' ]
			},
			img: {
				files: [ 'dev/themes/_assets/img/**/*.{png,jpg,gif}','!dev/themes/assets/img/**/*.*','!dev/themes/_assets/img/icon/**/*.{png,jpg,gif}' ],
				tasks: [ 'imagemin', 'sync:themes', 'notify:img' ]
			},
			js: {
				files: [ 'dev/themes/_assets/js/**/*.js' ],
				tasks: [ 'uglify', 'sync:themes', 'notify:js' ]
			},
			textFiles: {
				files: [ 'dev/themes/**/*','!dev/themes/_assets/**/*','!dev/themes/_assets/**/*.{js,png,jpg,gif}' ],
				tasks: [ 'sync:themes', 'notify:textFiles' ]
			}
		}
	});

	// Load all plugins
	require('load-grunt-tasks')(grunt);

	//Tasks
	grunt.registerTask( 'initial', [ 'copy:configBackup', 'copy:configRename', 'curl', 'unzip', 'copy:initialTheme', 'copy:source', 'sync:themes'] );
	grunt.registerTask( 'start', ['initial', 'imagemin', 'uglify', 'compass'] );
	
	grunt.registerTask( 'basic', ['uglify', 'compass', 'sync:themes'] );
	grunt.registerTask( 'default', [ 'basic', 'watch'] );

	grunt.registerTask('stage', function() {
		var sync_data = grunt.file.readJSON('sync-setup.json');
		
		if ( sync_data.stage.type != '' ) {
			grunt.log.writeln("Connection Type: " + sync_data.stage.type);

			if ( sync_data.stage.type == 'sftp' ) {
				grunt.config( 'watch.css.tasks', [ 'compass', 'sync:themes', 'sftp:stage', 'notify:css' ] );
				grunt.config( 'watch.img.tasks', [ 'imagemin', 'sync:themes', 'sftp:stage', 'notify:img' ] );
				grunt.config( 'watch.js.tasks', [ 'uglify', 'sync:themes', 'sftp:stage', 'notify:js' ] );
				grunt.config( 'watch.textFiles.tasks', [ 'sync:themes', 'sftp:stage', 'notify:textFiles' ] );
			}else{
				grunt.config( 'watch.css.tasks', [ 'compass', 'sync:themes', 'ftpush:stage', 'notify:css' ] );
				grunt.config( 'watch.img.tasks', [ 'imagemin', 'sync:themes', 'ftpush:stage', 'notify:img' ] );
				grunt.config( 'watch.js.tasks', [ 'uglify', 'sync:themes', 'ftpush:stage', 'notify:js' ] );
				grunt.config( 'watch.textFiles.tasks', [ 'sync:themes', 'ftpush:stage', 'notify:textFiles' ] );
			}
		}

		grunt.task.run('basic');
		grunt.task.run('notify:watch');
		grunt.task.run('watch');
	});

	grunt.registerTask('prod', function() {
		var sync_data = grunt.file.readJSON('sync-setup.json');

		if ( sync_data.stage.type != '' ) {
			grunt.log.writeln("Connection Type: " + sync_data.prod.type);

			if ( sync_data.prod.type == 'sftp' ) {
				grunt.config( 'watch.css.tasks', [ 'compass', 'sync:themes', 'sftp:prod', 'notify:css' ] );
				grunt.config( 'watch.img.tasks', [ 'imagemin', 'sync:themes', 'sftp:prod', 'notify:img' ] );
				grunt.config( 'watch.js.tasks', [ 'uglify', 'sync:themes', 'sftp:prod', 'notify:js' ] );
			}else{
				grunt.config( 'watch.css.tasks', [ 'compass', 'sync:themes', 'ftpush:prod', 'notify:css' ] );
				grunt.config( 'watch.img.tasks', [ 'imagemin', 'sync:themes', 'ftpush:prod', 'notify:img' ] );
				grunt.config( 'watch.js.tasks', [ 'uglify', 'sync:themes', 'ftpush:prod', 'notify:js' ] );
			}
		}

		grunt.task.run('basic');
		grunt.task.run('notify:watch');
		grunt.task.run('watch');
	});

	var cssDirectory = new Set();
	grunt.event.on('watch', function(action, filepath, target) {
		var path = require('path'),
			pkg = grunt.file.readJSON('package.json'),
			sync_data = grunt.file.readJSON('sync-setup.json'),
			siteDirectory = './';

		// grunt.log.writeln('\n' + target + ': ' + filepath + ' might have ' + action + '\n\n');

		switch ( target ){
			case 'img':
				siteDirectory = filepath.split('\\').join('/').replace( 'dev/themes', 'dist/wp-content/themes/' + pkg.name ).replace( '_assets', 'assets' );
				break;
			case 'js':
				siteDirectory = 'dist/wp-content/themes/' + pkg.name + '/assets/' + target + '/**/*';
				siteDirectory = siteDirectory.split('\\').join('/');
				break;
			case 'css':
				cssDirectory.clear();
				siteDirectory = [
					'dist/wp-content/themes/' + pkg.name + '/assets/' + target + '/**/*',
					'dist/wp-content/themes/' + pkg.name + '/assets/img/icon.png'
				];
				break;
			case 'textFiles':
				var normal = true;
				if ( filepath.split('img').length > 1 ){return}
				if ( filepath.split('js').length > 1 || filepath.split('css').length > 1 ) { 
					var file = filepath.split('\\').join('/'),
						file = file.split('/').slice(0, -1).join('/');
						file = file.replace( 'dev/themes', 'dist/wp-content/themes/' + pkg.name ).replace( '_assets', 'assets' );
					if ( cssDirectory.has(file) === false ) {
						cssDirectory.add(file);
					}

					normal = false;
					siteDirectory = file;
				}

				if ( normal ) {
					siteDirectory = filepath.split('\\').join('/').replace( 'dev/themes', 'dist/wp-content/themes/' + pkg.name ).replace( '_assets', 'assets' );
				}
				break;
		}

		//  SFTP Config
		var option = 'sftp.stage.files';
		// grunt.log.writeln(option + ' changed to ' + siteDirectory );
		grunt.config( option, { './' : siteDirectory } );
		option = 'sftp.prod.files';
		// grunt.log.writeln(option + ' changed to ' + siteDirectory );
		grunt.config( option, { './' : siteDirectory } );
		// /SFTP Config

		//  FTP Config
		if ( cssDirectory.size > 0 ) {
			var dest = Array.from(cssDirectory.values()),
				dest = dest.toString().replace( 'dist/', sync_data.prod.dest );
			
			grunt.log.writeln('Src: ' + siteDirectory );
			grunt.log.writeln('Dest: ' + dest );

			grunt.config( 'ftpush.stage.src', siteDirectory );
			grunt.config( 'ftpush.stage.dest', dest );

			grunt.config( 'ftpush.prod.src', siteDirectory );
			grunt.config( 'ftpush.prod.dest', dest );
		}
	});

};
