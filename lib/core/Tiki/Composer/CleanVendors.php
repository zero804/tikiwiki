<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

namespace Tiki\Composer;

use Composer\Script\Event;
use Composer\Util\FileSystem;

class CleanVendors
{
	private static $standardFiles = [
		//directories
		'development',
		'demo',
		'demo1',
		'demo2',
		'demos',
		'doc',
		'docs',
		'documentation',
		'samples',
		'examples',
		'test',
		'testing',
		'tests',
		'vendor',
		'www',
		'.gitattributes',
		'.gitignore',
		'.gitmodules',
		'.jshintrc',
		'bower.json',
		'changelog.txt',
		'changelog',
		'changelog.md',
		'readme.md',
		'composer.json',
		'composer.lock',
		'gruntfile.js',
		'gruntfile.coffee',
		'package.json',
		'.npmignore',
		'.github',
		'.scrutinizer.yml',
		'.travis.yml',
		'.travis.install.sh',
		'.editorconfig',
		'.jscsrc',
		'.jshintignore',
		'.eslintignore',
		'.eslintrc',
		'.hound.yml',
		'contributing.md',
		'changes.md'
	];

	public static function clean(Event $event)
	{
		$themes = __DIR__ . '/../../../../themes/';
		$vendors = $event->getComposer()->getConfig()->get('vendor-dir');

		if (substr($vendors, -1, 1) !== DIRECTORY_SEPARATOR) {
			$vendors .= DIRECTORY_SEPARATOR;
		}

		$fs = new FileSystem;
		$fs->ensureDirectoryExists($themes);

		self::addIndexFile($themes);
		self::addIndexFile($vendors);
		self::removeStandard($vendors);
		$fs->remove($vendors . 'adodb/adodb/cute_icons_for_site');
		$fs->remove($vendors . 'aFarkas/html5shiv/build');
		$fs->remove($vendors . 'bombayworks/zendframework1/library/Zend/Service/WindowsAzure/CommandLine/Scaffolders');
		$fs->remove($vendors . 'ckeditor/samples');
		self::removeMultiple(
			$vendors . 'codemirror/codemirror',
			[
				'doc',
				'mode/tiki',
				'index.html',
				'bin'
			]
		);

		self::removeMultiple($vendors . 'cwspear/bootstrap-hover-dropdown', ['bootstrap-hover-dropdown.min.js', 'demo.html']);
		self::removeMultiple($vendors . 'jquery/jquery-sheet', ['jquery-1.10.2.min.js', 'jquery-ui', 'parser.php', 'parser/formula/formula.php']);
		self::removeMultiple(
			$vendors . 'jquery/jquery-timepicker-addon',
			[
				'lib',
				'src',
				'jquery-ui-timepicker-addon.json',
				'jquery-ui-timepicker-addon.min.css',
				'jquery-ui-timepicker-addon.min.js'
			]
		);
		self::removeMultiple($vendors . 'jquery/jquery-ui', ['development-bundle', 'external']);
		self::removeMultiple($vendors . 'jquery/jtrack', ['js/jquery.json-2.2.min.js', 'js/jquery-1.4.2.min.js']);
		self::removeMultiple($vendors . 'jquery/md5', ['css', 'js/demo.js', 'js/md5.min.js', 'test']);
		$fs->remove($vendors . 'jquery/minicart/src');
		self::removeMultiple(
			$vendors . 'jquery/plugins/anythingslider',
			[
				'demos.html',
				'anythingslider.jquery.json',
				'expand.html',
				'simple.html',
				'video.html'
			]
		);
		self::removeMultiple(
			$vendors . 'jquery/plugins/chosen',
			[
				'docsupport',
				'chosen.css',
				'chosen.jquery.min.js',
				'chosen.min.css',
				'chosen.proto.js',
				'chosen.proto.min.js',
				'chosen-sprite.png',
				'chosen-sprite@2x.png',
				'index.proto.html',
				'options.html'
			]
		);
		$fs->remove($vendors . 'jquery/plugins/colorbox/content');
		self::removeMultiple(
			$vendors . 'jquery/plugins/galleriffic',
			[
				'js/jquery-1.3.2.js',
				'js/jquery.history.js',
				'js/jush.js',
				'example-1.html',
				'example-2.html',
				'example-3.html',
				'example-4.html',
				'example-5.html',
			]
		);
		$fs->remove($vendors . 'jquery/plugins/infinitecarousel/jquery.infinitecarousel3.min.js');
		self::removeMultiple(
			$vendors . 'jquery/plugins/jquery-validation',
			[
				'lib',
				'src',
				'dist/additional-methods.js',
				'dist/additional-methods.min.js',
				'dist/jquery.validate.min.js'
			]
		);
		self::removeMultiple(
			$vendors . 'jquery/plugins/jquery-json',
			[
				'dist',
				'libs',
				'HISTORY.md',
			]
		);
		$fs->remove($vendors . 'jquery/plugins/reflection-jquery/src');
		self::removeMultiple(
			$vendors . 'jquery/plugins/superfish',
			[
				'src',
				'superfish.jquery.json',
				'dist/js/jquery.js',
				'dist/js/superfish.min.js'
			]
		);
		self::removeMultiple(
			$vendors . 'mottie/tablesorter',
			[
				'addons',
				'beta-testing',
				'css',
				'dist',
				'testing',
				'bower.json',
				'CONTRIBUTING.md',
				'example.json',
				'Gruntfile.js',
				'index.html',
				'package.json',
				'tablesorter.jquery.json',
				'test.html',
				'js/extras',
				'js/jquery.tablesorter.js',
				'js/jquery.tablesorter.widgets.js',
				'js/parsers/parser-date.js',
				'js/parsers/parser-date-extract.js',
				'js/parsers/parser-date-iso8601.js',
				'js/parsers/parser-date-month.js',
				'js/parsers/parser-date-range.js',
				'js/parsers/parser-date-two-digit-year.js',
				'js/parsers/parser-date-weekday.js',
				'js/parsers/parser-duration.js',
				'js/parsers/parser-feet-inch-fraction.js',
				'js/parsers/parser-file-type.js',
				'js/parsers/parser-globalize.js',
				'js/parsers/parser-ignore-articles.js',
				'js/parsers/parser-image.js',
				'js/parsers/parser-metric.js',
				'js/parsers/parser-named-numbers.js',
				'js/parsers/parser-network.js',
				'js/parsers/parser-roman.js',
				'js/widgets/widget-alignChar.js',
				'js/widgets/widget-build-table.js',
				'js/widgets/widget-chart.js',
				'js/widgets/widget-cssStickyHeaders.js',
				'js/widgets/widget-columns.js',      //in jquery.tablesorter.combined.js
				'js/widgets/widget-editable.js',
				'js/widgets/widget-filter.js',      //in jquery.tablesorter.combined.js
				'js/widgets/widget-filter-formatter-html5.js',
				'js/widgets/widget-filter-formatter-select2.js',
				'js/widgets/widget-filter-type-insideRange.js',
				'js/widgets/widget-formatter.js',
				'js/widgets/widget-headerTitles.js',
				'js/widgets/widget-output.js',
				'js/widgets/widget-print.js',
				'js/widgets/widget-reflow.js',
				'js/widgets/widget-repeatheaders.js',
				'js/widgets/widget-resizable.js',       //in jquery.tablesorter.combined.js
				'js/widgets/widget-saveSort.js',        //in jquery.tablesorter.combined.js
				'js/widgets/widget-scroller.js',
				'js/widgets/widget-sortTbodies.js',
				'js/widgets/widget-staticRow.js',
				'js/widgets/widget-stickyHeaders.js',   //in jquery.tablesorter.combined.js
				'js/widgets/widget-storage.js',         //in jquery.tablesorter.combined.js
				'js/widgets/widget-uitheme.js'          //in jquery.tablesorter.combined.js
			]
		);
		self::removeMultiple(
			$vendors . 'jquery/plugins/treetable',
			[
				'javascripts/test',
				'stylesheets/jquery.treetable.theme.default.css',
				'stylesheets/screen.css',
				'treetable.jquery.json'
			]
		);
		self::removeMultiple(
			$vendors . 'jquery/plugins/zoom',
			[
				'jquery.zoom.min.js',
				'zoom.jquery.json',
				'demo.html',
				'daisy.jpg',
				'roxy.jpg'
			]
		);
		self::removeMultiple($vendors . 'mediumjs/mediumjs', ['src', 'medium.min.js']);
		$fs->remove($vendors . 'phpcas/phpcas/CAS-1.3.2/docs');
		$fs->remove($vendors . 'phpseclib/phpseclib/tests');
		$fs->remove($vendors . 'onelogin/php-saml/demo-old');
		self::removeMultiple(
			$vendors . 'player',
			[
				'flv/base',
				'flv/classes',
				'flv/html5',
				'flv/mtasc',
				'flv/template_js',
				'flv/template_maxi',
				'flv/template_mini',
				'flv/template_multi',
				'flv/build.xml',
				'flv/flv_stream.php',
				'flv/template_default/compileTemplateDefault.bat',
				'flv/template_default/compileTemplateDefault.sh',
				'flv/template_default/rorobong.jpg',
				'flv/template_default/TemplateDefault.as',
				'mp3/classes',
				'mp3/mtasc',
				'mp3/template_js',
				'mp3/template_maxi',
				'mp3/template_mini',
				'mp3/template_multi',
				'mp3/build.xml',
				'mp3/template_default/compileTemplateDefault.bat',
				'mp3/template_default/compileTemplateDefault.sh',
				'mp3/template_default/TemplateDefault.as',
				'mp3/template_default/test.mp3',
			]
		);
		self::removeMultiple(
			$vendors . 'rangy/rangy',
			[
				'uncompressed/rangy-highlighter.js',
				'uncompressed/rangy-serializer.js',
				'uncompressed/rangy-textrange.js',
				'rangy-core.js',
				'rangy-cssclassapplier.js',
				'rangy-highlighter.js',
				'rangy-selectionsaverestore.js',
				'rangy-serializer.js',
				'rangy-textrange.js',
			]
		);
		self::removeMultiple(
			$vendors . 'studio-42/elfinder',
			[
				'files',
				'elfinder.html',
			]
		);
		self::removeMultiple(
			$vendors . 'jcbrand/converse.js',
			[
				'fonticons/demo-files',
				'fonticons/demo.html',
				'mockup',
				'mockup.html'
			]
		);

		$fs->remove($vendors . 'twitter/bootstrap/docs');
		$fs->remove($vendors . 'zetacomponents/base/design');
		$fs->remove($vendors . 'zetacomponents/webdav/design');
		$fs->remove($vendors . 'nicolaskruchten/pivottable/images/animation.gif');

		// These are removed to avoid composer warnings caused by classes declared in multiple locations
		$fs->remove($vendors . 'adodb/adodb/datadict/datadict');
		$fs->remove($vendors . 'adodb/adodb/session/session');
		$fs->remove($vendors . 'adodb/adodb/perf/perf');
		$fs->remove($vendors . 'adodb/adodb/drivers/drivers');
		$fs->remove($vendors . 'adodb/adodb/adodb-active-recordx.inc.php');
		$fs->remove($vendors . 'adodb/adodb/drivers/adodb-informix.inc.php');
		$fs->remove($vendors . 'adodb/adodb/perf/perf-informix.inc.php');
		$fs->remove($vendors . 'adodb/adodb/datadict/datadict-informix.inc.php');

		// and cwspear/bootstrap-hover-dropdown includes bootstrap and jquery without asking
		$fs->remove($vendors . 'components/bootstrap');

		//Remove extra files to keep the system tidy
		$fs->remove($vendors . 'phpcas/phpcas/CAS-1.3.3/docs');
		$fs->remove($vendors . 'zendframework/zend-json/doc');
		$fs->remove($vendors . 'fortawesome/font-awesome/src/_includes/examples');
		$fs->remove($vendors . 'fortawesome/font-awesome/src/3.2.1/examples');
		$fs->remove($vendors . 'tijsverkoyen/css-to-inline-styles/TijsVerkoyen/CssToInlineStyles/tests/examples');
		$fs->remove($vendors . 'phpcas/phpcas/CAS-1.3.3/docs/examples');
		$fs->remove($vendors . 'fortawesome/font-awesome/src/_includes/tests');
		$fs->remove($vendors . 'tijsverkoyen/css-to-inline-styles/TijsVerkoyen/CssToInlineStyles/tests');
		$fs->remove($vendors . 'twitter/bootstrap/js/tests');
		$fs->remove($vendors . 'symfony/dependency-injection/Symfony/Component/DependencyInjection/Tests');
		$fs->remove($vendors . 'symfony/console/Symfony/Component/Console/Tests');
		$fs->remove($vendors . 'symfony/config/Symfony/Component/Config/Tests');
		$fs->remove($vendors . 'symfony/filesystem/Tests');
		$fs->remove($vendors . 'blueimp/javascript-load-image/js/demo.js');
		$fs->remove($vendors . 'blueimp/javascript-load-image/css');
		$fs->remove($vendors . 'blueimp/javascript-load-image/index.html');
		$fs->remove($vendors . 'blueimp/jquery-file-upload/cors');
		$fs->remove($vendors . 'blueimp/jquery-file-upload/server');
		$fs->remove($vendors . 'Sam152/Javascript-Equal-Height-Responsive-Rows/demo.html');
		$fs->remove($vendors . 'jquery/jtrack/demo.html');

		$fs->remove($vendors . 'phpcas/phpcas/CAS-1.3.3/docs');
		$fs->remove($vendors . 'jquery/plugins/jquery-json/test');
		$fs->remove($vendors . 'alxlit/bootstrap-chosen/example.html');
		$fs->remove($vendors . 'alxlit/bootstrap-chosen/example.png');
		$fs->remove($vendors . 'chartjs/Chart.js/samples');

		self::removeMultiple(
			$vendors . 'smarty/smarty',
			[
				'distribution/demo',
				'change_log.txt',
				'INHERITANCE_RELEASE_NOTES.txt',
				'SMARTY_2_BC_NOTES.txt',
				'SMARTY_3.0_BC_NOTES.txt',
				'SMARTY_3.1_NOTES.txt',
				'readme'
			]
		);

		self::removeMultiple($vendors . 'blueimp/jquery-file-upload/css', ['demo-ie8.css', 'demo.css']);
		self::removeMultiple(
			$vendors . 'blueimp/jquery-file-upload',
			[
				'angularjs.html',
				'basic.html',
				'basic-plus.html',
				'index.html',
				'jquery-ui.html'
			]
		);
		self::removeMultiple(
			$vendors . 'svg-edit/svg-edit/',
			[
				'embedapi.html',
				'extensions/imagelib/index.html',
				'browser-not-supported.html',
			]
		);
		self::removeMultiple(
			$vendors . 'etdsolutions',
			[
				'jquery',
				'jquery-ui',
			]
		);

		self::removeMultiple(
			$vendors . 'ahand/mobileesp',
			[
				'ASP_NET',
				'Cpp',
				'Java',
				'JavaScript',
				'MobileESP_UA-Test-Strings',
				'Python',
			]
		);

		self::removeMultiple($vendors . 'plotly/plotly.js/',
			[
				'src',
				'dist/extras',
				'dist/topojson',
				'dist/plotly.js',
				'dist/plotly.min.js',
				'dist/plot-schema.json',
				'dist/plotly-basic.js',
				'dist/plotly-basic.min.js',
				'dist/plotly-cartesian.js',
				'dist/plotly-finance.js',
				'dist/plotly-finance.min.js',
				'dist/plotly-geo-assets.js',
				'dist/plotly-geo.js',
				'dist/plotly-geo.min.js',
				'dist/plotly-gl2d.js',
				'dist/plotly-gl2d.min.js',
				'dist/plotly-gl3d.js',
				'dist/plotly-gl3d.min.js',
				'dist/plotly-mapbox.js',
				'dist/plotly-mapbox.min.js',
				'dist/plotly-with-meta.js',
				'dist/translation-keys.txt',
				'dist/plotly-locale-*'
			]
		);
	}

	private static function addIndexFile($path)
	{
		if (file_exists($path) || ! is_writable($path)) {
			return;
		}

		file_put_contents($path . 'index.php', '<?php header("location: ../index.php"); die;');
	}

	private static function removeStandard($base)
	{
		$fs = new FileSystem;
		$vendorDirs = glob($base . '*/*', GLOB_ONLYDIR);

		foreach ($vendorDirs as $dir) {
			if (is_dir($dir)) {
				foreach (self::$standardFiles as $file) {
					$path = $dir . '/' . $file;
					if (file_exists($path) || is_dir($path)) {
						$fs->remove($path);
					}
				}
				self::removeStandard($dir);
			}
		}
	}

	private static function removeMultiple($base, array $files)
	{
		$fs = new FileSystem;
		foreach ($files as $file) {
			$path = $base . '/' . $file;
			if (file_exists($path) || is_dir($path)) {
				$fs->remove($path);
			}
		}
	}
}
