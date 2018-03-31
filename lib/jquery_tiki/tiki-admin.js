(function ($) {
	$(document).on('change', '.preference :checkbox:not(.pref-reset)', function () {
		var childBlock = $(this).data('tiki-admin-child-block')
			, childMode = $(this).data('tiki-admin-child-mode')
			, checked = $(this).is(':checked')
			, disabled = $(this).prop('disabled')
			, $depedencies = $(this).parents(".adminoption").find(".pref_dependency")
			, childrenElements = null
			;

		if (childBlock) {
			childrenElements = $(childBlock).find(':input[id^="pref-"]');
		}

		if (childMode === 'invert') {
			checked = ! checked;
		}

		if (disabled && checked) {
			$(childBlock).show('fast');
			$depedencies.show('fast');
		} else if (disabled || ! checked) {
			var hideBlock = true;
			childrenElements.each(function( index ) {
				var value = $( this ).val();
				var valueDefault = $( this ).siblings('span.pref-reset-wrapper').children('.pref-reset').attr('data-preference-default');

				if (typeof valueDefault != 'undefined' && value != valueDefault) {
					hideBlock = false;
				}
			});

			if (hideBlock) {
				$(childBlock).hide('fast');
				$depedencies.hide('fast');
			}
		} else {
			$(childBlock).show('fast');
			$depedencies.show('fast');
		}
	});

	$(document).on('click', '.pref-reset-wrapper a', function () {
		var box = $(this).closest('span').find(':checkbox');
		box.click();
		$(this).closest('span').children( ".pref-reset-undo, .pref-reset-redo" ).toggle();
		return false;
	});
	
	$(document).on('click', '.pref-reset', function() {
		var c = $(this).prop('checked');
		var $el = $(this).closest('.adminoptionbox').find('input:not(:hidden),select,textarea')
			.not('.system').attr( 'disabled', c )
			.css("opacity", c ? .6 : 1 );
		var defval = $(this).data('preference-default');

		if ($el.is(':checkbox')) {
			$(this).data('preference-default', $el.prop('checked') ? 'y' : 'n');
			$el.prop('checked', defval === "y");
		} else {
			$(this).data('preference-default', $el.val());
			$el.val(defval);
		}
		$el.change();
		if (jqueryTiki.chosen) {
			$el.trigger("chosen:updated");
		}
	});

	$(document).on('change', '.preference select', function () {
		var childBlock = $(this).data('tiki-admin-child-block')
			, selected = $(this).val()
			, childMode = $(this).data('tiki-admin-child-mode')
			;

		$(childBlock).hide();
		$(childBlock + ' .modified').show();
		$(childBlock + ' .modified').parent().show();

		if (selected && /^[\w-]+$/.test(selected)) {
			$(childBlock).filter('.' + selected).show();
		}
		if (childMode === 'notempty' && selected.length) {
			$(childBlock).show();
		}
	});

	$(document).on('change', '.preference :radio', function () {
		var childBlock = $(this).data('tiki-admin-child-block');

		if ($(this).prop('checked')) {
			$(childBlock).show('fast');
			$(this).closest('.preference').find(':radio').not(this).change();
		} else {
			$(childBlock).hide();
		}
	});

	$(function () {
		$('.preference :checkbox, .preference select, .preference :radio').change();
	});

	$(function () {
		// highlight the admin icon (anchors)
		var $anchors = $(".adminanchors li a, .admbox"),
			bgcol = $anchors.is(".admbox") ? $anchors.css("background-color") : $anchors.parent().css("background-color");

		$("input[name=lm_criteria]").keyup( function () {
			var criterias = this.value.toLowerCase().split( /\s+/ ), word, text;
			$anchors.each( function() {
				var $parent = $(this).is(".admbox") ? $(this) : $(this).parent();
				if (criterias && criterias[0]) {
					text = $(this).attr("alt").toLowerCase();
					for( var i = 0; criterias.length > i; ++i ) {
						word = criterias[i];
						if ( word.length > 0 && text.indexOf( word ) == -1 ) {
							$parent.css("background", "");
							return;
						}
					}
					$parent.css("background", "radial-gradient(white, " + bgcol + ")");
				} else {
					$parent.css("background", "");
				}
			});
		});
	});

	// AJAX plugin list load for admin/textarea/plugins
	var pluginSearchTimer  = null;
	$("#pluginfilter").change(function (event) {
		var filter = $(this).val();
		if (filter.length > 2 || !filter) {
			if (pluginSearchTimer) {
				clearTimeout(pluginSearchTimer);
				pluginSearchTimer = null;
			}
			$("#pluginlist").load($.service("plugin", "list"), {
				filter: filter
			}, function (response, status, xhr) {
				if (status === "error") {
					$("#pluginfilter").showError(xhr);
				}
				$(this).tikiModal();
			}).tikiModal(tr("Loading..."));
		}
	}).keydown(function (event) {
		if (event.which === 13) {
			event.preventDefault();
			$(this).change();
		} else if (! pluginSearchTimer) {
			pluginSearchTimer = setTimeout(function () {
				$("#pluginfilter").change();
			}, 1000);
		}
	});

})(jQuery);
