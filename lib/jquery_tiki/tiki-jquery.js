// $Id$
// JavaScript glue for JQuery (1.3.2) in TikiWiki (3.0+)

var $jq = jQuery.noConflict();

// Check / Uncheck all Checkboxes - overriden from tiki-js.js
function switchCheckboxes (tform, elements_name, state) {
  // checkboxes need to have the same name elements_name
  // e.g. <input type="checkbox" name="my_ename[]">, will arrive as Array in php.
	$jq(tform).contents().find('input[name="' + elements_name + '"]:visible').attr('checked', state).change();
}


// override existing show/hide routines here

// add id's of any elements that don't like being animated here
var jqNoAnimElements = ['help_sections', 'ajaxLoading'];

function show(foo, f, section) {
	if ($jq.inArray(foo, jqNoAnimElements) > -1 || typeof jqueryTiki === 'undefined') {		// exceptions that don't animate reliably
		$jq("#" + foo).show();
	} else if ($jq("#" + foo).hasClass("tabcontent")) {		// different anim prefs for tabs
		showJQ("#" + foo, jqueryTiki.effect_tabs, jqueryTiki.effect_tabs_speed, jqueryTiki.effect_tabs_direction);
	} else {
		showJQ("#" + foo, jqueryTiki.effect, jqueryTiki.effect_speed, jqueryTiki.effect_direction);
	}
	if (f) { setCookie(foo, "o", section); }
}

function hide(foo, f, section) {
	if ($jq.inArray(foo, jqNoAnimElements) > -1 || typeof jqueryTiki === 'undefined') {		// exceptions
		$jq("#" + foo).hide();
	} else if ($jq("#" + foo).hasClass("tabcontent")) {
		hideJQ("#" + foo, jqueryTiki.effect_tabs, jqueryTiki.effect_tabs_speed, jqueryTiki.effect_tabs_direction);
	} else {
		hideJQ("#" + foo, jqueryTiki.effect, jqueryTiki.effect_speed, jqueryTiki.effect_direction);
	}
	if (f) {
//		var wasnot = getCookie(foo, section, 'x') == 'x';
		setCookie(foo, "c", section);
//		if (wasnot) {
//			history.go(0);	// used to reload the page with all menu items closed - broken since 3.x
//		}
	}
}

// flip function... unfortunately didn't use show/hide (ay?)
function flip(foo, style) {
	if (style && style !== 'block' || foo === 'help_sections' || foo === 'fgalexplorer' || typeof jqueryTiki === 'undefined') {	// TODO find a better way?
		$jq("#" + foo).toggle();	// inlines don't animate reliably (yet) (also help)
		if ($jq("#" + foo).css('display') === 'none') {
			setSessionVar('show_' + escape(foo), 'n');
		} else {
			setSessionVar('show_' + escape(foo), 'y');
		}
	} else {
		if ($jq("#" + foo).css("display") === "none") {
			setSessionVar('show_' + escape(foo), 'y');
			showJQ("#" + foo, jqueryTiki.effect, jqueryTiki.effect_speed, jqueryTiki.effect_direction);
		}
		else {
			setSessionVar('show_' + escape(foo), 'n');
			hideJQ("#" + foo, jqueryTiki.effect, jqueryTiki.effect_speed, jqueryTiki.effect_direction);
		}
	}
}

// handle JQ effects
function showJQ(selector, effect, speed, dir) {
	if (effect === 'none') {
		$jq(selector).show();
	} else if (effect === '' || effect === 'normal') {
		$jq(selector).show(400);	// jquery 1.4 no longer seems to understand 'nnormal' as a speed
	} else if (effect === 'slide') {
		$jq(selector).slideDown(speed);
	} else if (effect === 'fade') {
		$jq(selector).fadeIn(speed);
	} else if (effect.match(/(.*)_ui$/).length > 1) {
		$jq(selector).show(effect.match(/(.*)_ui$/)[1], {direction: dir }, speed);
	} else {
		$jq(selector).show();
	}
}

function hideJQ(selector, effect, speed, dir) {
	if (effect === 'none') {
		$jq(selector).hide();
	} else if (effect === '' || effect === 'normal') {
		$jq(selector).hide(400);	// jquery 1.4 no longer seems to understand 'nnormal' as a speed
	} else if (effect === 'slide') {
		$jq(selector).slideUp(speed);
	} else if (effect === 'fade') {
		$jq(selector).fadeOut(speed);
	} else if (effect.match(/(.*)_ui$/).length > 1) {
		$jq(selector).hide(effect.match(/(.*)_ui$/)[1], {direction: dir }, speed);
	} else {
		$jq(selector).hide();
	}
}

// override overlib
function convertOverlib(element, tip, params) {	// process modified overlib event fn to cluetip from {popup} smarty func
	
	if (element.processed) { return false; }
	if (typeof params == "undefined") { params = []; }
	
	var options = {};
	options.clickThrough = true;
	for (var param = 0; param < params.length; param++) {
		var val = "";
		var i = params[param].indexOf("=");
		if (i > -1) {
			var arr = params[param].split("=", 2);
			pam = params[param].substring(0, i).toLowerCase();
			val = params[param].substring(i+1);
		} else {
			pam = params[param].toLowerCase();
		}
		switch (pam) {
			case "sticky":
				options.sticky = true;
				break;
			case "fullhtml":
				options.cluetipClass = 'fullhtml';
				break;
			case "background":
				options.cluetipClass = 'fullhtml';
				tip = '<div style="background-image: url(' + val + '); height:' + options.height + 'px">' + tip + '</div>';
				break;
			case "onclick":
				options.activation = 'click';
				options.clickThrough = false;
				break;
			case "width":
				options.width = val;
				break;
			case "height":
				options.height = val;
				break;
			default:
				break;
		}
	}
	
	options.splitTitle = '|';
	options.showTitle = false;
	options.cluezIndex = 400;
	options.dropShadow = true;
	options.fx = {open: 'fadeIn', openSpeed: 'fast'};
	options.closeText = 'x';
	options.closePosition = 'title';
	options.mouseOutClose = true;
	//options.positionBy = 'mouse';	// TODO - add a param for this one if desired
	
	// attach new tip
	
	if (element.tipWidth) {
		options.width = element.tipWidth;
	} else if (!options.width || options.width === 'auto') {
		// hack to calculate div width
		var $el = $jq("<div />")
			.css('display', 'none')
			.insertBefore("#main")
			.html(tip);
		
		if ($el.width() > $jq(window).width()) {
			$el.width($jq(window).width() * 0.8);
		}
		options.width = $el.width();
		$jq(document.body).remove($el[0]);
		
		element.tipWidth = options.width;
	}
	
	prefix = "|";
	$jq(element).attr('title', prefix + tip);
	
	element.processed = true;
	
	//options.sticky = true; //useful for css work
	$jq(element).cluetip(options);

	if (options.activation === 'click') {
		$jq(element).trigger('click');
	} else {
		$jq(element).trigger('mouseover');
	}
	setTimeout(function () { $jq("#cluetip").show(); }, 200);	// IE doesn't necessarily display
	$jq(element).attr("title", "");	// remove temporary title attribute to avoid built in browser tips
	return false;
}

function nd() {
	$jq("#cluetip").hide();
}


$jq(document).ready( function() { // JQuery's DOM is ready event - before onload
	
	// tooltip functions and setup
	if (jqueryTiki.tooltips) {	// apply "cluetips" to all .tips class anchors
		
		var ctOptions = { splitTitle: '|', cluezIndex: 400, width: 'auto', fx: {open: 'fadeIn', openSpeed: 'fast'}, clickThrough: true };
		$jq.cluetip.setup( { insertionType: 'insertBefore', insertionElement: '#main' } );
		
		$jq('.tips[title!=""]').cluetip($jq.extend( ctOptions, {}));
		$jq('.titletips[title!=""]').cluetip($jq.extend( ctOptions, {}));
		$jq('.tikihelp[title!=""]').cluetip($jq.extend( ctOptions, {splitTitle: ':' }));	// , width: '150px'
		$jq('.stickytips').cluetip($jq.extend( ctOptions, { showTitle: false, sticky: false, local: true, hideLocal: true, activation: 'click', cluetipClass: 'fullhtml'}));
		
		// repeats for "tiki" buttons as you cannot set the class and title on the same element with that function (it seems?)
		//$jq('span.button.tips a').cluetip({splitTitle: '|', showTitle: false, width: '150px', cluezIndex: 400, fx: {open: 'fadeIn', openSpeed: 'fast'}, clickThrough: true});
		//$jq('span.button.titletips a').cluetip({splitTitle: '|', cluezIndex: 400, fx: {open: 'fadeIn', openSpeed: 'fast'}, clickThrough: true});
		// TODO after 5.0 - these need changes in the {button} Smarty fn
		
	}	// end cluetip setup
	
	// superfish setup (CSS menu effects)
	if (jqueryTiki.superfish) {
		$jq('ul.cssmenu_horiz').supersubs({ 
            minWidth:    11,   // minimum width of sub-menus in em units 
            maxWidth:    20,   // maximum width of sub-menus in em units 
            extraWidth:  1     // extra width can ensure lines don't sometimes turn over 
                               // due to slight rounding differences and font-family 
		});
		$jq('ul.cssmenu_vert').supersubs({ 
            minWidth:    11,   // minimum width of sub-menus in em units 
            maxWidth:    20,   // maximum width of sub-menus in em units 
            extraWidth:  1     // extra width can ensure lines don't sometimes turn over 
                               // due to slight rounding differences and font-family 
		});
		$jq('ul.cssmenu_horiz').superfish({
			animation: {opacity:'show', height:'show'},	// fade-in and slide-down animation
			speed: 'fast'								// faster animation speed
		});
		$jq('ul.cssmenu_vert').superfish({
			animation: {opacity:'show', height:'show'},	// fade-in and slide-down animation
			speed: 'fast'								// faster animation speed
		});
	}
	
	// tablesorter setup (sortable tables?)
	if (jqueryTiki.tablesorter) {
		$jq('.sortable').tablesorter({
			widthFixed: true							// ??
//			widgets: ['zebra'],							// stripes (coming soon)
		});
	}
	
	// ColorBox setup (Shadowbox, actually "<any>box" replacement)
	if (jqueryTiki.colorbox) {
		$jq().bind('cbox_complete', function(){	
			$jq("#cboxTitle").wrapInner("<div></div>");
		});
				
		// Tiki defaults for ColorBox
		
		// for every link containing 'shadowbox' or 'colorbox' in rel attribute
		$jq("a[rel*='box']").colorbox({
			transition: "elastic",
			maxHeight:"95%",
			maxWidth:"95%",
			overlayClose: true,
			title: true,
			current: jqueryTiki.cboxCurrent
		});
		
		// now, first let suppose that we want to display images in ColorBox by default:
		
		// this matches rel containg type=img or no type= specified
		$jq("a[rel*='box'][rel*='type=img'], a[rel*='box'][rel!='type=']").colorbox({
			photo: true
		});
		// rel containg slideshow (this one must be without #col1)
		$jq("a[rel*='box'][rel*='slideshow']").colorbox({
			photo: true,
			slideshow: true,
			slideshowSpeed: 3500,
			preloading: false,
			width: "100%",
			height: "100%"
		});
		// this are the defaults matching all *box links which are not obviously links to images...
		// (if we need to support more, add here... otherwise it is possible to override with type=iframe in rel attribute of a link)
		//  (from here one to speed it up, matches any link in #col1 only - the main content column)
		
		$jq("#col1 a[rel*='box']:not([rel*='type=img']):not([href*='display']):not([href*='preview']):not([href*='thumb']):not([rel*='slideshow']):not([href*='image']):not([href$='\.jpg']):not([href$='\.jpeg']):not([href$='\.png']):not([href$='\.gif'])").colorbox({
			iframe: true,
			width: "95%",
			height: "95%"
		});
		// hrefs starting with ftp(s)
		$jq("#col1 a[rel*='box'][href^='ftp://'], #col1 a[rel*='box'][href^='ftps://']").colorbox({
			iframe: true,
			width: "95%",
			height: "95%"
		});
		// rel containg type=flash
		$jq("#col1 a[rel*='box'][rel*='type=flash']").colorbox({
			flash: true,
			iframe: false
		});
		// rel with type=iframe (if someone needs to override anything above)
		$jq("#col1 a[rel*='box'][rel*='type=iframe']").colorbox({
			iframe: true
		});
		// inline content: hrefs starting with #
		$jq("#col1 a[rel*='box'][href^='#']").colorbox({
			inline: true,
			width: "50%",
			height: "50%",
			href: function(){ 
				return $jq(this).attr('href');
			}
		});
		
		// titles (for captions):
		
		// by default get title from the title attribute of the link (in all columns)
		$jq("a[rel*='box'][title]").colorbox({
			title: function(){ 
				return $jq(this).attr('title');
			}
		});
		// but prefer the title from title attribute of a wrapped image if any (in all columns)
		$jq("a[rel*='box'] img[title]").colorbox({
			title: function(){ 
				return $jq(this).attr('title');
			},
			photo: true,				// and if you take title from the image you need photo 
			href: function(){			// and href as well (for colobox 1.3.6 tiki 5.0)
				return $jq(this).parent().attr("href");
			}
		});
		
		/* Shadowbox params compatibility extracted using regexp functions */
		
		// rel containg title param overrides title attribute of the link (shadowbox compatible)
		$jq("#col1 a[rel*='box'][rel*='title=']").colorbox({
			title: function () {
				re = /(title=([^;\"]+))/i;
				ret = $jq(this).attr("rel").match(re);
				return ret[2];
			}
		});
		// rel containg height param (shadowbox compatible)
		$jq("#col1 a[rel*='box'][rel*='height=']").colorbox({
			height: function () {
				re = /(height=([^;\"]+))/i;
				ret = $jq(this).attr("rel").match(re);
				return ret[2];
			}
		});
		// rel containg width param (shadowbox compatible)
		$jq("#col1 a[rel*='box'][rel*='width=']").colorbox({
			width: function () {
				re = /(width=([^;\"]+))/i;
				ret = $jq(this).attr("rel").match(re);
				return ret[2];
			}
		});	
		
		// links generated by the {COLORBOX} plugin
		if (jqueryTiki.colorbox) {
			$jq("a[rel^='shadowbox[colorbox']").each(function () { $jq(this).attr('savedTitle', $jq(this).attr('title')); });
			$jq("a[rel^='shadowbox[colorbox']").cluetip({
				splitTitle: '<br />', 
				cluezIndex: 400, 
				width: 'auto', 
				fx: {open: 'fadeIn', openSpeed: 'fast'}, 
				clickThrough: true
			}).colorbox({
				title: function() {
					return $jq(this).attr('savedTitle');	// this fix not required is colorbox was disabled
				}
			});
		}
		
	}	// end if (jqueryTiki.colorbox)
	
	if (jqueryTiki.sheet) {
		
		// override saveSheet on jQuery.sheet for tiki specific export
		$jq.sheet.saveSheet = function( redirect ) {
			if (typeof redirect === 'undefined') { redirect = false; }

			var sheetInstance = this.instance[0];	// for now - only one editable sheet per page
			sheetInstance.evt.cellEditDone();
			
			var s = this.get_sheet_json(sheetInstance);
			
			s = "s=" + $jq.toJSON(s)	// convert to JSON
				.replace(/\+/g,"%2B")	// replace +'s with 0x2B hex value
				.replace(/\&/g,"%26");	// and replace &'s with 0x26
			
			jQuery.ajax({
				url: sheetInstance.s.urlSave,
				type: "POST",
				data: s,
				//contentType: "application/json; charset=utf-8",
				dataType: 'html',
				beforeSend: function() { window.showFeedback("Saving", 10000); }, 
				success: function(data) {
					sheetInstance.setDirty(false);
					window.showFeedback(data, 2000, redirect);
				}
			});
		};
		
		$jq.sheet.get_sheet_json = function(sheetInstance) {	// diverged from jQuery.sheet 1.1 / Tiki 6
			
			var sheetClone = sheetInstance.sheetDecorateRemove(true);
			var docs = []; //documents
			
			jQuery(sheetClone).each(function() {
				var doc = { //document
					metadata:{},
					data:{}
				};

				var count = 0;
				var cur_column = 0, cur_row = 0;
				var max_column = 0, max_row = 0;
				jQuery(this).find('tr').each(function(){
					count = 0;
					jQuery(this).find('td').each(function(){
						count++;
						
						var id = jQuery(this).attr('id');
						var txt = jQuery.trim(jQuery(this).text());
						var pos = id.search(/cell_c/i);
						var pos2 = id.search(/_r/i);
						
						if (pos !== -1 && pos2 !== -1) {
							cur_column = parseInt(id.substr(pos+6, pos2-(pos+6)), 10);
							cur_row = parseInt(id.substr(pos2+2), 10);
							
							if (max_column < cur_column) { max_column = cur_column; }
							
							if (max_row < cur_row) { max_row = cur_row; }
							
							if (count === 1) { doc.data['r' + cur_row] = {}; }
							
							doc.data['r'+cur_row]['c'+cur_column] = {};
							
							doc.data['r'+cur_row]['c'+cur_column].value = txt;
							
							formula = jQuery(this).attr('formula');
							if (formula !== undefined) {
								doc.data['r'+cur_row]['c'+cur_column].formula = formula;
							}
							
							var sp = jQuery(this).attr('colSpan');
							if (sp > 1) {
								doc.data['r'+cur_row]['c'+cur_column].width = sp;
							}
							sp = jQuery(this).attr('rowSpan');	// TODO in .sheet
							if (sp > 1) {
								doc.data['r'+cur_row]['c'+cur_column].height = sp;
							}
						}
					});
					
					cur_column = cur_row = 0;
				});
				
				var id = jQuery(this).attr('rel');
				id = id ? id.match(/sheetId(\d+)/) : null;
				id = id && id.length > 0 ? id[1] : 0;
				doc.metadata = {
					"columns": parseInt(max_column, 10) + 1, //length is 1 based, index is 0 based
					"rows": parseInt(max_row, 10) + 1, //length is 1 based, index is 0 based
					"title": jQuery(this).attr('title'),
					"sheetId" : id
				};
				docs.push(doc); //append to documents
			});
			return docs;
		};

	
	}
	
});		// end $jq(document).ready


/* Autocomplete assistants */

function parseAutoJSON(data) {
	var parsed = [];
	return $jq.map(data, function(row) {
		return {
			data: row,
			value: row,
			result: row
		};
	});
}

/// jquery ui dialog replacements for popup form code
/// need to keep the old non-jq version in tiki-js.js as jq-ui is optional (Tiki 4.0)
/// TODO refactor for 4.n

/* wikiplugin editor */
function popupPluginForm(area_name, type, index, pageName, pluginArgs, bodyContent, edit_icon){
    if (!$jq.ui) {
        return popup_plugin_form(area_name, type, index, pageName, pluginArgs, bodyContent, edit_icon); // ??
    }
    var container = $jq('<div class="plugin"></div>');
    var tempSelectionStart, tempSelectionEnd;

    if (!index) {
        index = 0;
    }
    if (!pageName) {
        pageName = '';
    }
	var textarea = getElementById(area_name);	// use weird version of getElementById in tiki-js.js (also gets by name)
	var replaceText = false;
	
	// 2nd version fix for Firefox 3.5 losing selection on changes to popup
	saveTASelection(area_name);

   if (!pluginArgs && !bodyContent) {
	    pluginArgs = {};
	    bodyContent = "";
		
		dialogSelectElement( area_name, '{' + type.toUpperCase(), '{' + type.toUpperCase() + '}' ) ;
		var sel = getTASelection( textarea );
		if (sel.length > 0) {
			sel = sel.replace(/^\s\s*/, "").replace(/\s\s*$/g, "");	// trim
			//alert(sel.length);
			if (sel.length > 0 && sel.substring(0, 1) === '{') { // whole plugin selected
				var l = type.length;
				if (sel.substring(1, l + 1).toUpperCase() === type.toUpperCase()) { // same plugin
					var rx = new RegExp("{" + type + "[\\(]?([\\s\\S^\\)]*?)[\\)]?}([\\s\\S]*){" + type + "}", "mi"); // using \s\S matches all chars including lineends
					var m = sel.match(rx);
					if (!m) {
						rx = new RegExp("{" + type + "[\\(]?([\\s\\S^\\)]*?)[\\)]?}([\\s\\S]*)", "mi"); // no closing tag
						m = sel.match(rx);
					}
					if (m) {
						var paramStr = m[1];
						bodyContent = m[2];
						
						var pm = paramStr.match(/([^=]*)=\"([^\"]*)\"\s?/gi);
						if (pm) {
							for (var i = 0; i < pm.length; i++) {
								var ar = pm[i].split("=");
								if (ar.length) { // add cleaned vals to params object
									pluginArgs[ar[0].replace(/^[,\s\"\(\)]*/g, "")] = ar[1].replace(/^[,\s\"\(\)]*/g, "").replace(/[,\s\"\(\)]*$/g, "");
								}
							}
						}
					}
					replaceText = sel;
				} else {
					if (!confirm("You appear to have selected text for a different plugin, do you wish to continue?")) {
						return false;
					}
					bodyContent = sel;
					replaceText = true;
				}
			} else { // not (this) plugin
				bodyContent = sel;
				replaceText = true;
			}
		} else {	// no selection
			replaceText = false;
		}
    }
    
    var form = build_plugin_form(type, index, pageName, pluginArgs, bodyContent);
    $jq(form).find('tr input[type=submit]').remove();
    
    container.append(form);
    document.body.appendChild(container[0]);
	
	var pfc = container.find('table tr').length;	// number of rows (plugin form contents)
	var t = container.find('textarea:visible').length;
	if (t) { pfc += t * 3; }
	if (pfc > 9) { pfc = 9; }
	if (pfc < 2) { pfc = 2; }
	pfc = pfc / 10;			// factor to scale dialog height
	
	var btns = {};
	var closeText = "Close";
	btns[closeText] = function() {
		$jq(this).dialog("close");

		// 2nd version fix for Firefox 3.5 losing selection on changes to popup
		restoreTASelection(area_name);

		var ta = getElementById(area_name);
		if (ta) { ta.focus(); }
	};
	
	btns[replaceText ? "Replace" : edit_icon ? "Submit" : "Insert"] = function() {
        var meta = tiki_plugins[type];
        var params = [];
        var edit = edit_icon;
        
        for (var i = 0; i < form.elements.length; i++) {
            element = form.elements[i].name;
            
            var matches = element.match(/params\[(.*)\]/);
            
            if (matches === null) {
                // it's not a parameter, skip 
                continue;
            }
            var param = matches[1];
            
            var val = form.elements[i].value;
            
            if (val !== '') {
                params.push(param + '="' + val + '"');
            }
        }
        
        var blob = '{' + type.toUpperCase() + '(' + params.join(',') + ')}' + (typeof form.content !== 'undefined' ? form.content.value : '') + '{' + type.toUpperCase() + '}';
        
        if (edit) {
            container.children('form').submit();
        } else {
//			getElementById(area_name).focus(); // unsuccesfull attempt to get Fx3.5/win to keep selection info
            insertAt(area_name, blob, false, false, replaceText);
        }
		$jq(this).dialog("close");
	        
		// 2nd version fix for Firefox 3.5 losing selection on changes to popup
		restoreTASelection(area_name);

		return false;
    };

	var heading = container.find('h3').hide();

	container.dialog('destroy').dialog({
		width: $jq(window).width() * 0.6,
		height: $jq(window).height() * pfc,
		title: heading.text(),
		autoOpen: false }).dialog('option', 'buttons', btns).dialog("open");
   
	// quick fix for Firefox 3.5 losing selection on changes to popup
	if (tempSelectionStart) {
		if (typeof textarea.selectionStart !== 'undefined' && textarea.selectionStart !== tempSelectionStart) {
			textarea.selectionStart = tempSelectionStart;
		}
		if (typeof textarea.selectionEnd !== 'undefined' && textarea.selectionEnd !== tempSelectionEnd) {
			textarea.selectionEnd = tempSelectionEnd;
		}
	}
}

/*
 * JS only textarea fullscreen function (for Tiki 5+)
 */

var fullScreenState = [];

$jq(document).ready(function() {	// if in translation-diff-mode go fullscreen automatically
	if ($jq("#diff_outer").length && !$jq(".wikipreview").length) {	// but not if previewing (TODO better)
		toggleFullScreen("editwiki");
	}
});

function toggleFullScreen(area_name) {
	var $ta = $jq("#" + area_name);
	var $diff = $jq("#diff_outer"), $edit_form, $edit_form_innards;	// vars for translation diff elements if present

	if (!$ta.length) {
		$ta = $jq(getElementById(area_name));	// use the cursed getElementById() func from tiki-js.js
		area_name = $ta.attr("id");				// as some textareas use name instead of id still
		if (!area_name) {
			return;			// may be accidentally called in wysiwyg mode and it all goes wrong
		}
	}
	
	if (fullScreenState[area_name]) {	// leave full screen - fullScreenState[area_name] contains info about previous page DOM state when fullscreen
		if ($diff.length) {
			$jq("#fs_grippy_" + area_name).remove();
			$diff.css("float", fullScreenState[area_name]["diff"]["float"]).width(fullScreenState[area_name]["diff"]["width"]).height(fullScreenState[area_name]["diff"]["height"]);
			$jq("#diff_history").height(fullScreenState[area_name]["diff_history"]["height"])
								.width(fullScreenState[area_name]["diff_history"]["width"]);
			for(var i = 0; i < fullScreenState[area_name]["edit_form_innards"].length; i++) {
				$jq(fullScreenState[area_name]["edit_form_innards"][i]["el"])
						.css("left", fullScreenState[area_name]["edit_form_innards"][i]["left"])
						.width(fullScreenState[area_name]["edit_form_innards"][i]["width"])
						.height(fullScreenState[area_name]["edit_form_innards"][i]["height"]);
			}	
			$edit_form = $jq(fullScreenState[area_name]["edit_form"]["el"]);	// hmmm?
			$edit_form.css("position", fullScreenState[area_name]["edit_form"]["position"])
						.css("left", fullScreenState[area_name]["edit_form"]["left"])
						.width(fullScreenState[area_name]["edit_form"]["width"]).height(fullScreenState[area_name]["edit_form"]["height"]);
		}
		$ta.css("float", fullScreenState[area_name]["ta"]["float"]).width(fullScreenState[area_name]["ta"]["width"]).height(fullScreenState[area_name]["ta"]["height"]);
		$ta.resizable({minWidth: fullScreenState[area_name]["resizable"]["minWidth"], minHeight: fullScreenState[area_name]["resizable"]["minHeight"]});
		
		for(i = 0; i < fullScreenState[area_name]["hidden"].length; i++) {
			fullScreenState[area_name]["hidden"][i].show();
		}
		
		for (i = 0; i < fullScreenState[area_name]["changed"].length; i++) {
			var $el = $jq(fullScreenState[area_name]["changed"][i]["el"]);
			$el.css("margin-left", fullScreenState[area_name]["changed"][i]["margin-left"])
				.css("margin-right", fullScreenState[area_name]["changed"][i]["margin-right"])
				.css("margin-top", fullScreenState[area_name]["changed"][i]["margin-top"])
				.css("margin-bottom", fullScreenState[area_name]["changed"][i]["margin-bottom"])
				.css("padding-left", fullScreenState[area_name]["changed"][i]["padding-left"])
				.css("padding-right", fullScreenState[area_name]["changed"][i]["padding-right"])
				.css("padding-top", fullScreenState[area_name]["changed"][i]["padding-top"])
				.css("padding-bottom", fullScreenState[area_name]["changed"][i]["padding-bottom"])
				.width(fullScreenState[area_name]["changed"][i]["width"])
				.height(fullScreenState[area_name]["changed"][i]["height"]);
		}
		
		$jq(".fs_clones").remove();
		$jq(document.documentElement).css("overflow","auto");
		
		fullScreenState[area_name] = false;
		
	} else {		// go full screen
		$jq(window).scrollTop(0);
		$jq(document.documentElement).css("overflow","hidden");
		
		fullScreenState[area_name] = [];
		fullScreenState[area_name]["hidden"] = [];
		fullScreenState[area_name]["changed"] = [];
		fullScreenState[area_name]["resizable"] = [];
		fullScreenState[area_name]["resizable"]["minWidth"] = $ta.resizable("option", "minWidth");
		fullScreenState[area_name]["resizable"]["minHeight"] = $ta.resizable("option", "minHeight");
		
		$ta.resizable("destroy");
		var h = $jq(window).height();
		var w = $jq(window).width();
		
		if ($diff.length) {	// translation diff there so split the screen down the middle (for now)
			w = Math.floor(w / 2) - 5;
		}
		
		// store & hide anything not in col1 
		fullScreenState[area_name]["hidden"].push($jq("#header, #col2, #col3, #footer"));
		$jq("#header, #col2, #col3, #footer").hide();
		
		// store & reset margins, padding and size for all the textarea parents, and hide siblings
		$ta.parents().each(function() {
			fullScreenState[area_name]["hidden"].push($jq(this).siblings(":visible:not('#diff_outer, .translation_message')"));
			var ob = [];
			ob["el"] = this;
			ob["margin-left"] = $jq(this).css("margin-left");	// this is for IE - it fails using margin or padding as a single setting
			ob["margin-right"] = $jq(this).css("margin-right");
			ob["margin-top"] = $jq(this).css("margin-top");
			ob["margin-bottom"] = $jq(this).css("margin-bottom");
			ob["padding-left"] = $jq(this).css("padding-left");
			ob["padding-right"] = $jq(this).css("padding-right");
			ob["padding-top"] = $jq(this).css("padding-top");
			ob["padding-bottom"] = $jq(this).css("padding-bottom");
			ob["width"] = $jq(this).css("width");
			ob["height"] = $jq(this).css("height");
			fullScreenState[area_name]["changed"].push(ob);
		});
		$ta.parents().each(function() {
			$jq(this).siblings(":visible:not('#diff_outer, .translation_message')").hide();
			$jq(this).css("margin", 0).css("padding", 0).width(w).height(h);
		});
		
		// store & resize translation diff divs etc
		if ($diff.length) {
			fullScreenState[area_name]["diff"] = [];
			fullScreenState[area_name]["diff"]["width"] = $diff.width();
			fullScreenState[area_name]["diff"]["height"] = $diff.height();
			fullScreenState[area_name]["diff"]["float"] = $diff.css("float");
			fullScreenState[area_name]["diff_history"] = [];
			fullScreenState[area_name]["diff_history"]["height"] = $jq("#diff_history").height();
			fullScreenState[area_name]["diff_history"]["width"] = $jq("#diff_history").width();
			$edit_form = $diff.next();
			$edit_form_innards = $edit_form.find("#edit-zone, table.normal, textarea, fieldset");
			fullScreenState[area_name]["edit_form"] = [];
			fullScreenState[area_name]["edit_form"]["el"] = $edit_form[0];	// store this element for easy access later
			fullScreenState[area_name]["edit_form"]["height"] = $edit_form.height();
			fullScreenState[area_name]["edit_form"]["width"] = $edit_form.width();
			fullScreenState[area_name]["edit_form"]["left"] = $edit_form.css("left") !== 'auto' ? $edit_form.css("left") : 0;
			fullScreenState[area_name]["edit_form"]["position"] = $edit_form.css("position");
			fullScreenState[area_name]["edit_form_innards"] = [];
			$edit_form_innards.each(function() {
				var ob = [];
				ob["el"] = this;
				ob["width"] = $jq(this).css("width");
				ob["height"] = $jq(this).css("height");
				ob["left"] = $jq(this).css("left");
				fullScreenState[area_name]["edit_form_innards"].push(ob);
			});
			
			$diff.parents().each(function() {			// shares some parents with the textarea
				$jq(this).width($jq(window).width());	// so make room for both
			});
		}
		
		// resize the actual textarea
		fullScreenState[area_name]["ta"] = [];
		fullScreenState[area_name]["ta"]["width"] = $ta.width();
		fullScreenState[area_name]["ta"]["height"] = $ta.height();
		fullScreenState[area_name]["ta"]["float"] = $ta.css("float");
		
		var b = 0;
		if ($ta.css("border-left-width")) {
		b = $ta.css("border-left-width").replace("px","");
		}
		
		$ta.width(w - b * 2).height($ta.parent().height() - $jq(".textarea-toolbar").height() - $jq(".translation_message").height() - 60 - b * 2);
		
		// add grippy resize bar to translation diff page
		if ($diff.length) {
			var grippy_width = 10;
			$diff.width(w).height(h).css("float", "left").next().css("float", "right");
			var vh = $jq("#diff_versions").css("overflow", "auto").height() + 18;
			if (vh > h * 0.15) {
				vh = h * 0.15;
			}
			$jq("#diff_versions").height(vh);
			$jq("#diff_history").height(h - vh).width(w).css("left", w + grippy_width);
			$edit_form.css("position","absolute").css("left", w + grippy_width).width(w - grippy_width);
			
			$grippy = $jq("<div id='fs_grippy_" + area_name +"' />").css({"background-image": "url(pics/icons/shading.png)",
											"background-repeat": "repeat-y",
											"background-position": -3,
											"position": "absolute",
											"left": w + "px",
											"top": 0,
											"cursor": "col-resize"})
									.width(grippy_width).height(h).draggable({ axis: 'x', drag: function(event, ui) {
										$diff.find("div,table").width(ui.offset.left - grippy_width);
										$edit_form.css("left", ui.offset.left + grippy_width).find("#edit-zone, table.normal, textarea, fieldset")
												.width($jq(window).width() - ui.offset.left);
									} });
			$diff.after($grippy);
			
		}
		
		// copy and add the action buttons (preview, save etc)
		if ($jq("div.top_actions").length) {
			$ta.parent().append($jq("div.top_actions > .wikiaction").clone(true).addClass("fs_clones"));
		} else {
			$ta.parent().append($jq("#editpageform td > .wikiaction").clone(true).addClass("fs_clones"));
		}

		// show action buttons and reapply cluetip options
		if (jqueryTiki.tooltips) {
			$jq(".fs_clones").cluetip({splitTitle: '|', showTitle: false, width: '150px', cluezIndex: 400, fx: {open: 'fadeIn', openSpeed: 'fast'}, clickThrough: true}).show();
		} else {
			$jq(".fs_clones").show();
		}

	}
}

/* Simple tiki plugin for jQuery
 * Helpers for autocomplete and sheet
 */

$jq.fn.tiki = function(func, type, options) {
	var opts;
	switch (func) {
		case "autocomplete":
			if (jqueryTiki.autocomplete) {
				if (typeof type !== 'undefined') { // func and type given
					options = options || {};		// some default options for autocompletes in tiki
					opts = {extraParams: {"httpaccept": "text/javascript"},
								dataType: "json",
								parse: parseAutoJSON,
								formatItem: function(row) { return row; },
								selectFirst: false,
								max: 15
							};
					for(opt in options) {
						opts[opt] = options[opt];
					}
				}
				var data = "";
				switch (type) {
					case "pagename":
						data = "tiki-listpages.php?listonly";
						break;
					case "groupname":
						data = "tiki-ajax_services.php?listonly=groups";
						break;
					case "username":
						data = "tiki-ajax_services.php?listonly=users";
						break;
					case "tag":
						data = "tiki-ajax_services.php?listonly=tags&separator=+";
						break;
				}
		 		return this.each(function() {
					$jq(this).autocomplete(data, opts);
		
				});
			}
			break;

		case "sheet":
			if (jqueryTiki.sheet) {
				options = options || {};	// some default options for sheets in tiki
				var sheet_theme = jqueryTiki.ui ? "lib/jquery/jquery-ui/themes/" + jqueryTiki.ui_theme + "/jquery-ui.css" : "lib/jquery/jquery.sheet/theme/jquery-ui-1.8.1.custom.css";
				opts = {urlBaseCss: 	"lib/jquery/jquery.sheet/jquery.sheet.css",
							urlTheme: 		sheet_theme,
							urlMenu: 		"lib/jquery_tiki/jquery.sheet/menu.html",	/* not working currently due to missing menu plugin */
							urlGet: "",
							buildSheet: true
						};
				for(opt in options) {
					opts[opt] = options[opt];
				}
		 		return this.each(function() {
					var sh;
		 			if (jqueryTiki.ui) {
		 				$jq(this).height($jq(this).height() + 100);	// make room for controls
		 				sh = $jq(this).sheet(opts);
		 				if (typeof ajaxLoadingShow === 'function') {
		 					ajaxLoadingHide();
		 				}
		 			} else {
		 				sh = $jq(this).sheet(opts);
		 			}
				});
			}
			break;
		case "carousel":
			if (jqueryTiki.carousel) {
				opts = {
						imagePath: "lib/jquery/infinitecarousel/images/"
					};
				for(opt in options) {
					opts[opt] = options[opt];
				}
		 		return this.each(function() {
					$jq(this).infiniteCarousel(opts);			
				});
			}
			break;
		case "datepicker":
			if (jqueryTiki.ui) {
				opts = {
						showOn: "both",
						buttonImage: "pics/icons/calendar.png",
						buttonImageOnly: true,
						dateFormat: "yy-mm-dd",
						showButtonPanel: true
					};
				for(opt in options) {
					opts[opt] = options[opt];
				}
		 		return this.each(function() {
					$jq(this).datepicker(opts);			
				});
			}
			break;
		case "accordion":
			if (jqueryTiki.ui) {
				opts = {
						autoHeight: false,
						collapsible: true,
						navigation: true
//						change: function(event, ui) {
//							// sadly accordion active property is broken in 1.7, but fix is coming in 1.8 so TODO 
//							setCookie(ui, ui.options.active, "accordion");
//						}
					};
				for(opt in options) {
					opts[opt] = options[opt];
				}
		 		return this.each(function() {
					$jq(this).accordion(opts);			
				});
			}
	}
};

/******************************
 * Functions for dialog tools *
 ******************************/

// shared

window.dialogData = [];
var dialogDiv;

function displayDialog( closeTo, list, areaname ) {
	var i, item, el, obj, tit = "";

	// 2nd version fix for Firefox 3.5 losing selection on changes to popup
	saveTASelection(areaname);

	if (!dialogDiv) {
		dialogDiv = document.createElement('div');
		document.body.appendChild( dialogDiv );
	}
	$jq(dialogDiv).empty();
	
	for( i = 0; i < window.dialogData[list].length; i++ ) {
		item = window.dialogData[list][i];
		if (item.indexOf("<") === 0) {	// form element
			el = $jq(item);
			$jq(dialogDiv).append( el );
		} else if (item.indexOf("{") === 0) {
			try {
				//obj = JSON.parse(item);	// safer, but need json2.js lib
				obj = eval("("+item+")");
			} catch (e) {
				alert(e.name + ' - ' + e.message);
			}
		} else {
			tit = item;
		}
	}
	
	if (!obj) { obj = {}; }
	if (!obj.width) { obj.width = 210; }
	obj.bgiframe = true;
	obj.autoOpen = false;
	$jq(dialogDiv).dialog('destroy').dialog(obj).dialog('option', 'title', tit).dialog('open');

	// 2nd version fix for Firefox 3.5 losing selection on changes to popup
	//restoreTASelection(areaname);
	// don't restore here - dialog will do it
	
	return false;
}

function dialogSelectElement( areaname, elementStart, elementEnd ) {
	restoreTASelection( areaname );
	
	var $textarea = $jq(getElementById(areaname));
	var val = $textarea.val();
	var pairs = [], pos = 0, s = 0, e = 0;
	
	while (s > -1) {	// positions of start/end markers
		s = val.indexOf(elementStart, e);
		if (s > -1) {
			e = val.indexOf(elementEnd, s + elementStart.length);
			if (e > -1) {
				e += elementEnd.length;
				pairs[pairs.length] = [ s, e ];
			}
		}
	}
	
	var selection = $textarea.selection();

	s = selection.start;
	e = selection.end;
	var st = $textarea.attr('scrollTop');

	for (var i = 0; i < pairs.length; i++) {
		if (s > pairs[i][0] && e < pairs[i][1]) {
			setSelectionRange($textarea[0], pairs[i][0], pairs[i][1]);
			break;
		}
	}
	
	saveTASelection( areaname );
}


function dialogSharedClose( areaname, dialog ) {
	$jq(dialog).dialog("close");
	restoreTASelection(areaname);
}

// Internal Link

function dialogInternalLinkOpen( areaname ) {
	$jq("#tbWLinkPage").tiki("autocomplete", "pagename");
	dialogSelectElement( areaname, '((', '))' ) ;
	var s = getTASelection($jq(getElementById(areaname))[0]);
	var m = /\((.*)\(([^\|]*)\|?([^\|]*)\|?([^\|]*)\|?\)\)/g.exec(s);
	if (m && m.length > 4) {
		if ($jq("#tbWLinkRel")) {
			$jq("#tbWLinkRel").val(m[1]);
		}
		$jq("#tbWLinkPage").val(m[2]);
		if (m[4]) {
			if ($jq("#tbWLinkAnchor")) {
				$jq("#tbWLinkAnchor").val(m[3]);
			}
			$jq("#tbWLinkDesc").val(m[4]);
		} else {
			$jq("#tbWLinkDesc").val(m[3]);
		}
	} else {
		$jq("#tbWLinkDesc").val(s);
		if ($jq("#tbWLinkAnchor")) {
			$jq("#tbWLinkAnchor").val("");
		}
	}
}

function dialogInternalLinkInsert( areaname, dialog ) {
	var s = "(";
	if ($jq("#tbWLinkRel") && $jq("#tbWLinkRel").val()) {
		s += $jq("#tbWLinkRel").val();
	}
	s += "(" + $jq("#tbWLinkPage").val();
	if ($jq("#tbWLinkAnchor") && $jq("#tbWLinkAnchor").val()) {
		s += "|" + ($jq("#tbWLinkAnchor").val().indexOf("#") !== 0 ? "#" : "") + $jq("#tbWLinkAnchor").val();
	}
	if ($jq("#tbWLinkDesc").val()) {
		s += "|" + $jq("#tbWLinkDesc").val();
	}
	s += "))";
	restoreTASelection(areaname);
	insertAt(areaname, s, false, false, true);
	saveTASelection(areaname);
	
	dialogSharedClose( areaname, dialog );
	
}

// External Link

function dialogExternalLinkOpen( areaname ) {
	$jq("#tbWLinkPage").tiki("autocomplete", "pagename");
	dialogSelectElement( areaname, '[', ']' ) ;
	var s = getTASelection($jq(getElementById(areaname))[0]);
	var m = /\[([^\|]*)\|?([^\|]*)\|?([^\|]*)\]/g.exec(s);
	if (m && m.length > 3) {
		$jq("#tbLinkURL").val(m[1]);
		$jq("#tbLinkDesc").val(m[2]);
		if (m[3]) {
			if ($jq("#tbLinkNoCache") && m[3] == "nocache") {
				$jq("#tbLinkNoCache").attr("checked", "checked");
			} else {
				$jq("#tbLinkRel").val(m[3]);
			}
		} else {
			$jq("#tbWLinkDesc").val(m[3]);
		}
	} else {
		if (s.match(/(http|https|ftp)([^ ]+)/ig) == s) { // v simple URL match
			$jq("#tbLinkURL").val(s);
		} else {
			$jq("#tbLinkDesc").val(s);
		}
	}
	if (!$jq("#tbLinkURL").val()) {
		$jq("#tbLinkURL").val("http://");
	}
}

function dialogExternalLinkInsert(areaname, dialog) {

	var s = "[" + $jq("#tbLinkURL").val();
	if ($jq("#tbLinkDesc").val()) {
		s += "|" + $jq("#tbLinkDesc").val();
	}
	if ($jq("#tbLinkRel").val()) {
		s += "|" + $jq("#tbLinkRel").val();
	}
	if ($jq("#tbLinkNoCache") && $jq("#tbLinkNoCache").attr("checked")) {
		s += "|nocache";
	}
	s += "]";
	restoreTASelection(areaname);
	insertAt(areaname, s, false, false, true);
	saveTASelection(areaname);
	
	dialogSharedClose( areaname, dialog );
	
}

// Table

function dialogTableOpen(areaname, dialog) {

	dialogSelectElement( areaname, '||', '||' ) ;

	var s = getTASelection($jq(getElementById(areaname))[0]);
	var m = /\|\|([\s\S]*?)\|\|/mg.exec(s);
	var vals = [], rows = 3, cols = 3, c, r, i, j;
	if (m) {
		m = m[1];
		m = m.split("\n");
		rows = 0;
		cols = 1;
		for (i = 0; i < m.length; i++) {
			var a2 = m[i].split("|");
			var a = [];
			for (j = 0; j < a2.length; j++) { // links can have | chars in
				if (a2[j].indexOf("[") > -1 && a2[j].indexOf("[[") == -1 && a2[j].indexOf("]") == -1) { // external link
					a[a.length] = a2[j];
					j++;
					var k = true;
					while (j < a2.length && k) {
						a[a.length - 1] += "|" + a2[j];
						if (a2[j].indexOf("]") > -1) { // closed
							k = false;
						} else {
							j++;
						}
					}
				} else if (a2[j].search(/\(\S*\(/) > -1 && a2[j].indexOf("))") == -1) {
					a[a.length] = a2[j];
					j++;
					k = true;
					while (j < a2.length && k) {
						a[a.length - 1] += "|" + a2[j];
						if (a2[j].indexOf("))") > -1) { // closed
							k = false;
						} else {
							j++;
						}
					}
				} else {
					a[a.length] = a2[j];
				}
			}
			vals[vals.length] = a;
			if (a.length > cols) {
				cols = a.length;
			}
			if (a.length) {
				rows++;
			}
		}
	}
	for (r = 1; r <= rows; r++) {
		for (c = 1; c <= cols; c++) {
			var v = "";
			if (vals.length) {
				if (vals[r - 1] && vals[r - 1][c - 1]) {
					v = vals[r - 1][c - 1];
				} else {
					v = "   ";
				}
			} else {
				v = "   "; //row " + r + ",col " + c + "";
			}
			var el = $jq("<input type=\"text\" id=\"tbTableR" + r + "C" + c + "\" class=\"ui-widget-content ui-corner-all\" size=\"10\" value=\"" + v + "\" style=\"width:" + (90 / cols) + "%\" />");
			$jq(dialog).append(el);
		}
		if (r == 1) {
			el = $jq("<img src=\"pics/icons/add.png\" />");
			$jq(dialog).append(el);
			el.click(function() {
				$jq(dialog).attr("cols", $jq(dialog).attr("cols") + 1);
				for (r = 1; r <= $jq(dialog).attr("rows"); r++) {
					v = "   ";
					var el = $jq("<input type=\"text\" id=\"tbTableR" + r + "C" + $jq(dialog).attr("cols") + "\" class=\"ui-widget-content ui-corner-all\" size=\"10\" value=\"" + v + "\" style=\"width:" + (90 / $jq(dialog).attr("cols")) + "%\" />");
					$jq("#tbTableR" + r + "C" + ($jq(dialog).attr("cols") - 1)).after(el);
				}
				$jq(dialog).find("input").width(90 / $jq(dialog).attr("cols") + "%");
			});
		}
		$jq(dialog).append($jq("<br />"));
	}
	el = $jq("<img src=\"pics/icons/add.png\" />");
	$jq(dialog).append(el);
	el.click(function() {
		$jq(dialog).attr("rows", $jq(dialog).attr("rows") + 1);
		for (c = 1; c <= $jq(dialog).attr("cols"); c++) {
			v = "   ";
			var el = $jq("<input type=\"text\" id=\"tbTableR" + $jq(dialog).attr("rows") + "C" + c + "\" class=\"ui-widget-content ui-corner-all\" size=\"10\" value=\"" + v + "\" style=\"width:" + (90 / $jq(dialog).attr("cols")) + "%\" />");
			$jq(this).before(el);
		}
		$jq(this).before("<br />");
		$jq(dialog).dialog("option", "height", ($jq(dialog).attr("rows") + 1) * 1.2 * $jq("#tbTableR1C1").height() + 130);
	});
	
	dialog.rows = rows;
	dialog.cols = cols;
	$jq(dialog).dialog("option", "width", (cols + 1) * 120 + 50);
	$jq(dialog).dialog("option", "position", "center");
	$jq("#tbTableR1C1").focus();
}

function dialogTableInsert(areaname, dialog) {
	var s = "||", rows, cols, c, r, rows2 = 1, cols2 = 1;
	rows = dialog.rows ? dialog.rows : 3;
	cols = dialog.cols ? dialog.cols : 3;
	for (r = 1; r <= rows; r++) {
		for (c = 1; c <= cols; c++) {
			if ($jq.trim($jq("#tbTableR" + r + "C" + c).val())) {
				if (r > rows2) {
					rows2 = r;
				}
				if (c > cols2) {
					cols2 = c;
				}
			}
		}
	}
	for (r = 1; r <= rows2; r++) {
		for (c = 1; c <= cols2; c++) {
			s += $jq("#tbTableR" + r + "C" + c).val();
			if (c < cols2) {
				s += "|";
			}
		}
		if (r < rows2) {
			s += "\n";
		}
	}
	s += "||";
	restoreTASelection(areaname);
	insertAt(areaname, s, false, false, true);
	saveTASelection(areaname);
	
	dialogSharedClose( areaname, dialog );
}

// Find

function dialogFindOpen(areaname) {
	
	var s = getTASelection($jq(getElementById(areaname))[0]);
	$jq("#tbFindSearch").val(s).focus();			  
}

function dialogFindFind( areaname ) {
	
	var s, opt, ta, str, re, p = 0, m;
	s = $jq("#tbFindSearch").removeClass("ui-state-error").val();
	opt = "";
	if ($jq("#tbFindCase").attr("checked")) {
		opt += "i";
	}
	ta = $jq(getElementById(areaname));
	str = ta.val();
	re = new RegExp(s,opt);
	p = getCaretPos(ta[0]);
	if (p && p < str.length) {
		m = re.exec(str.substring(p));
	} else {
		p = 0;
	}
	if (!m) {
		m = re.exec(str);
		p = 0;
	}
	if (m) {
		setSelectionRange(ta[0], m.index + p, m.index + s.length + p);
	} else {
		$jq("#tbFindSearch").addClass("ui-state-error");
	}

}

// Replace

function dialogReplaceOpen(areaname) {

	var s = getTASelection($jq(getElementById(areaname))[0]);
	$jq("#tbReplaceSearch").val(s).focus();
	  		  
}

function dialogReplaceReplace( areaname ) {
	
	var s = $jq("#tbReplaceSearch").val();
	var r = $jq("#tbReplaceReplace").val();
	var opt = "";
	if ($jq("#tbReplaceAll").attr("checked")) {
		opt += "g";
	}
	if ($jq("#tbReplaceCase").attr("checked")) {
		opt += "i";
	}
	var str = $jq(getElementById(areaname)).val();
	var re = new RegExp(s,opt);
	$jq(getElementById(areaname)).val(str.replace(re,r));

}





