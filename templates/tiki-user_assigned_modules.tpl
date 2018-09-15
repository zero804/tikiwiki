{title help="UserAssignedModules"}{tr}User assigned modules{/tr}{/title}

{include file='tiki-mytiki_bar.tpl'}

<div class="t_navbar">
	{button href="tiki-user_assigned_modules.php?recreate=1" class="btn btn-default" _text="{tr}Restore defaults{/tr}"}
</div>

<h2>{tr}User assigned modules{/tr}</h2>
<table >
	<tr>
		{if $prefs.feature_left_column ne 'n' || count($modules_l) > 0}
			<td >
				<b>{tr}Left column{/tr}</b>
				{if $prefs.feature_left_column eq 'n' and count($modules_l) > 0}<br><span class="highlight">{tr}The column is disabled{/tr}</span>{/if}
			</td>
		{/if}
		{if $prefs.feature_right_column ne 'n' || count($modules_r) > 0}
			<td >
				<b>{tr}Right column{/tr}</b>
				{if $prefs.feature_right_column eq 'n' and count($modules_r) > 0}<br><span class="highlight">{tr}The column is disabled{/tr}</span>{/if}
			</td>
		{/if}
	</tr>
	<tr>
		<!-- left column -->
		{if $prefs.feature_left_column ne 'n' || count($modules_l) > 0}
			<td style="vertical-align: top">
				<table class="table table-striped table-hover">
					<tr>
						<th>{tr}#{/tr}</th>
						<th>{tr}Name{/tr}</th>
						<th></th>
					</tr>

					{section name=ix loop=$modules_l}
						<tr>
							<td>{$modules_l[ix].ord}</td>
							<td>{$modules_l[ix].name}</td>
							<td>
								<form action="tiki-user_assigned_modules.php" method="post">
									<input type="hidden" name="redirect" value="1">
									<button
											type="submit"
											name="up"
											value="{$modules_l[ix].moduleId}"
											class="tips btn btn-link"
											style="padding: 0;"
											title=":{tr}Move module up{/tr}"
									>
										{icon name="up"}
									</button>
									<button
											type="submit"
											name="down"
											value="{$modules_l[ix].moduleId}"
											class="tips btn btn-link"
											style="padding: 0;"
											title=":{tr}Move module down{/tr}"
									>
										{icon name="down"}
									</button>
									{if $prefs.feature_right_column ne 'n'}
										<button
												type="submit"
												name="right"
												value="{$modules_l[ix].moduleId}"
												class="tips btn btn-link"
												style="padding: 0;"
												title=":{tr}Move to right side{/tr}"
										>
											{icon name="next"}
										</button>
									{/if}
									{if $modules_r[ix].name ne 'application_menu' and $modules_r[ix].name ne 'login_box' and $modules_r[ix].type ne 'P'}
										<button
												type="submit"
												name="unassign"
												value="{$modules_l[ix].moduleId}"
												class="tips btn btn-link"
												style="padding: 0;"
												title=":{tr}Unassign{/tr}"
										>
											{icon name="remove"}
										</button>
									{/if}
								</form>
							</td>
						</tr>
					{/section}
				</table>
			</td>
		{/if}
		<!-- right column -->
		{if $prefs.feature_right_column ne 'n' || count($modules_r) > 0}
			<td style="vertical-align: top">
				<table class="table table-striped table-hover">
					<tr>
						<th>{tr}#{/tr}</th>
						<th>{tr}Name{/tr}</th>
						<th></th>
					</tr>

					{section name=ix loop=$modules_r}
						<tr>
							<td>{$modules_r[ix].ord}</td>
							<td>{$modules_r[ix].name}</td>
							<td>
								<form action="tiki-user_assigned_modules.php" method="post">
									<input type="hidden" name="redirect" value="1">
									<button
										type="submit"
										name="up"
										value="{$modules_r[ix].moduleId}"
										class="tips btn btn-link"
										style="padding: 0;"
										title=":{tr}Move module up{/tr}"
									>
										{icon name="up"}
									</button>
									<button
										type="submit"
										name="down"
										value="{$modules_r[ix].moduleId}"
										class="tips btn btn-link"
										style="padding: 0;"
										title=":{tr}Move module down{/tr}"
									>
										{icon name="down"}
									</button>
									{if $prefs.feature_left_column ne 'n'}
										<button
											type="submit"
											name="left"
											value="{$modules_r[ix].moduleId}"
											class="tips btn btn-link"
											style="padding: 0;"
											title=":{tr}Move to left side{/tr}"
										>
											{icon name="previous"}
										</button>
									{/if}
									{if $modules_r[ix].name ne 'application_menu' and $modules_r[ix].name ne 'login_box' and $modules_r[ix].type ne 'P'}
										<button
											type="submit"
											name="unassign"
											value="{$modules_r[ix].moduleId}"
											class="tips btn btn-link"
											style="padding: 0;"
											title=":{tr}Unassign{/tr}"
										>
											{icon name="remove"}
										</button>
									{/if}
								</form>
							</td>
						</tr>
					{/section}
				</table>
			</td>
		{/if}
	</tr>
</table>

{if $canassign eq 'y'}
	<h2>{tr}Assign module{/tr}</h2>
	<form action="tiki-user_assigned_modules.php" method="post" class="form-horizontal">
		<div class="form-group">
			<label class="control-label col-sm-4" for="module">{tr}Module{/tr}</label>
			<div class="col-sm-8">
				<select name="module" class="form-control">
					{section name=ix loop=$assignables}
						<option value="{$assignables[ix].moduleId|escape}">{$assignables[ix].name}</option>
					{/section}
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-4" for="position">{tr}Column{/tr}</label>
			<div class="col-sm-8">
				<select name="position" class="form-control">
					{if $prefs.feature_left_column ne 'n'}<option value="left">{tr}Left{/tr}</option>{/if}
					{if $prefs.feature_right_column ne 'n'}<option value="right">{tr}Right{/tr}</option>{/if}
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-4" for="order">{tr}Order{/tr}</label>
			<div class="col-sm-8">
				<select name="order" class="form-control">
					{section name=ix loop=$orders}
						<option value="{$orders[ix]|escape}">{$orders[ix]}</option>
					{/section}
				</select>
			</div>
		</div>
		<div class="form-group">
			<div class="control-label col-sm-8">
				<input type="submit" class="btn btn-default" name="assign" value="{tr}Assign{/tr}">
			</div>
		</div>
	</form>
{/if}
