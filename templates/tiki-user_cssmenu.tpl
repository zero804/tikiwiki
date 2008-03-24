{* $Header: /cvsroot/tikiwiki/tiki/templates/tiki-user_cssmenu.tpl,v 1.1.2.9 2008-01-30 15:33:51 nyloth Exp $ *}
{if count($menu_channels) > 0}
{assign var=opensec value='0'}
{assign var=sep value=''}

<ul id="cssmenu{$idCssmenu}" class="cssmenu{if $menu_type}_{$menu_type}{/if} menu{$menu_info.menuId}">

{foreach key=pos item=chdata from=$menu_channels}

{* ----------------------------- section *}
{if $chdata.type ne 'o' and  $chdata.type ne '-'}

{if $opensec > 0}
{assign var=sectionType value=$chdata.type}
{php}
global $smarty;
$opensec = $smarty->get_template_vars('opensec');
$sectionType= $smarty->get_template_vars('sectionType');
if ($sectionType == 's' or $sectionType == 'r') {
	$sectionType = 0;
}
while ($opensec > $sectionType) {
	--$opensec;
	echo '</ul></li>';
}
$smarty->assign('opensec', $opensec);
{/php}
{/if}

<li class="option{$chdata.optionId} menuSection menuSection{$opensec} menuLevel{$opensec}{if $chdata.selected} selected{/if}">
{if $icon}{icon _id='folder' align="left"}{/if}
{if $chdata.url and $link_on_section ne 'n'}<a href="{if $prefs.feature_sefurl eq 'y' and $chdata.sefurl}{$chdata.sefurl}{else}{$chdata.url}{/if}">{/if}
{tr}{$chdata.name}{/tr}
{if $chdata.url and $link_on_section ne 'n'}</a>{/if}

{assign var=opensec value=$opensec+1}
<ul>

{* ----------------------------- option *}
{elseif $chdata.type eq 'o'}
<li class="option{$chdata.optionId} menuOption menuLevel{$opensec}{if $chdata.selected} selected{/if}"><a href="{if $prefs.feature_sefurl eq 'y' and $chdata.sefurl}{$chdata.sefurl}{else}{$chdata.url}{/if}">{tr}{$chdata.name}{/tr}</a></li>
{if $sep eq 'line'}{assign var=sep value=''}{/if}

{* ----------------------------- separator *}
{elseif $chdata.type eq '-'}
{if $opensec > 0}</ul></li>{assign var=opensec value=$opensec-1}{/if}
{assign var=sep value="line"}
{/if}

{/foreach}

{if $opensec > 0}
{php}
global $smarty;
$opensec = $smarty->get_template_vars('opensec');
while ($opensec) {
	--$opensec;
	echo '</ul></li>';
}
{/php}
{/if}

</ul>
{/if}