{* $Id$ *}

<h2>{tr}Export Tracker Items{/tr}</h2>
<div>
<form action="tiki-view_tracker.php?trackerId={$trackerId}&cookietab=3" method="post">
<table class="normal">
<tr class="formcolor">
	<td><label for="tracker">{tr}Tracker{/tr}</label></td>
	<td>
	<select name="trackerId" onchange="this.form.submit();" id="tracker">
      {foreach from=$trackers item=tracker}
       <option value="{$tracker.trackerId}" title="{$tracker.description|escape}"{if $tracker.trackerId eq $trackerId} selected="selected"{/if}>
           {$tracker.name|escape}
       </option>
      {/foreach}
    </select>
	</td>
</tr>
</table>
</form>
<form action="tiki-export_tracker{if $prefs.feature_ajax eq 'y'}_ajax{/if}.php" method="post" id="export_form">
<table class="normal">
<tr class="formcolor">
	<td>{tr}File{/tr}</td>
	<td>{tr}Tracker{/tr}_{$trackerId}.csv</td>
</tr>
<tr class="formcolor">
	<td><label for="encoding">{tr}Charset encoding{/tr}</label></td>
	<td><select name="encoding" id="emcoding"><option value="UTF-8" selected="selected">{tr}UTF-8{/tr}</option><option value="ISO-8859-1">{tr}ISO-8859-1{/tr}</option></select></td>
</tr>
<tr class="formcolor">
	<td><label for="separator">{tr}Separator{/tr}</label></td>
	<td><input type="text" name="separator" id="separator" value="," size="2" /></td>
</tr>
<tr class="formcolor">
	<td><label for="delimitorL">{tr}Delimitors{/tr}</label></td>
	<td><input type="text" name="delimitorL" id="delimitorL" value='"' size="2" /><input type="text" name="delimitorR" value='"' size="2" /></td>
</tr>
<tr class="formcolor">
	<td><label for="CR">{tr}Carriage Return inside Field Value{/tr}</label></td>
	<td><input type="text" name="CR" id="CR" value='%%%' size="4" /></td>
</tr>
<tr class="formcolor">
	<td><label for="parse">{tr}Parse as Wiki Text{/tr}</label></td>
	<td><input type="checkbox" name="parse" id="parse" /></td>
</tr>
<tr class="formcolor">
	<td>{tr}Info{/tr}</td>
	<td>
		<input name="showItemId" id="showItemId" type="checkbox" checked="checked" /><label for="showItemId">{tr}itemId{/tr}</label>
		<input type="checkbox" name="showStatus" id="showStatus"{if $info.showStatus eq 'y'} checked="checked"{/if} /><label for="showStatus">{tr}status{/tr}</label>
		<input type="checkbox" name="showCreated" id="showCreated"{if $info.showCreated eq 'y'} checked="checked"{/if} /><label for="showCreated">{tr}created{/tr}</label>
		<input type="checkbox" name="showLastModif" id="showLastModif"{if $info.showLastModif eq 'y'} checked="checked"{/if} /><label for="lastModif">{tr}lastModif{/tr}</label>
	</td>
</tr>
<tr class="formcolor">
	<td>{tr}Fields{/tr}</td>
	<td>
		<input type="radio" name="which" id="list" value="list"/> <label for="list">{tr}Fields visible in items list{/tr}</label>
		<br /><input type="radio" name="which" id="ls" value="ls"/> <label for="ls">{tr}Fields searchable or visible in items list{/tr}</label>
		<br /><input type="radio" name="which" id="item" value="item"/> <label for="item">{tr}Fields visible in an item view{/tr}</label>
		<br /><input type="radio" name="which" id="all" value="all"{if empty($displayedFields)} checked="checked"{/if} /> <label for="all">{tr}All fields{/tr}</label>
		<br /><input type="radio" name="which" id="these" value="these"{if !empty($displayedFields)} checked="checked"{/if}> <label for="these">{tr}These fields{/tr}</label>
		<div id="fields_list"{if empty($displayedFields)} style="display:none"{/if}>
			<select multiple="multiple" name="listfields[]" id="listfields">
				{foreach from=$fields item=ix}
					{if ($ix.isHidden eq 'n' or $ix.isHidden eq 'c' or $ix.isHidden eq 'p' or $tiki_p_admin_trackers eq 'y') and $ix.type ne 'x' and $ix.type ne 'h' and ($ix.type ne 'p' or $ix.options_array[0] ne 'password') and (empty($ix.visibleBy) or in_array($default_group, $ix.visibleBy) or $tiki_p_admin_trackers eq 'y')}
						<option value="{$ix.fieldId}"{if !empty($displayedFields) and in_array($ix.fieldId, $displayedFields)} selected="selected"{/if}>{$ix.name|escape}</option>
					{/if}
				{/foreach}
			</select>
			{remarksbox type="tip" title="{tr}Tip{/tr}"}{tr}Use Ctrl+Click to select multiple options{/tr}{/remarksbox}
		</div>
		{jq}$jq("input:name=which").change(function(){ if ($jq(this).val() == "these") { $jq("#fields_list").slideDown("fast"); } else { $jq("#fields_list").slideUp("fast"); } });{/jq}
	</td>
</tr>
<tr class="formcolor">
	<td>{tr}Filter{/tr}</td>
	<td>{include file="wiki-plugins/wikiplugin_trackerfilter.tpl" showFieldId="y" inForm="y"}</td></tr>
{if $prefs.feature_ajax eq 'y'}
	<tr class="formcolor">
		<td><label for="recordsMax">{tr}Number of records{/tr}</label></td>
		<td>
			<input type="text" name="recordsMax" id="recordsMax" value="{$recordsMax}" size="6" />
			<label for="recordsOffset">{tr}Start record{/tr}</label>
			<input type="text" name="recordsOffset" id="recordsOffset" value="{$recordsOffset}" size="6" />
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<p id="export_msg"></p>
			<div id="export_prog"></div>
		</td>
	</tr>
{/if}
<tr class="formcolor"><td>&nbsp;</td><td><input type="submit" name="export" id="export_button" value="{tr}Export{/tr}" /></td>
</tr>
</table>
</form>
<style type="text/css">
	.ui-progressbar-value {ldelim} background-image: url(lib/jquery/jquery-ui/themes/{$prefs.feature_jquery_ui_theme}/images/pbar-ani.gif); {rdelim}
</style>
{if $prefs.feature_ajax eq 'y'}{jq}

// setup for AJAX export
$jq("#export_form").submit( function () { return exportStart(this); });

if (!$jq.ui) { $jq("#export_prog").hide(); }

exportStart = function (el) {
	
	if ($jq.ui) {
		$jq("#export_prog").progressbar("destroy").progressbar({ value: 1 });
	}
	$jq("#export_button").hide();
	
	var fm = el;
	$jq(fm).attr('target', 'dl_frame');
	var $dl_frame = $jq('<iframe id="dl_frame" name="dl_frame"></iframe>');
	$dl_frame.css({position:'absolute',top:'-500px',left:'-500px'}).appendTo('body');
	fm.submit();
	
//	$jq.post("tiki-export_tracker_ajax.php", $jq(el).serialize(), function (data) {
//		//alert("done the post");
//	});

	$jq("#export_msg").text("Starting export...");
	setTimeout(function () { exportProgress(); }, 2000);
	return false;
}
exportProgress = function () {
	//console.debug("exportProgress");
	$jq.getJSON("tiki-export_tracker_monitor.php", { trackerId: {{$trackerId}}, xuser: "{{$user}}" }, function (res) {
		//console.debug(res);
		if (res) {
			if (res.status == "finish") {
				$jq("#dl_frame").remove();
				$jq("#export_msg").text("Exported: " + res.current + " records");
				if ($jq.ui) { $jq("#export_prog").progressbar('option', 'value', 100); }
				$jq("#export_button").show();
			} else {
				if (res.msg) {
					$jq("#export_msg").text("Message: " + res.msg);
				} else if (res.current) {
					var pc = parseInt((res.current / res.total) * 100, 10);
					$jq("#export_msg").text("Exported: " + res.current + "/" + res.total + " (" + pc + "%)");
					if ($jq.ui) {
						$jq("#export_prog").progressbar('option', 'value', pc);
					}
				} else if (res.status) {
					$jq("#export_msg").text("Status: " + res.status);
				}
				setTimeout(function () { exportProgress(); }, 1000);
			}
		}
	});
}
{/jq}
{remarksbox type="note" title="Warning"}Please note: Using experimental AJAX export function - work in progress!{/remarksbox}
{/if}
</div>

