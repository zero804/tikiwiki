//$Id$

jQuery.fn.extend({
	flexibleCodeMirror: function(settings) {
		settings = jQuery.extend({
			parse: ["tiki"],
			changeText: "Change Highlighter",
			languages: ["csharp","css","html","java","javascript","lua","ometa","sparql","php","plsql","python","r","scheme","sql","tiki","xml","xquery"],
			buttonText: {
				update: "Update",
				cancel: "Cancel"
			},
			parent: jQuery(this).parent(),
			lineNumbers: false,
			textWrapping: true,
			readOnly: false,
			width: '100%',
			force: false
		}, settings);
		
		jQuery(this).each(function() {
			var o = jQuery(this);
			
			if (!settings.force && !o.is('pre')) {
				if (!o.data('codemirror') || o.data("nocodemirror")) return false;
			}
			
			var textarea;
			
			if (!o.is(':input')) {
				var val = o.text();
				textarea =  $('<textarea></textarea>')
					.appendTo(settings.parent)
					.val(val);
			} else {
				textarea = o;
				settings.width = o.width() + "px";
				settings.height = o.height() + "px";
			}
			
			if (textarea.attr('codeMirrorRelationshipFullscreen')) return false;
			
			if (!textarea.length) return false;
			if (!window.CodeMirror) return false;
			
			var parserfiles = [];
			var stylesheet = [];
			
			var parse = textarea.data('syntax');
			if (parse) settings.parse = (parse + '').split(',');
			
			parse = settings.parse;
			
			if (!$.isArray(parse)) parse = [parse];
			
			function addParser(type, isContrib, hasTokenizer, hasColors) {
				var src = '';
				if (isContrib) {
					src = '../contrib/' + type + '/js/';
				}
				
				if (hasTokenizer) {
					parserfiles.push(src + 'tokenize' + type + '.js');
				}
				
				parserfiles.push(src + 'parse' + type + '.js');
				
				if (hasColors)
					addStylesheet(type, isContrib);
			}
			
			function addStylesheet(type, isContrib) {
				var src = 'lib/codemirror/';
				if (isContrib) {
					src += 'contrib/' + type + '/css/';
				} else {
					src += 'css/';
				}
				
				stylesheet.push(src + type + 'colors.css');
			}
			
			jQuery(parse).each(function(i) {
				switch (parse[i]) {
					case "csharp":
						addParser(parse[i], true, true, true);
						break;
					case "css": 		
						addParser(parse[i], false, false, true);
						break;
					case "html":
						addParser(parse[i] + 'mixed');
						addParser('xml', false, false, true);
						break;
					case "java":
						addParser(parse[i], true, true, true);
						break;
					case "javascript":
						addParser(parse[i], false, true);
						addStylesheet('js');
						break;
					case "lua":
						addParser(parse[i], true, false, true);
						break;
					case "ometa": 
						addParser(parse[i], true, true, true);
						break;
					case "sparql": 
						addParser(parse[i], false, false, true);
						break;
					case "php":
						addParser(parse[i], true, true, true);
						break;
					case "plsql":
						addParser(parse[i], true, false, true);
						break;
					case "python":
						addParser(parse[i], false, false, true); 
						break;
					case "r": 			
						parserfiles.push('../../codemirror_tiki/js/parsersplus.js'); 
						stylesheet.push('lib/codemirror_tiki/css/rspluscolors.css'); 
						break;
					case "scheme":
						addParser(parse[i], true, true, true);
						break;
					case "sql": 		
						addParser(parse[i], true, false, true); 
						break;
					case "tiki":
						parserfiles.push('../../codemirror_tiki/js/parsetikisyntax.js');
						stylesheet.push('lib/codemirror_tiki/css/tikiwikisyntaxcolors.css');
						break;
					case "xml":
						addParser(parse[i], false, false, true);
						break;
					case "xquery": 
						addParser(parse[i], true, true);
						stylesheet.push('lib/codemirror/contrib/xquery/css/xqcolors.css');
						break;
				}
			});
			
			var editor = CodeMirror.fromTextArea(textarea[0], {
				width: settings.width,
				height: settings.height,
				path: 'lib/codemirror/js/',
				parserfile: (parserfiles.length ? parserfiles : ['parsedummy.js']), //if no parser is loaded, load the dummy so that they can at least edit
				stylesheet: stylesheet,
				onChange: function() {
					//Setup codemirror to send the text back to the textarea
					textarea.val(editor.getCode()).change();
				},
				lineNumbers: settings.lineNumbers,
				textWrapping: settings.textWrapping,
				readOnly: settings.readOnly
			});
			
			if (!settings.readOnly) {
				addCodeMirrorEditorRelation(editor, textarea);
				
				var changeButton = jQuery('<div class="button">' +
				'<a>' +
				settings.changeText +
				'</a>' +
				'</div>').insertAfter(textarea.next()).click(function(){
					var options = 'Languages:<br />';
					jQuery(settings.languages).each(function(){
						var lang = this + '';
						options += '<input class="lang" type="checkbox" value="' + lang + '" ' + (parse.indexOf(lang) > -1 ? 'checked="true"' : '') + '/>' + lang + '<br />';
					});
					
					options += 'Options:<br />';
					options += '<input class="opt" type="checkbox" value="lineNumbers" ' + (settings.lineNumbers ? 'checked="true"' : '') + '/>Line Numbers<br />';
					options += '<input class="opt" type="checkbox" value="textWrapping" ' + (settings.textWrapping ? 'checked="true"' : '') + '/>Text Wrapping<br />';
					options += '<input class="opt" type="checkbox" value="dynamicHeight" ' + (settings.dynamicHeight ? 'checked="true"' : '') + '/>Dynamic Height<br />';
					
					var msg = jQuery('<div />').html(options).dialog({
						title: settings.changeText,
						modal: true,
						buttons: {
							"Update": function(){
								var newSettings = {};
								var newParse = [];
								msg.find('.lang').each(function(){
									var o = jQuery(this);
									if (o.is(':checked')) {
										newParse.push(o.val());
									}
								});
								
								newSettings.parse = newParse;
								
								msg.find('.opt').each(function(){
									var o = jQuery(this);
									newSettings[o.val()] = o.is(':checked');
								});
								
								changeButton.remove();
								editor.toTextArea();
								
								
								textarea.flexibleCodeMirror(jQuery.extend(settings, newSettings));
								
								msg.dialog("destroy");
							},
							"Cancel": function(){
								msg.dialog("destroy");
							}
						}
					});
				});
			}
		});
	}
});

$('textarea')
	.flexibleCodeMirror({
		changeText: tr("Change Highlighter")
	});

if (!$.s5) {
	$('.codelisting')
		.each(function() {
			$(this).flexibleCodeMirror({
				readOnly: true,
				parse: ['javascript'],
				width: $(this).width() + 'px',
				height: $(this).parent().height() + 'px'
			});
		})
		.hide();
}			
			
$(document)
	.bind('plugin_html_ready', function(args) {
		var code = args.container.find('textarea:first');
		
		code.flexibleCodeMirror({
			parse: ['xml', 'css', 'javascript', 'html'],
			lineNumbers: true,
			changeText: tr("Change Highlighter"),
			force: true
		});
	});

$(document)
	.bind('plugin_code_ready', function(args) {
		var code = args.container.find('textarea:first');
		
		code.flexibleCodeMirror({
			parse: ['xml', 'css', 'javascript', 'html'],
			lineNumbers: true,
			changeText: tr("Change Highlighter"),
			force: true
		});
	});

$(document)
	.bind('plugin_r_ready', function(args) {
		var r = args.container.find('textarea:first');
	
		r.flexibleCodeMirror({
			parse: ['r'],
			lineNumbers: true,
			changeText: tr("Change Highlighter"),
			force: true
		});
	});

$(document)
	.bind('plugin_rr_ready', function(args) {
		var rr = args.container.find('textarea:first');

		rr.flexibleCodeMirror({
			parse: ['r'],
			lineNumbers: true,
			changeText: tr("Change Highlighter"),
			force: true
		});
	});