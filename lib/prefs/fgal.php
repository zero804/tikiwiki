<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_fgal_list()
{
	//set fgal_default_view options
	$defaultViews = [
		'list' => tra('List'),
		'browse' => tra('Browse'),
		'page' => tra('Page')
	];
	global $prefs;
	if (! empty($prefs['fgal_elfinder_feature']) && $prefs['fgal_elfinder_feature'] === 'y') {
		$defaultViews = $defaultViews + ['finder' => tra('Finder view')];
	}
	//set show options for list prefs
	$showOptions = [
		'n' => tra('Hide'),
		'y' => tra('Show as a column'),
		'o' => tra('Show in popup box'),
		'a' => tra('Both'),
	];

	return [
		'fgal_podcast_dir' => [
			'name' => tra('Podcast directory'),
			'description' => tra('File system directory for storing podcast files'),
			'type' => 'text',
			'help' => 'File+Gallery+Config',
			'size' => 50,
			'hint' => tra('The server must be able to read/write the directory.') . ' ' . tra('Required for podcasts.'),
			'perspective' => false,
			'default' => 'files/',
		],
		'fgal_batch_dir' => [
			'name' => tra('Path'),
			'type' => 'text',
			'help' => 'File+Gallery+config',
			'size' => 50,
			'hint' => tra('To enable and use directory batch loading, set up a web-readable directory (preferably outside the web space). Then upload files to that directory, such as with SCP, FTP, etc') . ' ' . tra('The server must be able to read the directory.') . ' ' . tra('The directory can be outside the web space.'),
			'perspective' => false,
			'default' => '',
		],
		'fgal_prevent_negative_score' => [
			'name' => tra('Prevent download if score becomes negative'),
			'description' => tra('If using Tiki Score system, users with a negative score cannot download files.'),
			'type' => 'flag',
			'help' => 'File+Gallery+config',
			'default' => 'n',
			'dependencies' => ['feature_score'],
		],
		'fgal_limit_hits_per_file' => [
			'name' => tra('Allow download limit per file'),
			'type' => 'flag',
			'help' => 'File+Gallery+config',
			'default' => 'n',
		],
		'fgal_allow_duplicates' => [
			'name' => tra('Allow file duplicates'),
			'description' => tra('Allow the same file to be uploaded more than once.'),
			'type' => 'list',
			'help' => 'File+Gallery+config',
			'perspective' => false,
			'options' => [
							  'n' => tra('Never'),
							  'y' => tra('Yes, even in the same gallery'),
							  'different_galleries' => tra('Only in different galleries')
			],
			'default' => 'y',
		],
		'fgal_display_zip_option' => [
			'name' => tra('Display ZIP option in gallery'),
			'description' => tra('Users can upload a .zip (archive file). Tiki will automatically un-zip the archive and add each file to the gallery..'),
			'type' => 'flag',
			'help' => 'File+Gallery+config',
			'default' => 'n',
		],
		'fgal_match_regex' => [
			'name' => tra('Must match'),

			'description' => tra('A regular expression that must be matched to accept the file example A-Za-z* (filename can only have a-z letters) 
For example, if you want to require that uploads must have a wordprocessing file extension, you could enter .*.(odt|sxw|doc|dot|rtf|wpt|frm|wpd|txt|ODT|SXW|DOC|DOT|RTF|WPT|FRM|WPD|TXT)'),
			'type' => 'text',
			'size' => 50,
			'default' => '',
		],
		'fgal_nmatch_regex' => [
			'name' => tra('Cannot match'),
			'description' => tra('A regular expression that, if matched, causes the file to be rejected. For example, .gif rejects gif images. Note that the period must be escaped since a regular expression is being used. If you don’t know anything about regular expressions just leave the fields blank and all the files will be accepted.'),
			'type' => 'text',
			'size' => 50,
			'default' => '',
		],
		'fgal_quota' => [
			'name' => tra('Quota for all files and archives'),
			'description' => tra('The total size of files uploaded to all the file galleries or to a specific file gallery can be limited. The quota for a file gallery applies to that file gallery and all the file galleries under it. 
When the limit is reached, no more files can be uploaded. The user will see an explanatory error message. An email can be sent via the Mail notifications feature.)'),
			'shorthint' => tra('0 for unlimited'),
			'type' => 'text',
			'units' => tra('megabytes'),
			'size' => 7,
			'default' => 0,
		],
		'fgal_quota_per_fgal' => [
			'name' => tra('Quota for each file gallery'),
			'description' => tra('A different quota can be defined for each file gallery.'),
			'type' => 'flag',
			'default' => 'n',
		],
		'fgal_quota_default' => [
			'name' => tra('Default quota for each new gallery'),
			'shorthint' => tra('0 for unlimited'),
			'type' => 'text',
			'units' => tra('megabytes'),
			'size' => 7,
			'default' => 0,
		],
		'fgal_quota_show' => [
			'name' => tra('Show quota bar in the list page'),
			'type' => 'list',
			'options' => [
				'n' 			=> tra('Never'),
				'bar_and_text' 	=> tra('Yes, display bar and detail text'),
				'y' 			=> tra('Yes, display only bar'),
				'text_only'		=> tra('Yes, display only text')
			],
			'default' => 'y',
		],
		'fgal_use_db' => [
			'name' => tra('Storage'),
			'description' => tra('Specify if uploaded files should be stored in the database or file directory.'),
			'type' => 'list',
			'perspective' => false,
			'options' => [
				'y' => tra('Store in database'),
				'n' => tra('Store in directory'),
			],
			'default' => 'y',
			'tags' => ['basic'],
		],
		'fgal_use_dir' => [
			'name' => tra('Path to the directory to store file gallery files'),
			'description' => tra("Specify a directory on this server, for example: /var/www/  It's recommended that this directory not be web-accessible. PHP must be able to read/write to the directory."),
			'type' => 'text',
			'size' => 50,
			'perspective' => false,
			'default' => 'storage/fgal/',
			'tags' => ['basic'],
		],
		'fgal_search_in_content' => [
			'name' => tra('Searchable file gallery content'),
			'description' => tra('Include the search form on the current gallery page just after "Find"'),
			'type' => 'flag',
			'default' => 'n',
		],
		'fgal_search' => [
			'name' => tra('Include a search form in file galleries'),
			'type' => 'flag',
			'default' => 'y',
		],
		'fgal_list_ratio_hits' => [
			'name' => tra('Display hits ratio to maximum'),
			'description' => tra('Display hits with a ratio of hits to maximum hits'),
			'type' => 'flag',
			'default' => 'n',
		],
		'fgal_display_properties' => [
			'name' => tra('Display properties in the context menu'),
			'type' => 'flag',
			'default' => 'y',
		],
		'fgal_display_replace' => [
			'name' => tra('Display "Replace" in the context menu'),
			'type' => 'flag',
			'default' => 'y',
		],
		'fgal_delete_after' => [
			'name' => tra('Automatic deletion of old files'),
			'description' => tra('The user will have an option when uploading a file to specify the time after which the file is deleted'),
			'type' => 'flag',
			'warning' => tra('A cron job must be set up in order to delete the files.'),
			'help' => 'File+Gallery+Config',
			'default' => 'n',
		],
		'fgal_checked' => [
			'name' => tra('Allow action on multiple files or galleries'),
			'description' => tra('Include "Remove" as an option for the checkbox action in file galleries'),
			'type' => 'flag',
			'help' => 'File+Gallery+Config',
			'default' => 'y',
		],
		'fgal_delete_after_email' => [
			'name' => tra('Deletion emails notification'),
			'description' => tra('Email addresses (comma-separated) to receive a copy of each deleted file'),
			'type' => 'text',
			'default' => '',
		],
		'fgal_keep_fileId' => [
			'name' => tra('Keep the same fileId for the latest version of a file'),
			'description' => tra('If the checkbox is checked, the file ID of the latest version of a file stays the same. A link to the file will always link to the latest version. If not checked, each version of the file is assigned its own file ID, so a link to the file will be to a specific version of the file'),
			'type' => 'flag',
			'default' => 'y',
		],
		'fgal_show_thumbactions' => [
			'name' => tra('Show thumbnail actions'),
			'description' => tra('Show the checkbox and wrench icon for file actions menu when not displaying details'),
			'type' => 'flag',
			'default' => 'y',
		],
		'fgal_thumb_max_size' => [
			'name' => tra('Maximum thumbnail size'),
			'description' => tra('Maximum width or height for image thumbnails'),
			'units' => tra('pixels'),
			'type' => 'text',
			'size' => 5,
			'default' => 120,
		],
		'fgal_enable_auto_indexing' => [
			'name' => tra('Automatic indexing of file content'),
			'description' => tra('Uses command line tools to extract the information from the files based on their MIME types.'),
			'default' => 'n',
			'type' => 'flag',
		],
		'fgal_asynchronous_indexing' => [
			'name' => tra('Asynchronous indexing'),
			'type' => 'flag',
			'default' => 'y',
		],
		'fgal_upload_from_source' => [
			'name' => tra('Upload files from remote source'),
			'description' => tra('Enable copying files to file galleries from a URL that will be polled for new revisions.'),
			'type' => 'flag',
			'default' => 'n',
			'tags' => ['advanced'],
			'dependencies' => ['fgal_keep_fileId'],
		],
		'fgal_source_refresh_frequency' => [
			'name' => tra('Remote source refresh frequency limit'),
			'description' => tra('Minimum number of seconds to elapse between remote source checks to prevent flooding the server with requests.'),
			'hint' => tr('Set to zero to disable refresh'),
			'type' => 'text',
			'filter' => 'int',
			'size' => 5,
			'units' => tra('seconds'),
			'default' => 3600,
		],
		'fgal_source_show_refresh' => [
			'name' => tra('Display controls to attempt a file refresh'),
			'description' => tra('Let users trigger a refresh attempt from the remote host.'),
			'type' => 'flag',
			'default' => 'n',
		],
		'fgal_tracker_existing_search' => [
			'name' => tra('Allow searching for existing files in the tracker files field'),
			'description' => tra('Search files using the search index.'),
			'type' => 'flag',
			'default' => 'y',
			'dependencies' => ['feature_search'],
		],
		'fgal_fix_mime_type' => [
			'name' => tra('Set MIME type based on file suffix'),
			'description' => tra('Sets the MIME type of an image file according to the file suffix when it is incorrectly detected as application/octet-stream'),
			'type' => 'flag',
			'default' => 'n',
			'tags' => ['experimental'],
			'help' => 'File+Gallery+config',
		],
		'fgal_clean_xml_always' => [
			'name' => tra('Clean XML Always'),
			'description' => tra('Sanitize XML based files such as SVG for all users.'),
			'type' => 'flag',
			'default' => 'y',
			'help' => 'File+Gallery+config',
			'permission' => [
				'textFilter' => 'upload_javascript',
			],
		],
		'fgal_allow_svg' => [
			'name' => tra('Allow SVG file upload'),
			'description' => tra('Because SVG files may contain malicious code and compromise system security, specifically grant permission to upload SVG files..'),
			'type' => 'flag',
			'default' => 'n',
			'help' => 'File+Gallery+config',
			'keywords' => 'svg upload',
			'permission' => [
				'textFilter' => 'upload_svg',
			],
		],
		'fgal_browse_name_max_length' => [
			'name' => tra('Maximum name length'),
			'description' => tra('Length to which to truncate file names in browse view.'),
			'type' => 'text',
			'filter' => 'int',
			'units' => tra('characters'),
			'size' => 5,
			'default' => 40,
		],
		'fgal_image_max_size_x' => [
			'name' => tra('Maximum width of images'),
			'description' => tra('Default maximum width of images in galleries.'),
			'type' => 'text',
			'filter' => 'int',
			'shorthint' => tr('0 for unlimited'),
			'units' => tra('pixels'),
			'size' => 5,
			'default' => 0,
		],
		'fgal_image_max_size_y' => [
			'name' => tra('Maximum height of images'),
			'description' => tra('Default maximum height of images in galleries.'),
			'type' => 'text',
			'filter' => 'int',
			'shorthint' => tr('0 for unlimited'),
			'units' => tra('pixels'),
			'size' => 5,
			'default' => 0,
		],
		'fgal_elfinder_feature' => [
			'name' => tra('Use elFinder UI'),
			'description' => tra('Alternative file manager with drag and drop capability'),
			'type' => 'flag',
			'filter' => 'alpha',
			'default' => 'n',
			'help' => 'elFinder',
			'dependencies' => ['feature_jquery_ui'],
		],
		'fgal_viewerjs_feature' => [
			'name' => tra('Use Viewer JS'),
			'description' => tra('Uses ViewerJS from http://viewerjs.org if available (needs a separate install due to licensing restrictions), and allows displaying ODF files (odt, ods, odp) as well as pdf in web pages'),
			'type' => 'flag',
			'filter' => 'alpha',
			'default' => 'n',
			'help' => 'ViewerJS',
			'tags' => ['deprecated'],
			'warning' => tra('This feature will be removed after Tiki18 and before Tiki19'),
		],
		'fgal_viewerjs_uri' => [
			'name' => tra('Viewer JS URI'),
			'description' => tra('Where ViewerJS is installed'),
			'type' => 'text',
			'filter' => 'url',
			'default' => 'files/viewerjs/ViewerJS/index.html',
			'help' => 'ViewerJS',
			'tags' => ['deprecated'],
			'dependencies' => ['fgal_viewerjs_feature'],
			'warning' => tra('This feature will be removed after Tiki18 and before Tiki19'),
		],
		'fgal_pdfjs_feature' => [
			'name' => tr('Use PDF.js'),
			'description' => tr('Uses PDF.js to display PDF files in web pages'),
			'type' => 'flag',
			'default' => 'n',
			'help' => 'PDF.js-viewer',
			'packages_required' => ['npm-asset/pdfjs-dist' => 'vendor/npm-asset/pdfjs-dist/build/pdf.js'],
		],
		'fgal_default_view' => [
			'name' => tra('Default view'),
			'type' => 'list',
			'options' => $defaultViews,
			'default' => 'list',
		],
		'fgal_sortField' => [
			'name' => tra('Default sort field'),
			'type' => 'list',
			'options' => [
				'created' => tra('Creation Date'),
				'name' => tra('Name'),
				'lastModif' => tra('Last modification date'),
				'hits' => tra('Hits'),
				'user' => tra('Owner'),
				'description' => tra('Description'),
				'id' => tra('ID'),
			],
			'default' => 'created',
		],
		'fgal_sortDirection' => [
			'name' => tra('Default sort direction'),
			'type' => 'radio',
			'options' => [
				'desc' => tra('Descending'),
				'asc' => tra('Ascending'),
			],
			'default' => 'desc',
		],
		'fgal_icon_fileId' => [
			'name' => tra('Gallery icon'),
			'description' => tra('Enter the ID of any file in any gallery to be used as the icon for this gallery in browse view'),
			'type' => 'text',
			'filter' => 'digits',
			'default' => '',
		],
		'fgal_show_explorer' => [
			'name' => tra('Show explorer'),
			'type' => 'flag',
			'default' => 'y',
		],
		'fgal_show_path' => [
			'name' => tra('Show path'),
			'type' => 'flag',
			'default' => 'y',
		],
		'fgal_show_slideshow' => [
			'name' => tra('Show slideshow'),
			'type' => 'flag',
			'default' => 'n',
		],
		'fgal_list_id' => [
			'name' => tra('ID'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'o',
		],
		'fgal_list_type' => [
			'name' => tra('Type'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'y',
		],
		'fgal_list_name' => [
			'name' => tra('Name'),
			'type' => 'list',
			'options' => [
				'a' => tra('Name-filename'),
				'n' => tra('Name only'),
				'f' => tra('Filename only'),
			],
			'default' => 'n',
		],
		'fgal_list_description' => [
			'name' => tra('Description'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'o',
		],
		'fgal_list_size' => [
			'name' => tra('Size'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'y',
		],
		'fgal_list_created' => [
			'name' => tra('Created / Uploaded'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'o',
		],
		'fgal_list_lastModif' => [
			'name' => tra('Last modified'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'y',
		],
		'fgal_list_creator' => [
			'name' => tra('Uploaded by'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'o',
		],
		'fgal_list_author' => [
			'name' => tra('Creator'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'o',
		],
		'fgal_list_last_user' => [
			'name' => tra('Last modified by'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'o',
		],
		'fgal_list_comment' => [
			'name' => tra('Comment'),

			'type' => 'list',
			'options' => $showOptions,
			'default' => 'o',
		],
		'fgal_list_files' => [
			'name' => tra('Files'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'o',
		],
		'fgal_list_hits' => [
			'name' => tra('Hits'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'o',
			'dependencies' => ['feature_stats'],
		],
		'fgal_list_lastDownload' => [
			'name' => tra('Last download'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'n',
		],
		'fgal_list_lockedby' => [
			'name' => tra('Locked by'),
			'type' => 'list',
			'options' => $showOptions + ['i' => tra('Show icon in column')],
			'default' => 'a',
		],
		'fgal_list_backlinks' => [
			'name' => tra('Backlinks'),
			'description' => tra('Present a list of pages that link to the current page.'),
			'type' => 'list',
			'help' => 'Backlinks',
			'options' => $showOptions,
			'default' => 'n',
		],
		'fgal_list_deleteAfter' => [
			'name' => tra('Delete after'),
			'type' => 'list',
			'options' => [
				'n' => tra('Hide'),
				'y' => tra('Show as a column'),
			],
			'default' => 'n',
		],
		'fgal_list_share' => [
			'name' => tra('Share'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'n',
		],
		'fgal_list_source' => [
			'name' => tra('Source'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'n',
		],
		'fgal_list_id_admin' => [
			'name' => tra('ID'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'y',
		],
		'fgal_list_type_admin' => [
			'name' => tra('Type'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'y',
		],
		'fgal_list_name_admin' => [
			'name' => tra('Name'),
			'type' => 'list',
			'options' => [
				'a' => tra('Name-filename'),
				'n' => tra('Name only'),
				'f' => tra('Filename only'),
			],
			'default' => 'n',
		],
		'fgal_list_description_admin' => [
			'name' => tra('Description'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'o',
		],
		'fgal_list_size_admin' => [
			'name' => tra('Size'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'y',
		],
		'fgal_list_created_admin' => [
			'name' => tra('Created / Uploaded'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'o',
		],
		'fgal_list_lastModif_admin' => [
			'name' => tra('Last modified'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'y',
		],
		'fgal_list_creator_admin' => [
			'name' => tra('Uploaded by'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'o',
		],
		'fgal_list_author_admin' => [
			'name' => tra('Creator'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'o',
		],
		'fgal_list_last_user_admin' => [
			'name' => tra('Last modified by'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'o',
		],
		'fgal_list_comment_admin' => [
			'name' => tra('Comment'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'o',
		],
		'fgal_list_files_admin' => [
			'name' => tra('Files'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'o',
		],
		'fgal_list_hits_admin' => [
			'name' => tra('Hits'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'o',
		],
		'fgal_list_lastDownload_admin' => [
			'name' => tra('Last download'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'n',
		],
		'fgal_list_lockedby_admin' => [
			'name' => tra('Locked by'),
			'type' => 'list',
			'options' => $showOptions + ['i' => tra('Show icon in column')],
			'default' => 'n',
		],
		'fgal_list_backlinks_admin' => [
			'name' => tra('Backlinks'),
			'description' => tra('Present a list of pages that link to the current page.'),
			'help' => 'Backlinks',
			'type' => 'list',
			'options' => $showOptions,
			'default' => 'y',
		],
		'fgal_list_deleteAfter_admin' => [
			'name' => tra('Delete after'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => '',
		],
		'fgal_list_share_admin' => [
			'name' => tra('Share'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => '',
		],
		'fgal_list_source_admin' => [
			'name' => tra('Source'),
			'type' => 'list',
			'options' => $showOptions,
			'default' => '',
		],
		'fgal_convert_documents_pdf' => [
			'name' => tra('View or export office documents as PDF'),
			'description' => tra('If enabled allows to view documents without download or to export documents as PDF files'),
			'type' => 'flag',
			'keywords' => 'convert files documents pdf',
			'default' => 'n',
			'tags' => ['basic'],
			'packages_required' => ['media-alchemyst/media-alchemyst' => 'Unoconv\Unoconv'],
		],
	];
}
