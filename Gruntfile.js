module.exports = function( grunt ) {

	'use strict';

	// Project configuration
	grunt.initConfig( {

		pkg: grunt.file.readJSON( 'package.json' ),

		addtextdomain: {
			options: {
				textdomain: 'bring-3pl-shelfless-fulfillment-for-woocommerce',
			},
			update_all_domains: {
				options: {
					updateDomains: true
				},
				src: [ '*.php', '**/*.php', '!\.git/**/*', '!bin/**/*', '!node_modules/**/*', '!tests/**/*' ]
			}
		},

		wp_readme_to_markdown: {
			your_target: {
				files: {
					'README.md': 'readme.txt'
				}
			},
		},

		makepot: {
			target: {
				options: {
					domainPath: '/languages',
					exclude: [ '\.git/*', 'bin/*', 'node_modules/*', 'tests/*' ],
					mainFile: 'bring-3pl-shelfless-fulfillment-for-woocommerce.php',
					potFilename: 'bring-3pl-shelfless-fulfillment-for-woocommerce.pot',
					potHeaders: {
						poedit: true,
						'x-poedit-keywordslist': true,
						'last-translator': 'Harvey Entendez Diaz <h.diaz@arcanys.com>'
					},
					type: 'wp-plugin',
					updateTimestamp: true
				}
			}
		},

		semver: {
			options: {
				space: "\t"
			},
			your_target: {
				src: 'package.json',
				dest: 'package.json.out'
			}
		},
	} );

	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-wp-readme-to-markdown' );
	grunt.loadNpmTasks( 'grunt-semver' );
	grunt.registerTask( 'default', [ 'i18n','readme' ] );
	grunt.registerTask( 'i18n', ['addtextdomain', 'makepot'] );
	grunt.registerTask( 'readme', ['wp_readme_to_markdown'] );

	grunt.util.linefeed = '\n';

};
