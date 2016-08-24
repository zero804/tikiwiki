/**
 * Uyghur translation
 * @author Alim.Boyaq <boyaq@otkur.biz>
 * @version 2014-12-19
 */
if (elFinder && elFinder.prototype && typeof(elFinder.prototype.i18) == 'object') {
	elFinder.prototype.i18.ug_CN = {
		translator : 'تەرجىمە قىلغۇچى:  ئۆتكۈر بىز شىركىتى info@otkur.biz',
		language   : 'ئ‍ۇيغۇرچە',
		direction  : 'rtl',
		dateFormat : 'Y-m-d H:i',
		fancyDateFormat : '$1 H:i',
		messages   : {

			/********************************** errors **********************************/
			'error'                : 'خاتالىق',
			'errUnknown'           : 'كۈتۈلمىگەن خاتالىقكەن.',
			'errUnknownCmd'        : 'كۈتۈلمىگەن بۇيرۇقكەن.',
			'errJqui'              : 'jQuery UI تەڭشىكى توغرا بولمىغان. چوقۇم Selectable، draggable، droppabl قاتارلىق بۆلەكلەر بولۇشى كېرەك.',
			'errNode'              : 'elFinder DOM ئېلىمىنتلىرىنى قۇرالىشى كېرەك.',
			'errURL'               : 'elFinder تەڭشىكى توغرا بولمىغان! URL تەڭشىكى يېزىلمىغان.',
			'errAccess'            : 'زىيارەت قىلىش چەكلەنگەن.',
			'errConnect'           : 'ئارقا سۇپىغا ئۇلاش مەغلۇپ بولدى..',
			'errAbort'             : 'ئارقا سۇپىغا توختىتىلدى.',
			'errTimeout'           : 'ئارقا سۇپىغا بەلگىلەنگەن ۋاقىتتا ئۇلىيالمىدى.',
			'errNotFound'          : 'ئارقا سۇپا تېپىلمىدى.',
			'errResponse'          : 'ئارقا سۇپىدىن توغرا بولمىغان ئىنكاس قايتتى.',
			'errConf'              : 'ئارقا سۇپا تەڭشىكى توغرا ئەمەس.',
			'errJSON'              : 'PHP JSON بۆلىكى قاچىلانمىغان.',
			'errNoVolumes'         : 'ئوقۇشقا بولىدىغان ھۈججەت خالتىسى يوق.',
			'errCmdParams'         : 'پارامېتىر خاتا، بۇيرۇق: "$1".',
			'errDataNotJSON'       : 'ئارقا سۇپا قايتۇرغان سانلىق مەلۇمات توغرا بولغان JSON ئەمەسكەن.',
			'errDataEmpty'         : 'ئارقا سۇپا قايتۇرغان سانلىق مەلۇمات قۇرۇقكەن.',
			'errCmdReq'            : 'ئارقا سۇپىدىكى بۇيرۇقنىڭ ئ‍سىمى تەمىنلىنىشى كېرەك.',
			'errOpen'              : '"$1"نى ئاچالمىدى.',
			'errNotFolder'         : 'ئوبىكىت مۇندەرىجە ئەمەسكەن.',
			'errNotFile'           : 'ئوبىكىت ھۈججەت ئەمەسكەن.',
			'errRead'              : '"$1"نى ئوقۇيالمىدى.',
			'errWrite'             : '"$1"نى يازالمىدى.',
			'errPerm'              : 'ھوقۇق يوق.',
			'errLocked'            : '"$1" تاقالغان,ئۆزگەرتەلمەيسىز.',
			'errExists'            : '"$1" ناملىق ھۈججەت باركەن.',
			'errInvName'           : 'توغرا بولمىغان ھۈججەت قىسقۇچ ئىسمى.',
			'errFolderNotFound'    : 'ھۈججەت قىسقۇچنى تاپالمىدى.',
			'errFileNotFound'      : 'ھۈججەتنى تاپالمىدى.',
			'errTrgFolderNotFound' : '"$1" ناملىق ھۈججەت قىسقۇچنى تاپالمىدى.',
			'errPopup'             : 'سەكرەپ چىققان يېڭى بەتنى تور كۆرگۈچ كۆرسەتمىدى، ئۈستىدىكى ئەسكەرتىشتىن تور كۆرگۈچنى كۆرسىتىشكە تەڭشەڭ.',
			'errMkdir'             : '"$1" ناملىق ھۈججەت قىسقۇچنى قۇرالمىدى.',
			'errMkfile'            : '"$1" ناملىق ھۈججەتنى قۇرالمىدى.',
			'errRename'            : '"$1" ناملىق ھۈججەتنىڭ ئىسمىنى يېڭىلاش مەغلۇپ بولدى.',
			'errCopyFrom'          : ' "$1" ناملىق ئورۇندىن ھۈججەت كۆچۈرۈش چەكلەنگەن.',
			'errCopyTo'            : '"$1" ناملىق ئورۇنغا ھۈججەت كۆچۈرۈش چەكلەنگەن.',
			'errUpload'            : 'يۈكلەشتە خاتالىق كۆرۈلدى.',
			'errUploadFile'        : '"$1" ناملىق ھۈججەتنى يۈكلەشتە خاتالىق كۆرۈلدى.',
			'errUploadNoFiles'     : 'يۈكلىمەكچى بولغان ھۈججەت تېپىلمىدى.',
			'errUploadTotalSize'   : 'سانلىق مەلۇمات چوڭلىقى چەكلىمىدىن ئېشىپ كەتكەن..',
			'errUploadFileSize'    : 'ھۈججەت چوڭلىقى چەكلىمىدىن ئېشىپ كەتكەن..',
			'errUploadMime'        : 'چەكلەنگەن ھۈججەت شەكلى.',
			'errUploadTransfer'    : '"$1" ناملىق ھۈججەتنى يوللاشتا خاتالىق كۆرۈلدى.',
			'errNotReplace'        : '"$1" ناملىق ھۈججەت باركەن، ئالماشتۇرۇشقا بولمايدۇ.', // new
			'errReplace'           : '"$1" ناملىق ھۈججەتنى ئالماشتۇرۇش مەغلۇپ بولدى.',
			'errSave'              : '"$1" ناملىق ھۈججەتنى ساقلاش مەغلۇپ بولدى.',
			'errCopy'              : '"$1" ناملىق ھۈججەتنى كۆچۈرۈش مەغلۇپ بولدى.',
			'errMove'              : '"$1" ناملىق ھۈججەتنى يۆتكەش مەغلۇپ بولدى.',
			'errCopyInItself'      : '"$1" ناملىق ھۈججەتنى ئەسلى ئورنىغا يۆتكەش مەغلۇپ بولدى.',
			'errRm'                : '"$1" ناملىق ھۈججەتنى ئۆچۈرۈش مەغلۇپ بولدى.',
			'errRmSrc'             : 'ئەسلى ھۈججەتنى ئۆچۈرۈش مەغلۇپ بولدى.',
			'errExtract'           : ' "$1" ناملىق مەلۇماتتىن ھۈججەت ئايرىش مەغلۇپ بولدى..',
			'errArchive'           : 'پىرىسلانغان ھۈججەت ھاسىللاش مەغلۇپ بولدى.',
			'errArcType'           : 'بۇ خىل پىرىسلانغان ھۈججەت شەكلىنى سىستېما بىر تەرەپ قىلالمىدى.',
			'errNoArchive'         : 'ھۈججەت پىرىسلانغان ھۈججەت ئەمەس، ياكى توغرا پىرىسلانمىغان.',
			'errCmdNoSupport'      : 'بۇ خىل بۇيرۇقنى بىر تەرەپ قىلالمىدى.',
			'errReplByChild'       : '“$1” ناملىق ھۈججەت قىسقۇچنى ئالماشۇتۇرۇشقا بولمايدۇ.',
			'errArcSymlinks'       : 'بىخەتەرلىك ئۈچۈن بۇ مەشغۇلات ئەمەلدىن قالدۇرۇلدى..',
			'errArcMaxSize'        : 'پىرىسلانغان ھۈججەتنىڭ چوڭلىقى چەكلىمىدىن ئېشىپ كەنكەن.',
			'errResize'            : ' "$1" چوڭلۇقنى تەڭشەشكە بولمىدى.',
			'errResizeDegree'      : 'توغرا بولمىغان پىقىرىتىش گىرادۇسى',
			'errResizeRotate'      : 'رەسىمنى پىقىرىتىشقا بولمىدى.',
			'errResizeSize'        : 'توغرا بولمىغان رەسىم چوڭلىقى.',
			'errResizeNoChange'    : 'رەسىم چوڭلىقى ئۆزگەرمىگەن.',
			'errUsupportType'      : 'قوللىمايدىغان ھۈججەت شەكلى.',
			'errNotUTF8Content'    : '"$1" ناملىق ھۈججەتنىڭ كودى  UTF-8ئەمەسكەن،  تەھرىرلىگىلى بولمايدۇ.',  // added 9.11.2011
			'errNetMount'          : ' "$1" نى يۈكلەشتە خاتلىق يۈز بەردى..', // added 17.04.2012
			'errNetMountNoDriver'  : 'بۇ خىل پروتوكول قوللانمىدى..',     // added 17.04.2012
			'errNetMountFailed'    : 'يۈكلەش مەغلۇپ بولدى.',         // added 17.04.2012
			'errNetMountHostReq'   : 'مۇلازىمىتىرنى كۆرسىتىپ بېرىڭ.', // added 18.04.2012
			'errSessionExpires'    : 'Your session has expired due to inactivity.',
			'errCreatingTempDir'   : 'Unable to create temporary directory: "$1"',
			'errFtpDownloadFile'   : 'Unable to download file from FTP: "$1"',
			'errFtpUploadFile'     : 'Unable to upload file to FTP: "$1"',
			'errFtpMkdir'          : 'Unable to create remote directory on FTP: "$1"',
			'errArchiveExec'       : 'Error while archiving files: "$1"',
			'errExtractExec'       : 'Error while extracting files: "$1"',

			/******************************* commands names ********************************/
			'cmdarchive'   : 'پىرىسلاش',
			'cmdback'      : 'قايتىش',
			'cmdcopy'      : 'كۆچۈرۈش',
			'cmdcut'       : 'كېسىش',
			'cmddownload'  : 'چۈشۈرۈش',
			'cmdduplicate' : 'نۇسخىلاش',
			'cmdedit'      : 'تەھرىرلەش',
			'cmdextract'   : 'پىرىستىن ھۈججەت چىقىرىش',
			'cmdforward'   : 'ئ‍الدىغا مېڭىش',
			'cmdgetfile'   : 'تاللاش',
			'cmdhelp'      : 'ئەپ ھەققىدە',
			'cmdhome'      : 'باش بەت',
			'cmdinfo'      : 'ئۇچۇرلىرى',
			'cmdmkdir'     : 'يېڭى ھۈججەت قىسقۇچ',
			'cmdmkfile'    : 'يېڭى ھۈججەت',
			'cmdopen'      : 'ئېچىش',
			'cmdpaste'     : 'چاپلاش',
			'cmdquicklook' : 'كۆرۈش',
			'cmdreload'    : 'يېڭىلاش',
			'cmdrename'    : 'نام يېڭىلاش',
			'cmdrm'        : 'ئۆچۈرۈش',
			'cmdsearch'    : 'ھۈججەت ئىزدەش',
			'cmdup'        : 'ئالدىنقى مۇندەرىجىگە بېرىش',
			'cmdupload'    : 'يۈكلەش',
			'cmdview'      : 'كۆرۈش',
			'cmdresize'    : 'چوڭلىقىنى تەڭشەش',
			'cmdsort'      : 'تەرتىپ',
			'cmdnetmount'  : 'توردىن قوشۇش', // added 18.04.2012

			/*********************************** buttons ***********************************/
			'btnClose'  : 'تاقاش',
			'btnSave'   : 'ساقلاش',
			'btnRm'     : 'ئۆچۈرۈش',
			'btnApply'  : 'ئىشلىتىش',
			'btnCancel' : 'بېكارلاش',
			'btnNo'     : 'ياق',
			'btnYes'    : 'ھەئە',
			'btnMount'  : 'يۈكلەش',  // added 18.04.2012

			/******************************** notifications ********************************/
			'ntfopen'     : 'قىسقۇچنى ئېچىش',
			'ntffile'     : 'ھۈججەتنى ئېچىش',
			'ntfreload'   : 'يېڭىلاش',
			'ntfmkdir'    : 'قىسقۇچ قۇرۇش',
			'ntfmkfile'   : 'ھۈججەت قۇرۇش',
			'ntfrm'       : 'ئۆچۈرۈش',
			'ntfcopy'     : 'كۆچۈرۈش',
			'ntfmove'     : 'يۆتكەش',
			'ntfprepare'  : 'كۆچۈرۈش تەييارلىقى',
			'ntfrename'   : 'نام يېڭىلاش',
			'ntfupload'   : 'يۈكلەش',
			'ntfdownload' : 'چۈشۈرۈش',
			'ntfsave'     : 'ساقلاش',
			'ntfarchive'  : 'پىرىسلاش',
			'ntfextract'  : 'پىرىستىن يېشىش',
			'ntfsearch'   : 'ئىزدەش',
			'ntfresize'   : 'چوڭلىقى ئۆزگەرتىلىۋاتىدۇ',
			'ntfsmth'     : 'ئالدىراش >_<',
			'ntfloadimg'  : 'رەسىم ئېچىلىۋاتىدۇ',
      		'ntfnetmount' : 'تور ھۈججىتى يۈكلىنىۋاتىدۇ', // added 18.04.2012
			'ntfdim'      : 'Acquiring image dimension',

			/************************************ dates **********************************/
			'dateUnknown' : 'ئېنىق ئەمەس',
			'Today'       : 'بۈگۈن',
			'Yesterday'   : 'تۆنۈگۈن',
			'msJan'       : '1-ئاي',
			'msFeb'       : '2-ئاي',
			'msMar'       : '3-ئاي',
			'msApr'       : '4-ئاي',
			'msMay'       : '5-ئاي',
			'msJun'       : '6-ئاي',
			'msJul'       : '7-ئاي',
			'msAug'       : '8-ئاي',
			'msSep'       : '9-ئ‍اي',
			'msOct'       : '10-ئاي',
			'msNov'       : '11-ئاي',
			'msDec'       : '12-ئاي',
			'January'     : '1-ئاي',
			'February'    : '2-ئاي',
			'March'       : '3-ئاي',
			'April'       : '4-ئاي',
			'May'         : '5-ئاي',
			'June'        : '6-ئاي',
			'July'        : '7-ئاي',
			'August'      : '8-ئاي',
			'September'   : '9-ئاي',
			'October'     : '10-ئاي',
			'November'    : '11-ئاي',
			'December'    : '12-ئاي',
			'Sunday'      : 'يەكشەنبە',
			'Monday'      : 'دۈشەنبە',
			'Tuesday'     : 'سەيشەنبە',
			'Wednesday'   : 'چارشەنبە',
			'Thursday'    : 'پەيشەنبە',
			'Friday'      : 'جۈمە',
			'Saturday'    : 'شەنبە',
			'Sun'         : 'يە',
			'Mon'         : 'دۈ',
			'Tue'         : 'سە',
			'Wed'         : 'چا',
			'Thu'         : 'پە',
			'Fri'         : 'جۈ',
			'Sat'         : 'شە',

			/******************************** sort variants ********************************/
			'sortname'          : 'نامى ',
			'sortkind'          : 'شەكلى ',
			'sortsize'          : 'چوڭلىقى',
			'sortdate'          : 'ۋاقتى',
			'sortFoldersFirst'  : 'قىسقۇچلار باشتا',

			/********************************** messages **********************************/
			'confirmReq'      : 'مۇقىملاشتۇرۇڭ',
			'confirmRm'       : 'راستىنلا ئۆچۈرەمسىز?<br/>كەينىگە قايتۇرغىلى بولمايدۇ!',
			'confirmRepl'     : 'ھازىرقى ھۈججەت بىلەن كونىسىنى ئالماشتۇرامسىز?',
			'apllyAll'        : 'ھەممىسىگە ئىشلىتىش',
			'name'            : 'نامى',
			'size'            : 'چوڭلىقى',
			'perms'           : 'ھوقۇق',
			'modify'          : 'ئۆزگەرگەن ۋاقتى',
			'kind'            : 'تۈرى',
			'read'            : 'ئوقۇش',
			'write'           : 'يېزىش',
			'noaccess'        : 'ھوقۇق يوق',
			'and'             : 'ھەم',
			'unknown'         : 'ئېنىق ئەمەس',
			'selectall'       : 'ھەممىنى تاللاش',
			'selectfiles'     : 'تاللاش',
			'selectffile'     : 'بىرىنچىسىنى تاللاش',
			'selectlfile'     : 'ئەڭ ئاخىرقىسىنى تاللاش',
			'viewlist'        : 'جەدۋەللىك كۆرىنىشى',
			'viewicons'       : 'رەسىملىك كۆرىنىشى',
			'places'          : 'ئورنى',
			'calc'            : 'ھېسابلاش',
			'path'            : 'ئورنى',
			'aliasfor'        : 'باشقا نامى',
			'locked'          : 'تاقالغان',
			'dim'             : 'چوڭلىقى',
			'files'           : 'ھۈججەت',
			'folders'         : 'قىسقۇچ',
			'items'           : 'تۈرلەر',
			'yes'             : 'ھەئە',
			'no'              : 'ياق',
			'link'            : 'ئۇلىنىش',
			'searcresult'     : 'ئىزدەش نەتىجىسى',
			'selected'        : 'تاللانغان تۈرلەر',
			'about'           : 'چۈشەنچە',
			'shortcuts'       : 'تېز كونۇپكىلار',
			'help'            : 'ياردەم',
			'webfm'           : 'تور ھۈججەتلىرىنى باشقۇرۇش',
			'ver'             : 'نەشرى',
			'protocolver'     : 'پروتوكول نەشرى',
			'homepage'        : 'تۈر باش بېتى',
			'docs'            : 'ھۈججەت',
			'github'          : 'Fork us on Github',
			'twitter'         : 'Follow us on twitter',
			'facebook'        : 'Join us on facebook',
			'team'            : 'گۇرۇپپا',
			'chiefdev'        : 'باش پىروگراممىر',
			'developer'       : 'پىروگراممىر',
			'contributor'     : 'تۆھپىكار',
			'maintainer'      : 'ئاسرىغۇچى',
			'translator'      : 'تەرجىمان',
			'icons'           : 'سىنبەلگە',
			'dontforget'      : 'تەرىڭىزنى سۈرتىدىغان قولياغلىقىڭىزنى ئۇنۇتماڭ جۇمۇ',
			'shortcutsof'     : 'تېز كونۇپكىلار چەكلەنگەن',
			'dropFiles'       : 'ھۈججەتنى موشۇ يەرگە تاشلاڭ',
			'or'              : 'ياكى',
			'selectForUpload' : 'يۈكلىمەكچى بولغان ھۈججەتنى تاللاڭ',
			'moveFiles'       : 'يۆتكەش',
			'copyFiles'       : 'كۆچۈرۈش',
			'rmFromPlaces'    : 'ھۈججەتلەرنى ئۆچۈرۈش',
			'aspectRatio'     : 'نىسبىتىنى ساقلاش',
			'scale'           : 'نىسبىتى',
			'width'           : 'ئۇزۇنلىقى',
			'height'          : 'ئىگىزلىكى',
			'resize'          : 'چوڭلىقىنى تەڭشەش',
			'crop'            : 'كېسىش',
			'rotate'          : 'پىقىرىتىش',
			'rotate-cw'       : 'سائەت ئىستىرىلكىسى بويىچە 90 گىرادۇس پىقىرىتىش',
			'rotate-ccw'      : 'سائەت ئىستىرىلكىسىنى تەتۈر يۆنىلىشى بويىچە 90گىرادۇس پىقىرىتىش',
			'degree'          : 'گىرادۇس',
			'netMountDialogTitle' : 'Mount network volume', // added 18.04.2012
			'protocol'            : 'پىروتوكڭل', // added 18.04.2012
			'host'                : 'مۇلازىمىتىر', // added 18.04.2012
			'port'            : 'پورت', // added 18.04.2012
			'user'            : 'ئەزا', // added 18.04.2012
			'pass'            : 'ئىم', // added 18.04.2012

			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'ئېنىق ئەمەس',
			'kindFolder'      : 'ھۈججەت قىسقۇچ',
			'kindAlias'       : 'باشقا نامى',
			'kindAliasBroken' : 'باشقا نامى خاتا',
			// applications
			'kindApp'         : 'كود ھۈججىتى',
			'kindPostscript'  : 'Postscript ھۈججىتى',
			'kindMsOffice'    : 'Microsoft Office ھۈججىتى',
			'kindMsWord'      : 'Microsoft Word ھۈججىتى',
			'kindMsExcel'     : 'Microsoft Excel ھۈججىتى',
			'kindMsPP'        : 'Microsoft Powerpoint ھۈججىتى',
			'kindOO'          : 'Open Office ھۈججىتى',
			'kindAppFlash'    : 'Flash ھۈججىتى',
			'kindPDF'         : 'Portable Document Format (PDF)',
			'kindTorrent'     : 'Bittorrent ھۈججىتى',
			'kind7z'          : '7z ھۈججىتى',
			'kindTAR'         : 'TAR ھۈججىتى',
			'kindGZIP'        : 'GZIP ھۈججىتى',
			'kindBZIP'        : 'BZIP ھۈججىتى',
			'kindXZ'          : 'XZ ھۈججىتى',
			'kindZIP'         : 'ZIP ھۈججىتى',
			'kindRAR'         : 'RAR ھۈججىتى',
			'kindJAR'         : 'Java JAR ھۈججىتى',
			'kindTTF'         : 'True Type فونت',
			'kindOTF'         : 'Open Type فونت',
			'kindRPM'         : 'RPM',
			// texts
			'kindText'        : 'تېكىست',
			'kindTextPlain'   : 'تېكىست',
			'kindPHP'         : 'PHP ھۈججىتى',
			'kindCSS'         : 'CSS ھۈججىتى',
			'kindHTML'        : 'HTML ھۈججىتى',
			'kindJS'          : 'Javascript ھۈججىتى',
			'kindRTF'         : 'RTF ھۈججىتى',
			'kindC'           : 'C ھۈججىتى',
			'kindCHeader'     : 'C باش ھۈججىتى',
			'kindCPP'         : 'C++ ھۈججىتى',
			'kindCPPHeader'   : 'C++ باش ھۈججىتى',
			'kindShell'       : 'Unix سىكىرىپت ھۈججىتى',
			'kindPython'      : 'Python ھۈججىتى',
			'kindJava'        : 'Java ھۈججىتى',
			'kindRuby'        : 'Ruby ھۈججىتى',
			'kindPerl'        : 'Perl ھۈججىتى',
			'kindSQL'         : 'SQL ھۈججىتى',
			'kindXML'         : 'XML ھۈججىتى',
			'kindAWK'         : 'AWK ھۈججىتى',
			'kindCSV'         : 'CSV ھۈججىتى',
			'kindDOCBOOK'     : 'Docbook XML ھۈججىتى',
			// images
			'kindImage'       : 'رەسىم',
			'kindBMP'         : 'BMP رەسىم',
			'kindJPEG'        : 'JPEG رەسىم',
			'kindGIF'         : 'GIF رەسىم',
			'kindPNG'         : 'PNG رەسىم',
			'kindTIFF'        : 'TIFF رەسىم',
			'kindTGA'         : 'TGA رەسىم',
			'kindPSD'         : 'Adobe Photoshop رەسىم',
			'kindXBITMAP'     : 'X bitmap رەسىم',
			'kindPXM'         : 'Pixelmator رەسىم',
			// media
			'kindAudio'       : 'ئاۋاز',
			'kindAudioMPEG'   : 'MPEG ئاۋاز',
			'kindAudioMPEG4'  : 'MPEG-4 ئاۋاز',
			'kindAudioMIDI'   : 'MIDI ئاۋاز',
			'kindAudioOGG'    : 'Ogg Vorbis ئاۋاز',
			'kindAudioWAV'    : 'WAV ئاۋاز',
			'AudioPlaylist'   : 'MP3 قويۇش تىزىملىكى',
			'kindVideo'       : 'سىن',
			'kindVideoDV'     : 'DV سىن',
			'kindVideoMPEG'   : 'MPEG سىن',
			'kindVideoMPEG4'  : 'MPEG-4 سىن',
			'kindVideoAVI'    : 'AVI سىن',
			'kindVideoMOV'    : 'Quick Time سىن',
			'kindVideoWM'     : 'Windows Media سىن',
			'kindVideoFlash'  : 'Flash سىن',
			'kindVideoMKV'    : 'Matroska سىن',
			'kindVideoOGG'    : 'Ogg سىن'
		}
	};
}
