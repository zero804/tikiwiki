{* $Id$ *}
{if isset($msg)}{$msg|escape}{/if}

{title help="Credits"}{tr}Manage Credits{/tr}{/title}

<form method="get" action="tiki-admin_credits.php" class="form-inline">
	<label class="my-1 mr-2" for="userfilter">{tr}Username:{/tr}</label>
	<input class="form-control" type="text" name="userfilter" id="userfilter" value="{$userfilter|escape}">
	<input  class="btn btn-info" type="submit" value="{tr}Search{/tr}">
</form>

{if $new_month}{$new_month}{/if}

{if $editing}
<form method="post" action="tiki-admin_credits.php">
	<div class="table-responsive">
		<table class="table">
			<tr>
				<td></td>
				<td><b>{tr}Type{/tr}</b></td>
				<td><b>{tr}Creation Date{/tr}</b><br>{tr}(YYYY-MM-DD HH:MM:SS){/tr}</td>
				<td><b>{tr}Expiration Date{/tr}</b><br>{tr}(YYYY-MM-DD HH:MM:SS){/tr}</td>
				<td><b>{tr}Used{/tr}</b><br>{tr}(level credits always 0){/tr}</td>
				<td><b>{tr}Total{/tr}</b></td>
			</tr>
			{foreach key=id item=data from=$credits}
			<tr>
				<td>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="delete[]" value="{$id|escape}">
					</div>
				</td>
				<td><input class="form-control" type="text" name="credits[{$id|escape}][credit_type]" value="{$data.credit_type|escape}" readonly="readonly"></td>
				<td><input class="form-control" type="text" name="credits[{$id|escape}][creation_date]" value="{$data.creation_date|escape}"></td>
				<td><input class="form-control" type="text" name="credits[{$id|escape}][expiration_date]" value="{$data.expiration_date|escape}"></td>
				<td><input class="form-control" type="text" name="credits[{$id|escape}][used_amount]" value="{$data.used_amount|escape}"></td>
				<td><input class="form-control" type="text" name="credits[{$id|escape}][total_amount]" value="{$data.total_amount|escape}"></td>
			</tr>
			{/foreach}
			<tr>
				<td><strong>{tr}New{/tr}</strong></td>
				<td>
					<select name="credit_type" class="form-control">
						{foreach key=id item=data from=$credit_types}
						<option value="{$id}">{$id|escape}</option>
						{/foreach}
					</select>
				</td>
				<td><input class="form-control" type="text" name="creation_date" value="" size="20"></td>
				<td><input class="form-control" type="text" name="expiration_date" value="" size="20"></td>
				<td><input class="form-control" type="text" name="used_amount" value="0" size="6" readonly="readonly"></td>
				<td><input class="form-control" type="text" name="total_amount" value="" size="6"></td>
			</tr>
			<tr>
				<td colspan="5"><input class="btn btn-primary" type="submit" name="save" value="{tr}Save{/tr}" style="display:none;"><input class="btn btn-danger" type="submit" name="confirm" value="{tr}Delete Checked{/tr}"></td>
				<td colspan="1"><input class="btn btn-primary" type="submit" name="save" value="{tr}Save{/tr}"><input type="hidden" name="userfilter" value="{$userfilter|escape}"></td>
			</tr>
		</table>
	</div>
</form>

<h2>{tr}User Credits Expiry Summary (Plans){/tr}</h2>
<div class="table-responsive">
	<table class="table">
		<tr>
			<th>{tr}User Plan{/tr}</th>
			<th>{tr}Start of Latest Plan{/tr}</th>
			<th>{tr}Start of Next Plan{/tr}</th>
			<th>{tr}Expiry{/tr}</th>
		</tr>
		{foreach key=id item=data from=$userPlans}
			<tr>
				<td>{$id|escape}</td>
				<td>{if $data.currentbegin}{$data.currentbegin|escape}{else}-{/if}</td>
				<td>{if $data.nextbegin}{$data.nextbegin|escape}{else}-{/if}</td>
				<td>{if $data.expiry}{$data.expiry|escape}{else}-{/if}</td>
			</tr>
		{/foreach}
	</table>
</div>

<h2>{tr}Use User Credits{/tr}</h2>
<form method="post" action="tiki-admin_credits.php">
	{tr}Use:{/tr}
	<select class="form-control" name="use_credit_type">
		{foreach key=id item=data from=$credit_types}
			<option value="{$id}">{$id|escape}</option>
		{/foreach}
	</select>
	<br>
	{tr}Amount:{/tr} <input class="form-control" type="text" name="use_credit_amount" value="0" size="8">
	<input type="hidden" name="userfilter" value="{$userfilter|escape}">
	<input class="btn btn-primary" type="submit" name="use_credit" value="{tr}Use{/tr}">
</form>

<h2>{tr}Restore User Level Credits{/tr}</h2>
<form method="post" action="tiki-admin_credits.php">
	{tr}Restore:{/tr}
	<select class="form-control" name="restore_credit_type">
		{foreach key=id item=data from=$static_credit_types}
			<option value="{$id}">{$id|escape}</option>
		{/foreach}
	</select>
	<br>
	{tr}Amount:{/tr} <input class="form-control" type="text" name="restore_credit_amount" value="0" size="8">
	<input type="hidden" name="userfilter" value="{$userfilter|escape}">
	<input type="submit" name="restore_credit" value="{tr}Restore{/tr}">
</form>

<h2>{tr}Historical Usage Report{/tr}</h2>
	<div>
		<form method="post" action="tiki-admin_credits.php">
			<input type="hidden" name="userfilter" value="{$userfilter|escape}">
			<table class='normal'>
				<tr>
					<td>
						{html_select_date time=$startDate prefix="startDate_" end_year="-10" day_value_format="%02d" field_order=$prefs.display_field_order}
					<br>
						{html_select_date time=$endDate prefix="endDate_" end_year="-10" day_value_format="%02d" field_order=$prefs.display_field_order}
					</td>
					<td>
						<select  class="form-control" name="action_type">
							<option value="">{tr}all types{/tr}</option>
							{foreach key=id item=data from=$credit_types}
								<option value="{$id}" {if $act_type eq '{$id}'}selected="selected"{/if}>{$id|escape}</option>
							{/foreach}
						</select>
					</td>

					<td>&nbsp;</td>
					<td><input class="btn btn-primary" type="submit" value="{tr}filter{/tr}"><br/><br/></td>
				</tr>
				<tr>
					<th>{tr}Type{/tr}</th>
					<th>{tr}Usage Date{/tr}</th>
					<th colspan='2'>{tr}Amount Used{/tr}</th>
				</tr>
				{foreach item=con_data from=$consumption_data}
					<tr>
						<td>{$con_data.credit_type}</td>
						<td>{$con_data.usage_date|date_format:"%d-%m-%Y %H:%M:%S"}</td>
						<td colspan='2'>{$con_data.used_amount}</td>
					</tr>
				{/foreach}
			</table>
		</form>
	</div>
{else}
	{tr}No such user{/tr}
{/if}
<hr>

<h1>{tr}Manage Credit Types{/tr}</h1>
<form method="post" action="tiki-admin_credits.php">
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<tr>
				<td></td>
				<td><b>{tr}Type{/tr}</b></td>
				<td><b>{tr}Display Text{/tr}</b></td>
				<td><b>{tr}Unit Text{/tr}</b></td>
				<td><b>{tr}Is Static Level Credit{/tr}</b></td>
				<td><b>{tr}Display Bar Length Scaling Divisor{/tr}</b></td>
			</tr>
			{foreach key=id item=data from=$credit_types}
			<tr>
				<td>&nbsp;</td>
				<td><input class="form-control" type="text" name="credit_types[{$id|escape}][credit_type]" value="{$data.credit_type|escape}" size="8" readonly="readonly"></td>
				<td><input class="form-control" type="text" name="credit_types[{$id|escape}][display_text]" value="{$data.display_text|escape}" size="8"></td>
				<td><input class="form-control" type="text" name="credit_types[{$id|escape}][unit_text]" value="{$data.unit_text|escape}" size="8"></td>
				<td><select class="form-control" name="credit_types[{$id|escape}][is_static_level]">
				<option value='n'>{tr}No{/tr}</option>
				<option value='y' {if $data.is_static_level == 'y'}selected="selected"{/if}>{tr}Yes{/tr}</option>
				</select>
				<td><input class="form-control" type="text" name="credit_types[{$id|escape}][scaling_divisor]" value="{$data.scaling_divisor|escape}" size="6"></td>
			</tr>
			{/foreach}
			<tr>
				<td><strong>{tr}New{/tr}</strong></td>
				<td><input class="form-control" type="text" name="new_credit_type" value="" size="8"></td>
				<td><input class="form-control" type="text" name="display_text" value="" size="8"></td>
				<td><input class="form-control" type="text" name="unit_text" value="" size="8"></td>
				<td>
					<select class="form-control" name="is_static_level">
					<option value='n'>{tr}No{/tr}</option>
					<option value='y'>{tr}Yes{/tr}</option>
					</select>
				</td>
				<td><input class="form-control" type="text" name="scaling_divisor" value="1" size="6"></td>
			</tr>
			<tr>
				<td colspan="6"><input class="btn btn-primary" type="submit" name="update_types" value="{tr}Save{/tr}"><input type="hidden" name="userfilter" value="{$userfilter|escape}"></td>
			</tr>
		</table>
	</div>
</form>

<h2>{tr}Purge Expired and Used Credits (All Users){/tr}</h2>
<form method="post" action="tiki-admin_credits.php">
	<input class="btn btn-primary" type="submit" name="purge_credits" value="{tr}Purge{/tr}">
</form>
