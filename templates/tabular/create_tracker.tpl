{extends "layout_view.tpl"}

{block name="title"}
	{title}{$title}{/title}
{/block}

{block name="navigation"}
	<div class="form-group row">
		{permission name=admin_trackers}
			<a class="btn btn-primary" href="{service controller=tabular action=manage}">{icon name=list} {tr}Manage{/tr}</a>
		{/permission}
	</div>
{/block}

{block name="content"}

	<form class="edit-tabular" method="post" action="{service controller=tabular action=create_tracker}" enctype="multipart/form-data">
		<div class="form-group row">
			<label class="col-form-label col-sm-3">{tr}Name{/tr}</label>
			<div class="col-sm-9">
				<input class="form-control" type="text" name="name" required>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-form-label col-sm-3">{tr}Tracker Name{/tr}</label>
			<div class="col-sm-9">
				<input class="form-control" type="text" name="tracker_name" required>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-form-label  col-sm-3">{tr}File{/tr}</label>
			<div class="col-sm-9">
				<input type="file" name="file" accept="text/csv" required>
			</div>
		</div>
		<div id="tracker-columns" class="form-group row" style="display:none">
			<div class="col-sm-12">
				<table class="table fields">
					<thead>
					<tr>
						<th>{tr}Field{/tr}</th>
						<th>{tr}Field Type{/tr}</th>
						<th><abbr title="{tr}Primary Key{/tr}">{tr}PK{/tr}</abbr></th>
						<th><abbr title="{tr}Unique Key{/tr}">{tr}UK{/tr}</abbr></th>
						<th><abbr title="{tr}Read-Only{/tr}">{tr}RO{/tr}</abbr></th>
						<th><abbr title="{tr}Export-Only{/tr}">{tr}EO{/tr}</abbr></th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<tr id="row-template" class="hidden" style="display: none;">
						<td>
							<div class="input-group input-group-sm">
								<input type="text" class="field-label form-control" readonly/>
								<div class="input-group-btn">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<span class="align">{tr}Left{/tr}</span>
										<input class="display-align" type="hidden" value="left">
									</button>
									<ul class="dropdown-menu dropdown-menu-right" role="menu">
										<li class="dropdown-item"><a class="align-option" href="#left">{tr}Left{/tr}</a></li>
										<li class="dropdown-item"><a class="align-option" href="#center">{tr}Center{/tr}</a></li>
										<li class="dropdown-item"><a class="align-option" href="#right">{tr}Right{/tr}</a></li>
										<li class="dropdown-item"><a class="align-option" href="#justify">{tr}Justify{/tr}</a></li>
									</ul>
								</div>
							</div>
						</td>
						<td class="form-group">
							<select class="form-control type">
								{foreach from=$types key=k item=info}
									<option value="{$k}" {if $k eq 't'}selected{/if}>{$info.name|escape} {if $info.deprecated}- Deprecated{/if}</option>
								{/foreach}
							</select>
							{*<ul class="dropdown-menu dropdown-menu-right" role="menu">*}
								{**}
							{*</ul>*}

						</td>
						<td><input class="primary" type="radio" name="pk" required /></td>
						<td><input class="unique-key" type="checkbox" /></td>
						<td><input class="read-only" type="checkbox" /></td>
						<td><input class="export-only" type="checkbox" /></td>
						<td class="text-right"><button class="remove">{icon name=remove}</button></td>
					</tr>
					</tbody>
					<tfoot>
					<tr>
						<td colspan="5">
							<textarea name="fields" class="hidden"></textarea>
						</td>
					</tr>
					</tfoot>
				</table>
				<div class="form-text">
					<p><strong>{tr}Primary Key:{/tr}</strong> {tr}Required to import data. Can be any field as long as it is unique.{/tr}</p>
					<p><strong>{tr}Unique Key:{/tr}</strong> {tr}Impose unique value requirement for the target column. This only works with Transactional Import feature.{/tr}</p>
					<p><strong>{tr}Read-only:{/tr}</strong> {tr}When importing a file, read-only fields will be skipped, preventing them from being modified, but also speeding-up the process.{/tr}</p>
					<p>{tr}When two fields affecting the same value are included in the format, such as the ID and the text value for an Item Link field, one of the two fields must be marked as read-only to prevent a conflict.{/tr}</p>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-form-label col-sm-3">{tr}Simple headers{/tr}</label>
			<div class="col-sm-9 form-check">
				<input type="hidden" name="config[simple_headers]" value="1">
				<input type="checkbox" class="form-check-input" value="1" checked disabled>
				<a class="tikihelp" title="{tr}Simple headers{/tr}: {tr}Allow using field labels only as a header row when importing rather than the full &quot;Field [permName:type]&quot; format.{/tr}">
					{icon name=information}
				</a>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-form-label col-sm-3">{tr}Import updates{/tr}</label>
			<div class="col-sm-9 form-check">
				<input type="checkbox" class="form-check-input" name="config[import_update]" value="1" {if $config['import_update']} checked {/if}>
				<a class="tikihelp" title="{tr}Import update{/tr}: {tr}Allow updating existing entries matched by PK when importing. If this is disabled, only new items will be imported.{/tr}">
					{icon name=information}
				</a>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-form-label col-sm-3">{tr}Ignore blanks{/tr}</label>
			<div class="col-sm-9 form-check">
				<input type="checkbox" class="form-check-input" name="config[ignore_blanks]" value="1" {if $config['ignore_blanks']} checked {/if}>
				<a class="tikihelp" title="{tr}Ignore blanks{/tr}: {tr}Ignore blank values when import is updating existing items. Only non-blank values will be updated this way.{/tr}">
					{icon name=information}
				</a>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-form-label col-sm-3">{tr}Transactional import{/tr}</label>
			<div class="col-sm-9 form-check">
				<input type="checkbox" class="form-check-input" name="config[import_transaction]" value="1" {if $config['import_transaction']} checked {/if}>
				<a class="tikihelp" title="{tr}Import transaction{/tr}: {tr}Import in a single transaction. If any of the items fails validation, the whole import is rejected and nothing is saved.{/tr}">
					{icon name=information}
				</a>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-form-label col-sm-3">{tr}Bulk import{/tr}</label>
			<div class="col-sm-9 form-check">
				<input type="checkbox" class="form-check-input" name="config[bulk_import]" value="1" {if $config['bulk_import']} checked {/if}>
				<a class="tikihelp" title="{tr}Bulk Import{/tr}: {tr}Import in 'bulk' mode so the search index is not updated for each item and no notifications should be sent.{/tr}">
					{icon name=information}
				</a>
			</div>
		</div>
		<div class="form-group submit">
			<div class="col-sm-9 col-sm-push-3">
				<input type="submit" class="btn btn-primary" value="{tr}Import{/tr}">
			</div>
		</div>
	</form>

	{jq}
		$('input[name="file"]').on('change', function() {

			var fileUpload = $('input[name="file"]')[0];

			// remove existing elements
			$('#tracker-columns').find('tr[id^="row"]:not(".hidden")').each(function(){
				$(this).remove();
			});

			if (!fileUpload.value) {
				$('#tracker-columns').hide();
				$('#tracker-columns').find('table').trigger('tabular-update');
			}

			if (typeof (FileReader) != "undefined" && fileUpload.value != '') {

				var reader = new FileReader();

				reader.onload = function (e) {
					var rows = e.target.result.split("\n");
					var columns = rows[0].split(',');
					// @todo get ajax to process the columns row csv without loading js csv libs

					for(i = 0; i < columns.length; i++) {
						var $elem = $('#row-template').clone();
						$elem.attr('id', 'row'+i);
						$elem.removeClass('hidden');
						$elem.find('input[type="text"]').val(columns[i]);
						$elem.show();
						$('#tracker-columns table tbody').append($elem);
					}

					$('#tracker-columns').show();
					$('#tracker-columns').find('table').trigger('tabular-update');
				}

				reader.readAsText(fileUpload.files[0]);
			}
		});

	{/jq}
{/block}

