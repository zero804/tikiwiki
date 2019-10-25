// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 * Make page ready for inline editing
 */

function enableWysiwygInlineEditing() {

	if ($(".wp_wysiwyg").length) {
		alert(tr("This page contains a WYSIWYG plugin so is not suitable for inline editing, sorry."));
		setCookie("wysiwyg_inline_edit", "", "preview", "session");
		return false;
	}

	ajaxLoadingShow("page-data");
	setCookie("wysiwyg_inline_edit", 1, "preview", "session");
	$.get($.service("wiki", "get_page", {page: window.CKEDITOR.config.autoSavePage}), function (data) {
		if (data) {
			$("#page-data").html(data);
			$("body").append($("script", "#page-data").remove());	// move scripts somewhere else
			// lists dont inline happily so wrap in divs
			$("#page-data > ul, #page-data > ol, #page-data > dl, #page-data > table").each(function () {
					$(this).wrap("<div>");
			});
			// save original data and add contenteditable
			$("#page-data > *:not(.icon_edit_section)").each(function () {
				if ($(".tiki_plugin", this).length === 0 && !$(this).hasClass("tiki_plugin")) {
					$(this).data("inline_original", $(this).html())
							.attr("contenteditable", true);
				} else {
					$(this).attr("title", tr("This block is not editable inline currently as it contains plugins."));
				}
			});

			// handle toobals per element
			window.CKEDITOR.on("instanceCreated", function( event ) {
				var editor = event.editor,
				element = editor.element;

				// Customize editors for headers and tag list.
				// These editors dont need features like smileys, templates, iframes etc.
				if ( element.is( "h1", "h2", "h3", "h4", "h5", "h6" )) {
					// Customize the editor configurations on "configLoaded" event,
					// which is fired after the configuration file loading and
					// execution. This makes it possible to change the
					// configurations before the editor initialization takes place.
					editor.on( "configLoaded", function() {
						// Remove unnecessary plugins to make the editor simpler.
						editor.config.removePlugins = "colorbutton,find,flash,font," +
							"forms,iframe,image,newpage,removeformat,scayt," +
							"smiley,specialchar,stylescombo,templates,wsc";
						// Rearrange the layout of the toolbar.
			//			editor.config.toolbarGroups = [
			//				{ name: "editing", groups: [ "basicstyles", "links" ] },
			//				{ name: "undo" },
			//				{ name: "clipboard", groups: [ "selection", "clipboard" ] }
			//			];
					});
				}
				editor.on('blur', function (event) {
					// Check if something has changed
					var e = event.editor;
					if (e.checkDirty()) {
						// Unsaved changed
						$(this.element)[0].addClass('unsavedChangesInEditor');
					}
				});
				editor.on("instanceReady", function (event) {
					// Remove "Rich Text Editor,..." tooltip
					$(event.editor.element.$).attr('title', '');
				});
			});
			// init inline ckeditors
			window.CKEDITOR.inlineAll();
			ajaxLoadingHide();
		}
	});

	$(window).on('beforeunload', function(e) {
		if (window.needToConfirm && checkWysiwygInlineEditingDirty()) {
			var msg = tr("You are about to leave this page. Changes since your last save may be lost. Are you sure you want to exit this page?");
			if (e) {
				e.returnValue = msg;
			}
			return msg;
		}
	});

	window.needToConfirm = true;
	return true;
}

/**
 * Stop inline editing on this page.
 *	The user will confirm the closing. Thus it can be cancelled
 *
 * @return boolean true if stopped, otherwise false
 */

function disableWYSIWYGInlineEditing(dontConfirm) {

	// Check for unsaved changes
	if (! dontConfirm && checkWysiwygInlineEditingDirty()) {
		var msg = tr("You are about to exit the inline editor. Changes since your last save may be lost. Are you sure you want to proceed?");
		var rc = confirm(msg);
		if (rc == false) {
			return false;
		}
	}

	// Stop inline editing
	ajaxLoadingShow("page-data");
	setCookie("wysiwyg_inline_edit", "", "preview", "session");
	$.get($.service("wiki", "get_page", {page: window.CKEDITOR.config.autoSavePage}), function (data) {
		if (data) {
			$("#page-data").html(data);
			$("#page-data > *").removeClass("unsavedChangesInEditor");	// Clear the dirty marker display
			$("#page-data > *[contenteditable=true]").attr("contenteditable", false).removeClass("cke_editable");
			for (var e in  CKEDITOR.instances) {
				if (CKEDITOR.instances[e] != null) {
					CKEDITOR.instances[e].destroy();
				}
			}
		}
		ajaxLoadingHide();
		$.buildAutoToc();
	});

	return true;
}

function checkWysiwygInlineEditingDirty() {
	if (typeof CKEDITOR === 'object') {
		for (var ed in CKEDITOR.instances) {
			if (CKEDITOR.instances.hasOwnProperty(ed)) {
				if (CKEDITOR.instances[ed].checkDirty()) {
					return true;
				}
			}
		}
	}
	return false;
}

/**
 * Process divs set up by wikiplugin_wysiwyg
 */

$.fn.wysiwygPlugin = function (execution, page, ckoption) {

	$(this).each(function () {
		var $this = $(this).css({position: "relative"});
		var $edit_button = $("<button />")
			.addClass("btn btn-primary edit_button_wp_wysiwyg_" + execution)
			.text(tr("Edit"))
			.button()
			.mouseover(function () {
				$(this).show();
				$this.css({
					backgroundColor: "rgba(128,128,125,0.25)"
				});
			});
		var wp_bgcol = $(this).css("background-color");
		$(this).mouseover(function () {
			$(this).css({
				backgroundColor:  "rgba(128,128,125,0.2)"
			});
			$edit_button
				.css({ right: "1em", bottom: "1em" })
				.show();
		}).mouseout(function () {
				$(this).css({
					backgroundColor: wp_bgcol
				});
				$edit_button.hide();
		}).append(
			$edit_button
				.css({position: "absolute", display: "none"})
				.click(function () {
					// check no one else is editing this page
					$.getJSON($.service("semaphore", "is_set_by_other"), {
						object_type: jqueryTiki.current_object.type,
						object_id: jqueryTiki.current_object.object
					}, function (data) {
						if (data) {
							$("#tikifeedback").showError(tr("This page is being edited by another user. Please reload the page and try again later."));
						} else {
							// coast is clear, carry on...

							$.getJSON($.service("semaphore", "set"), {
								object_type: jqueryTiki.current_object.type,
								object_id: jqueryTiki.current_object.object
							});
							$edit_button.remove();
							$("#outerToc-static").hide();

							var ok = true;
							$(".wp_wysiwyg:not(#wp_wysiwyg_" + execution + ")").each(function () {
								if (CKEDITOR.instances[$(this).attr("id")]) {
									if (CKEDITOR.instances[$(this).attr("id")].checkDirty()) {
										if (confirm(tr("You have unsaved changes in this WYSIWYG section.\nDo you want to save your changes?"))) {
											CKEDITOR.instances[$(this).attr("id")].focus();
											ok = false;
											return;
										}
									}
									CKEDITOR.instances[$(this).attr("id")].destroy();
								}
								$(".button_" + $(this).attr("id")).remove();
							});
							if (!ok) {
								return;
							}

							CKEDITOR.replace($this.attr("id"), ckoption);
							CKEDITOR.on("instanceReady", function (event) {
								// close others
								var editor = event.editor;

								if (editor.element.getId() !== "wp_wysiwyg_" + execution) {
									return;
								}
								var editorSelector = "#cke_" + editor.element.getId();

								$(".button_wp_wysiwyg_" + execution).remove();

								$(editorSelector).after(
									$("<button />")
										.addClass("button_wp_wysiwyg_" + execution)
										.text(tr("Cancel"))
										.button()
										.click(function () {
											$.getJSON($.service("semaphore", "unset"), {
												object_type: jqueryTiki.current_object.type,
												object_id: jqueryTiki.current_object.object
											}, function () {
												// reload
												location.href = location.href.replace(/#.*$/, "");
											});
										})
								).after(
									$("<button />")
										.addClass("button_wp_wysiwyg_" + execution)
										.text(tr("Save"))
										.button()
										.click(function (event) {
											// FIXME: loses width parameter
											
											$(editorSelector).tikiModal(tr("Saving..."));

											$.getJSON($.service("semaphore", "unset"), {
												object_type: jqueryTiki.current_object.type,
												object_id: jqueryTiki.current_object.object
											});

											var data = editor.getData();
											data = data.replace(/<\/p>\n\n<p/g, "</p>\n<p");	// remove cke4 extra linefeeds
											data = data.replace(/\n\n\s*?$/m, "\n");
											var height, params = {};

											// Set height parameter
											if (editor.plugins.divarea) {
												height = editor.ui.space("contents").getClientRect().height;
											} else if (editor.window) { // Not defined in source mode
												height = $(editor.window.getFrame().$).height();
											}
											if (height) {
												params["height"] = height + "px";
											} else {
												// Fallback to the parameter's current value, which is passed in the style attribute  
												params["height"] = $(editorSelector).prev(".wp_wysiwyg").css('min-height');
											}
											
											params["use_html"] = $(editorSelector).prev(".wp_wysiwyg").data("html");
											var options = {
												page: page,
												type: "wysiwyg",
												message: "Modified by WYSIWYG Plugin",
												index: execution,
												content: data,
												params: params,
												ticket: $this.data("ticket"),
											};

											$.post($.service('plugin', 'replace'), options, function () {
													location.reload();
												},
												"json"
											).always(function () {
												$(editorSelector).tikiModal();
											});
											return false;
										})
								);
							});
						}
					});
				})
			);
	});
};

