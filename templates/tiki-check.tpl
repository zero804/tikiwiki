{* $Id$ *}
{title help="Server Check"}{tr}Server Check{/tr}{/title}

<h2>{tr}MySQL or MariaDB Database Properties{/tr}</h2>
<form method="post" action="tiki-check.php">
<input class="registerSubmit" type="submit" class="btn btn-default" name="acknowledge" value="{tr}Acknowledge{/tr}">
<div class="table-responsive">
	<table class="table table-striped table-hover">
		<tr>
			<th>{tr}Property{/tr}</th>
			<th>{tr}Value{/tr}</th>
			<th>{tr}Tiki Fitness{/tr}</th>
			<th>{tr}Acknowledge{/tr}</th>
			<th>{tr}Explanation{/tr}</th>
		</tr>

		{foreach from=$mysql_properties key=key item=item}
			<tr>
				<td class="text">{$key}</td>
				<td class="text">{$item.setting}</td>
				<td class="text">
					<span class="text-{$fmap[$item.fitness]['class']}">
						{icon name="{$fmap[$item.fitness]['icon']}"} {$item.fitness}
					</span>
				</td>
				<td class="text"><input type="checkbox" name="{$key}" {if $item.fitness eq 'good'}disabled{/if} {if $item.ack}checked{/if} /></td>
				<td class="text">{$item.message}</td>
			</tr>
		{foreachelse}
			{norecords _colspan=4}
		{/foreach}
	</table>
</div>

{if $engineTypeNote}
	{remarksbox type="note" title="{tr}New database engine{/tr}"}{tr}Your website is using a 18.x or higher version of tiki wiki and your database tables are not using the InnoDB database engine, you should consider migrate to InnoDB, that is now the default database engine for Tiki{/tr}{/remarksbox}
{/if}

<h2>{tr}MySQL crashed Tables{/tr}</h2>
{remarksbox type="note" title="{tr}Be careful{/tr}"}{tr}The following list is just a very quick look at SHOW TABLE STATUS that tells you, if tables have been marked as crashed. If you are experiencing database problems you should still run CHECK TABLE or myisamchk to make sure{/tr}.{/remarksbox}
<div class="table-responsive">
	<table class="table">
		<tr>
			<th>{tr}Table{/tr}</th>
			<th>{tr}Comment{/tr}</th>
		</tr>

		{foreach from=$mysql_crashed_tables key=key item=item}
			<tr>
				<td class="text">{$key}</td>
				<td class="text">{$item.Comment}</td>
			</tr>
		{foreachelse}
			{norecords _colspan=2}
		{/foreach}
	</table>
</div>

<h2>{tr}Test sending emails{/tr}</h2>
{tr}To test if your installation is capable of sending emails please visit the <a href="tiki-install.php">Tiki Installer</a>{/tr}.

<h2>{tr}Server Information{/tr}</h2>
<div class="table-responsive">
	<table class="table">
		<tr>
			<th>{tr}Property{/tr}</th>
			<th>{tr}Value{/tr}</th>
		</tr>

		{foreach from=$server_information key=key item=item}
			<tr>
				<td class="text">{$key}</td>
				<td class="text">{$item.value}</td>
			</tr>
		{foreachelse}
			{norecords _colspan=2}
		{/foreach}
	</table>
</div>

<h2>{tr}Server Properties{/tr}</h2>
<div class="table-responsive">
	<table class="table">
		<tr>
			<th>{tr}Property{/tr}</th>
			<th>{tr}Value{/tr}</th>
			<th>{tr}Tiki Fitness{/tr}</th>
			<th>{tr}Acknowledge{/tr}</th>
			<th>{tr}Explanation{/tr}</th>
		</tr>

		{foreach from=$server_properties key=key item=item}
			<tr>
				<td class="text">{$key}</td>
				<td class="text">{$item.setting}</td>
				<td class="text">
					<span class="text-{$fmap[$item.fitness]['class']}">
						{icon name="{$fmap[$item.fitness]['icon']}"} {$item.fitness}
					</span>
				</td>
				<td class="test"><input type="checkbox" name="{$key}" {if $item.fitness eq 'good'}disabled{/if} {if $item.ack}checked{/if} /></td>
				<td class="text">{$item.message}</td>
			</tr>
		{foreachelse}
			{norecords _colspan=4}
		{/foreach}
	</table>
</div>

<h2>{tr}Special directories{/tr}</h2>
{tr}To backup these directories go to <a href="tiki-admin_system.php">Admin->Tiki Cache/SysAdmin</a>{/tr}.
{if count($dirs)}
	<div class="table-responsive">
		<table class="table">
			<tr>
				<th>{tr}Directory{/tr}</th>
				<th>{tr}Fitness{/tr}</th>
				<th>{tr}Explanation{/tr}</th>
			</tr>

			{foreach from=$dirs item=d key=k}
				<tr>
					<td class="text">{$d|escape}</td>
					<td class="text">
						{if $dirsWritable[$k]}
							{icon name='ok' iclass='text-success'}
						{else}
							{icon name='remove' iclass='text-danger'}
						{/if}
					</td>
					<td>
						{if $dirsWritable[$k]}
							{tr}Directory is writeable{/tr}.
						{else}
							{tr}Directory is not writeable!{/tr}
						{/if}
					</td>
				</tr>
			{/foreach}
		</table>
	</div>
{/if}


<h2>{tr}Apache properties{/tr}</h2>
{if $apache_properties}
	<div class="table-responsive">
		<table class="table">
			<tr>
				<th>{tr}Property{/tr}</th>
				<th>{tr}Value{/tr}</th>
				<th>{tr}Tiki Fitness{/tr}</th>
				<th>{tr}Acknowledge{/tr}</th>
				<th>{tr}Explanation{/tr}</th>
			</tr>

			{foreach from=$apache_properties key=key item=item}
				<tr>
					<td class="text">{$key}</td>
					<td class="text">{$item.setting}</td>
					<td class="text">
					<span class="text-{$fmap[$item.fitness]['class']}">
						{icon name="{$fmap[$item.fitness]['icon']}"} {$item.fitness}
					</span>
					</td>
					<td class="test"><input type="checkbox" name="{$key}" {if $item.fitness eq 'good'}disabled{/if} {if $item.ack}checked{/if} /></td>
					<td class="text">{$item.message}</td>
				</tr>
			{foreachelse}
				{norecords _colspan=4}
			{/foreach}
		</table>
	</div>
{else}
	{$no_apache_properties}
{/if}

<h2>{tr}IIS properties{/tr}</h2>
{if $iis_properties}
	<div class="table-responsive">
		<table class="table">
			<tr>
				<th>{tr}Property{/tr}</th>
				<th>{tr}Value{/tr}</th>
				<th>{tr}Tiki Fitness{/tr}</th>
				<th>{tr}Acknowledge{/tr}</th>
				<th>{tr}Explanation{/tr}</th>
			</tr>

			{foreach from=$iis_properties key=key item=item}
				<tr>
					<td class="text">{$key}</td>
					<td class="text">{$item.setting}</td>
					<td class="text">
					<span class="text-{$fmap[$item.fitness]['class']}">
						{icon name="{$fmap[$item.fitness]['icon']}"} {$item.fitness}
					</span>
					</td>
					<td class="test"><input type="checkbox" name="{$key}" {if $item.fitness eq 'good'}disabled{/if} {if $item.ack}checked{/if} /></td>
					<td class="text">{$item.message}</td>
				</tr>
			{foreachelse}
				{norecords _colspan=4}
			{/foreach}
		</table>
	</div>
{else}
	{$no_iis_properties}
{/if}

<h2>{tr}PHP scripting language properties{/tr}</h2>
<div class="table-responsive">
	<table class="table">
		<tr>
			<th>{tr}Property{/tr}</th>
			<th>{tr}Value{/tr}</th>
			<th>{tr}Tiki Fitness{/tr}</th>
			<th>{tr}Acknowledge{/tr}</th>
			<th>{tr}Explanation{/tr}</th>
		</tr>

		{foreach from=$php_properties key=key item=item}
			<tr>
				<td class="text">{$key}</td>
				<td class="text">{$item.setting}</td>
				<td class="text">
					<span class="text-{$fmap[$item.fitness]['class']}">
						{icon name="{$fmap[$item.fitness]['icon']}"} {$item.fitness}
					</span>
				</td>
				<td class="test"><input type="checkbox" name="{$key}" {if $item.fitness eq 'good'}disabled{/if} {if $item.ack}checked{/if} /></td>
				<td class="text">{$item.message}</td>
			</tr>
		{foreachelse}
			{norecords _colspan=4}
		{/foreach}
	</table>
</div>

{remarksbox type="note" id="php_conf_info" title="{tr}Change PHP configuration values{/tr}"}
	{if $php_sapi_info}
		<p>
		{if $php_sapi_info.message}
			{tr}{$php_sapi_info.message}{/tr}
		{/if}
		{if $php_sapi_info.link}
			{tr}<a href="{$php_sapi_info.link}">{$php_sapi_info.link}</a>{/tr}
		{/if}
		</p>
	{/if}

	<p>
		{tr}You can check the full documentation on how to change the configurations values in <a href="http://www.php.net/manual/en/configuration.php">http://www.php.net/manual/en/configuration.php</a>{/tr}
	</p>
{/remarksbox}

<h2>{tr}PHP Security properties{/tr}</h2>
{tr}To check the file integrity of your Tiki installation, go to <a href="tiki-admin_security.php">Admin->Security</a>{/tr}.
<div class="table-responsive">
	<table class="table">
		<tr>
			<th>{tr}Property{/tr}</th>
			<th>{tr}Value{/tr}</th>
			<th>{tr}Tiki Fitness{/tr}</th>
			<th>{tr}Acknowledge{/tr}</th>
			<th>{tr}Explanation{/tr}</th>
		</tr>

		{foreach from=$security key=key item=item}
			<tr>
				<td class="text">{$key}</td>
				<td class="text">{$item.setting}</td>
				<td class="text">
					<span class="text-{$fmap[$item.fitness]['class']}">
						{icon name="{$fmap[$item.fitness]['icon']}"} {$item.fitness}
					</span>
				</td>
				<td class="test"><input type="checkbox" name="{$key}" {if $item.fitness eq 'safe'}disabled{/if} {if $item.ack}checked{/if} /></td>
				<td class="text">{$item.message}</td>
			</tr>
		{foreachelse}
			{norecords _colspan=4}
		{/foreach}
	</table>
</div>
</form>

<h2>{tr}Tiki Security{/tr}</h2>
{assign var=sensitive_data_box_title value="{tr}Sensitive Data Exposure{/tr}"}
{if $sensitive_data_detected_files}
{remarksbox type='error' title="{$sensitive_data_box_title}" close='n'}
	<p>{tr}Tiki detected that there are temporary files in the db folder which may expose credentials or other sensitive information.{/tr}</p>
	<ul>
		{foreach from=$sensitive_data_detected_files item=file}
			<li>
				{$file}
			</li>
		{/foreach}
	</ul>
{/remarksbox}
{else}
{remarksbox type='info' title="{$sensitive_data_box_title}" close='n'}
	<p>{tr}Tiki did not detect temporary files in the db folder which may expose credentials or other sensitive information.{/tr}</p>
{/remarksbox}
{/if}

{if $prefs.print_pdf_from_url === "mpdf" && !empty($mPDFClassMissing)}
	<h2>{tr}Print configurations{/tr}</h2>
	{remarksbox type='error' title="{tr}mPDF Information{/tr}" close='n'}
		<p>{tr}mPDF is selected as Print option, however the class can't be loaded, please check "Print Settings" in /tiki-admin.php?page=print{/tr}</p>
	{/remarksbox}
{/if}

<h2>{tr}File Gallery Search Indexing{/tr}</h2>
{icon name='help' href='https://doc.tiki.org/Search+within+files'} <em>{tr _0='<a href="https://doc.tiki.org/Search+within+files">' _1='</a>'}More information %0 here %1{/tr}</em>
{if $prefs.fgal_enable_auto_indexing eq 'y'}
	{if $security.shell_exec.setting eq 'Disabled'}
		{remarksbox type='error' title='{tr}Command Missing{/tr}' close='n'}
			<p>{tr}The command "shell_exec" is required for file gallery search indexing{/tr}</p>
		{/remarksbox}
	{/if}
	<div class="table-responsive">
		<table class="table">
			<tr>
				<th>{tr}Mimetype{/tr}</th>
				<th>{tr}Tiki Fitness{/tr}</th>
				<th>{tr}Explanation{/tr}</th>
			</tr>

			{foreach from=$file_handlers key=key item=item}
				<tr>
					<td class="text">{$key}</td>
					<td class="text">
						<span class="text-{$fmap[$item.fitness]['class']}">
							{icon name="{$fmap[$item.fitness]['icon']}"} {$item.fitness}
						</span>
					</td>
					<td class="text">{$item.message|escape}</td>
				</tr>
			{foreachelse}
				{norecords _colspan=3}
			{/foreach}
		</table>
	</div>
{else}
	{remarksbox type='info' title='{tr}Feature disabled{/tr}' close='n'}
		<p>{tr _0='<a href="tiki-admin.php?page=fgal">' _1='</a>'}Go to the %0 File Gallery Control Panel %1 (with advanced preferences showing) to enable{/tr}</p>
	{/remarksbox}
{/if}

<h2>{tr}MySQL Variable Information{/tr}</h2>
<div class="table-responsive">
	<table class="table">
		<tr>
			<th>{tr}Property{/tr}</th>
			<th>{tr}Value{/tr}</th>
		</tr>

		{foreach from=$mysql_variables key=key item=item}
			<tr>
				<td class="text">{$key}</td>
				<td class="text">{$item.value|escape}</td>
			</tr>
		{foreachelse}
			{norecords _colspan=2}
		{/foreach}
	</table>
</div>

<h2>{tr}PHP Info{/tr}</h2>
{tr}For more detailed information about your PHP installation see <a href="tiki-phpinfo.php">Admin->phpinfo</a>{/tr}.

<a name="benchmark"></a>
<h2>{tr}Benchmark PHP/MySQL{/tr}</h2>
<a href="tiki-check.php?benchmark=run&ts={$smarty.now}#benchmark" class="btn btn-primary btn-sm" style="margin-bottom: 10px;">{tr}Check{/tr}</a>
{if !empty($benchmark)}
	<br />
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<tr>
				<th>{tr}Property{/tr}</th>
				<th>{tr}Value{/tr}</th>
			</tr>

			{foreach from=$benchmark key=key item=item}
				<tr>
					<td class="text">{$key}</td>
					<td class="text">{$item.value}</td>
				</tr>
			{/foreach}
		</table>
	</div>
{/if}

<a name="bomscanner"></a>
<h2>{tr}BOM Detected Files{/tr}</h2>
<a href="tiki-check.php?bomscanner=run&ts={$smarty.now}#bomscanner" class="btn btn-primary btn-sm" style="margin-bottom: 10px;">{tr}Check{/tr}</a>
{if $bomscanner}
	<p>{tr}Scanned files:{/tr} {$bom_total_files_scanned}</p>
	{if ! empty($bom_detected_files)}
		<p>{tr}BOM files detected:{/tr}</p>
		<ul>
			{foreach from=$bom_detected_files item=file}
				<li>
					{$file}
				</li>
			{/foreach}
		</ul>
	{else}
		<p><span class="icon icon-information fas fa-info-circle fa-fw"></span>&nbsp;{tr}No BOM files detected{/tr}</p>
	{/if}
{/if}

<h2>{tr}TRIM{/tr}</h2>
{tr}For more detailed information about Tiki Remote Instance Manager please check <a href="https://doc.tiki.org/TRIM">doc.tiki.org</a>{/tr}.

{if trim_capable}
	<h3>Server Instance</h3>
	<div class="table-responsive">
		<table class="table">
			<tr>
				<th>{tr}Requirements{/tr}</th>
				<th>{tr}Status{/tr}</th>
				<th>{tr}Message{/tr}</th>
			</tr>
			{foreach from=$trim_server_requirements key=key item=item}
				<tr>
					<td class="text">{$key}</td>
					<td class="text">
					<span class="text-{$fmap[$item.fitness]['class']}">
						{icon name="{$fmap[$item.fitness]['icon']}"} {$item.fitness}
					</span>
					</td>
					<td class="text">{$item.message}</td>
				</tr>
			{/foreach}
		</table>
	</div>

	<h3>Client Instance</h3>
	<div class="table-responsive">
		<table class="table">
			<tr>
				<th>{tr}Requirements{/tr}</th>
				<th>{tr}Status{/tr}</th>
				<th>{tr}Message{/tr}</th>
			</tr>
			{foreach from=$trim_client_requirements key=key item=item}
				<tr>
					<td class="text">{$key}</td>
					<td class="text">
					<span class="text-{$fmap[$item.fitness]['class']}">
						{icon name="{$fmap[$item.fitness]['icon']}"} {$item.fitness}
					</span>
					</td>
					<td class="text">{$item.message}</td>
				</tr>
			{/foreach}
		</table>
	</div>
{else}
	{remarksbox type='error' title='{tr}OS not supported{/tr}' close='n'}
		<p>{tr}Apparently tiki is running on a Windows based server. This feature is not supported natively.{/tr}</p>
	{/remarksbox}
{/if}

<h2>{tr}User Data Encryption{/tr}</h2>
{if users_with_mcrypt_data > 0}
	{remarksbox type='error' title='{tr}MCrypt encryption found in users data preferences{/tr}' close='n'}
		<p>{tr _0=$users_with_mcrypt_data}Found %0 user(s) with data preferences encrypted with MCrypt.{/tr}</p>
		<p>If MCrypt library gets removed, non-converted user encrypted data can no longer be decrypted. The data is
			thus lost and must be re-entered.</p>
	{/remarksbox}
{else}
	<p>{tr}No user preferences were found with data encrypted with MCrypt.{/tr}</p>
{/if}

<h2>{tr}Tiki Packages{/tr}</h2>
{if ! $composer_available}
	{remarksbox type="warning" title="{tr}Composer not found{/tr}"}
		{tr}Composer could not be executed, so the automated check on the packages cannot be performed.{/tr}
	{/remarksbox}
{/if}
<div class="table-responsive">
	<table class="table">
		<tr>
			<th>{tr}Package Name{/tr}</th>
			<th>{tr}Version{/tr}</th>
			<th>{tr}Status{/tr}</th>
			<th>{tr}Message{/tr}</th>
		</tr>

		{foreach from=$packages key=key item=item}
			<tr>
				<td class="text">{$item.name}</td>
				<td class="text">{$item.version}</td>
				<td class="text">
					<span class="text-{$fmap[$item.status]['class']}">
						{icon name="{$fmap[$item.status]['icon']}"} {$item.status}
					</span>
				</td>
				<td class="text">
                    {foreach from=$item.message key=message_key item=message}
						{$message}<br/>
                    {/foreach}
				</td>
			</tr>
		{foreachelse}
			{norecords _colspan=4}
		{/foreach}
	</table>
</div>


<h2>{tr}OCR Status{/tr}</h2>
<div class="table-responsive">
	<table class="table">
		<tr>
			<th>{tr}Requirements{/tr}</th>
			<th>{tr}Version{/tr}</th>
			<th>{tr}Status{/tr}</th>
			<th>{tr}Message{/tr}</th>
		</tr>

		{foreach from=$ocr key=key item=item}
			<tr>
				<td class="text">{$item.name}</td>
				<td class="text">{$item.version}</td>
				<td class="text">
					<span class="text-{$fmap[$item.status]['class']}">
						{icon name="{$fmap[$item.status]['icon']}"} {$item.status}
					</span>
				</td>
				<td class="text">{$item.message}</td>
			</tr>
		{/foreach}
	</table>
</div>
