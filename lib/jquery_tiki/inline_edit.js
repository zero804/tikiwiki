(function ($) {
	/**
	 * One executor is created for each object containing inline fields.
	 * When the field is modified, the executor is triggered.
	 *
	 * As a delayed executor, a period of grace is given for the field to
	 * be corrected or other fields to be modified. Each modification resets 
	 * the counter.
	 *
	 * When modifications stop happening, the entire object is stored in a
	 * single AJAX request.
	 */
	var executors = {}, obtainExecutor;

	obtainExecutor = function (container) {
		var url = $(container).data('object-store-url');

		if (executors[url]) {
			return executors[url];
		}

		return executors[url] = delayedExecutor(5000, function () {
			var parts = [];
			var containers = [];
			$('.editable-inline :input').each(function () {
				var container = $(this).closest('.editable-inline')[0];
				var ownership = $(container).data('object-store-url');

				if (ownership === url) {
					parts.push($(this).serialize());

					if (-1 === containers.indexOf(container)) {
						containers.push(container);
					}
				}
			});

			$(containers).each(function () {
				$(this).tikiModal($(this).width() > 20 ? tr("Saving...") : " ");
			});

			$.post(url, parts.join('&'), 'json')
				.success(function () {
					$(containers).
						removeClass('modified').
						removeClass('unsaved').
						trigger('changed.inline.tiki').
						trigger('saved.tiki').
						filter(function () {
							// The post-save value application is only for cases where the field was initially fetched
							return $(this).data('field-fetch-url');
						}).
						each(function () {
							var $this = $(this),
								obj = $.extend($(this).data('field-fetch-url'), { mode: "output" });	// use the url for the field input in output mode

							$.get($.serviceUrl(obj))
								.success(function (data) {
									$this.removeClass("loaded")
										.tikiModal()
										.html($.trim(data.replace("<!DOCTYPE html>", "")))
										.attr("title", $(this).data("saved_title") || "")
										.removeData("saved_title");
								});
						});
				})
				.error(function () {
					$(containers).filter('.modified').addClass('unsaved');
					$.getJSON($.service('object', 'report_error'));
				})
				;
		});
	};

	$(document).on('click', '.editable-inline:not(.loaded)', function () {
		var container = this
			, url = $(this).data('field-fetch-url')
			;

		$(container).
			addClass('loaded').
			data("saved_html", $(container).html()).
			data("saved_text", $(container).text());

		if (url) {
			$.get(url)
				.success(function (data) {
					var w = $(container).parent().width();	// td width
					$(container).html(data);
					$("input, select", container).each(function () {
						$(this).keydown(function (e) {
							if (e.which === 13) {
								$(this).blur();
								return false;
							} else if (e.which === 9) {
								$(this).blur();
								if (e.shiftKey) {
									$(this).parents("td:first").prev().find(".editable-inline:first").click();
								} else {
									$(this).parents("td:first").next().find(".editable-inline:first").click();
								}
								return false;
							} else {
								return true;
							}
						}).width(Math.min($(this).width(), w));
					});
					if (jqueryTiki.chosen) {
						var $select = $("select", container);
						if ($select.length) {
							$select.tiki("chosen");
						}
					}
				})
				.error(function () {
					$(container).addClass('failure');
				})
				;
		}
	});

	$(document).on('change', '.editable-inline.loaded :input', function () {
		var container, executor;
		
		container = $(this).closest('.editable-inline')[0];
		executor = obtainExecutor(container);
		$(container).
			data("saved_title", $(container).attr("title")).
			attr("title", tr("Queued for saving")).
			addClass('modified').
			trigger('changed.inline.tiki');

		executor();
	});
})(jQuery);
