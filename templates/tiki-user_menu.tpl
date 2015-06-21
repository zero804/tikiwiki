{* $Id$ *}
<div class="tikimenu{if $menu_info.structure eq 'y'} structuremenu{/if}">
	{assign var=opensec value='0'}
	{assign var=sep value=''}
	
	{foreach key=pos item=chdata from=$menu_channels}
		{assign var=cname value=$menu_info.menuId|cat:'__'|cat:$chdata.position}
		{* ----------------------------- section *}
		{if $chdata.type ne 'o' and  $chdata.type ne '-'}
			
			{if $opensec > 0}
				{assign var=sectionType value=$chdata.type}
				{if $sectionType eq 's' or $sectionType eq 'r'}
					{assign var=sectionType value=0}
				{/if}
				{if $opensec > $sectionType}
					{assign var=m value=$opensec-$sectionType}
					{section loop=$menu_channels name=close max=$m}
						   </div>
					{/section}
					{assign var=opensec value=$sectionType}
				{/if}
			{/if}
			
			<div class="separator{$sep}{if isset($chdata.selected) and $chdata.selected} selected{/if}{if isset($chdata.selectedAscendant) and $chdata.selectedAscendant} selectedAscendant{/if}">
				{if $sep eq 'line'}
					{assign var=sep value=''}
				{/if}
				{if (isset($chdata.open) && $chdata.open) || (!isset($chdata.open) && $menu_info.type !== 'd')}
					{$open = 'inline'}
					{$closed = 'none'}
				{else}
					{$open = 'none'}
					{$closed = 'inline'}
				{/if}
				{if $menu_info.type eq 'e' or $menu_info.type eq 'd'}
					{if $prefs.menus_items_icons eq 'y' and $menu_info.use_items_icons eq 'y'}
						<span class="separatoricon-toggle" style="display:inline">
							<a class='separator' href="javascript:toggle('menu{$cname}');">
								{icon _id=$chdata.icon alt="{tr}Toggle{/tr}" _defaultdir=$prefs.menus_items_icons_path}
							</a>
						</span>
						{if $chdata.url and $link_on_section eq 'y'}
							<span class="separatoricon-url" style="display:none">
								<a href="{if $prefs.feature_sefurl eq 'y' and !empty($chdata.sefurl)}{$chdata.sefurl|escape}{else}{if $prefs.menus_item_names_raw eq 'n'}{$chdata.url|escape}{else}{$chdata.url}{/if}{/if}">
									{icon _id=$chdata.icon alt="{tr}Toggle{/tr}" _defaultdir=$prefs.menus_items_icons_path}
								</a>
							</span>
						{/if}
					{elseif $prefs.feature_menusfolderstyle eq 'y'}
						{* these anchor tags need to be in a single long line to avoid stray underlining upon hover *}
						<a class='separator' href="#" onclick="icontoggle('menu{$cname}', this); return false;" title="{tr}Toggle options{/tr}" id="sep{$cname}">{if empty($menu_info.icon)}<span class="toggle-open" style="display:{$open}">{icon _id="ofolder" alt='Toggle'}</span><span class="toggle-closed" style="display:{$closed}">{icon _id="folder" alt='Toggle'}</span>{else}<span class="toggle-open" style="display:{$open}"><img src="{$menu_info.oicon|escape}" alt="{tr}Toggle{/tr}"></span><span class="toggle-closed" style="display:{$closed}"><img src="{$menu_info.icon|escape}" alt="{tr}Toggle{/tr}"></span>{/if}</a>
					{else}
						<a class='separator' href="#" onclick="icontoggle('menu{$cname}', this); return false;" title="{tr}Toggle options{/tr}" id="sep{$cname}"><span class="toggle-open" style="display:{$open}">[-]</span><span class="toggle-closed" style="display:{$closed}">[+]</span>
					{/if}
				{else}
					{if empty($menu_info.icon)}
						{icon _id="ofolder" alt='Toggle'}
					{else}
						<img src="{$menu_info.oicon|escape}" alt="{tr}Toggle{/tr}">
					{/if}
				{/if}
				{if $chdata.url and $link_on_section eq 'y'}
					<a href="{if $prefs.feature_sefurl eq 'y' and !empty($chdata.sefurl)}{$chdata.sefurl|escape}{else}{if $prefs.menus_item_names_raw eq 'n'}{$chdata.url|escape}{else}{$chdata.url}{/if}{/if}" class="separator">
				{else}
					<a href="#" onclick="icontoggle('menu{$cname}', this); return false;" class="separator" id="sep{$cname}">
				{/if}
					<span class="menuText">
						{if $translate eq 'n'}
							{if $escape_menu_labels}{$chdata.name|escape}{else}{$chdata.name}{/if}
						{else}
							{tr}{if $escape_menu_labels}{$chdata.name|escape}{else}{$chdata.name}{/if}{/tr}
						{/if}
					</span>
				</a>
			</div> {* separator *}

			{assign var=opensec value=$opensec+1}
			{if $menu_info.type eq 'e' or $menu_info.type eq 'd'}
				<div class="menuSection" style="display:{if $open === 'inline'}block{else}none{/if}" id='menu{$cname}'>
			{else}
				<div class="menuSection">
			{/if}
		{* ----------------------------- option *}
		{elseif $chdata.type eq 'o'}
			<div class="option{$chdata.optionId} option{$sep}{if isset($chdata.selected) and $chdata.selected} selected{/if}">
				<a href="{if $prefs.feature_sefurl eq 'y' and !empty($chdata.sefurl)}{$chdata.sefurl|escape}{else}{if $prefs.menus_item_names_raw eq 'n'}{$chdata.url|escape}{else}{$chdata.url}{/if}{/if}" class="linkmenu">
					{if $prefs.menus_items_icons eq 'y' and $menu_info.use_items_icons eq 'y' and ($opensec eq 0 or $chdata.icon neq '')}
						{icon _id=$chdata.icon alt='' _defaultdir=$prefs.menus_items_icons_path}
					{/if}
					<span class="menuText">
						{if $translate eq 'n'}
							{if $escape_menu_labels}{$chdata.name|escape}{else}{$chdata.name}{/if}
						{else}
							{tr}{if $escape_menu_labels}{$chdata.name|escape}{else}{$chdata.name}{/if}{/tr}
						{/if}
					</span>
				</a>
			</div>
			{if $sep eq 'line'}
				{assign var=sep value=''}
			{/if}
			
		{* ----------------------------- separator *}
		{elseif $chdata.type eq '-'}
			{if $opensec > 0}
				</div>{assign var=opensec value=$opensec-1}
			{/if}
			{assign var=sep value="line"}
		{/if}
	{/foreach}
	
	{if $opensec > 0}
		{section loop=$menu_channels name=close max=$opensec}
			</div>
		{/section}
		{assign var=opensec value=0}
	{/if}
</div>
