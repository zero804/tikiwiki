{* $Id$ *}<!DOCTYPE html>
<html lang="{if !empty($pageLang)}{$pageLang}{else}{$prefs.language}{/if}"{if $prefs.feature_bidi eq 'y'} dir="rtl"{/if}{if !empty($page_id)} id="page_{$page_id}"{/if}>
<head>
	{include file='header.tpl'}
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body{html_body_attributes}>
{$cookie_consent_html}

{include file="layout_fullscreen_check.tpl"}

{if $prefs.feature_ajax eq 'y'}
	{include file='tiki-ajax_header.tpl'}
{/if}

{if $prefs.feature_layoutshadows eq 'y'}
<div id="main-shadow">{eval var=$prefs.main_shadow_start}{/if}

	{if !isset($smarty.session.fullscreen) || $smarty.session.fullscreen ne 'y'}
	{if $prefs.feature_layoutshadows eq 'y'}
	<div id="header-shadow">{eval var=$prefs.header_shadow_start}{/if}
		<div class="header_outer" id="header_outer">
			<div class="header_container">
				<div class="container{if $smarty.session.fullscreen eq 'y'}-fluid{/if}">
					<header class="header page-header" id="page-header">
						{modulelist zone=top class='top_modules d-flex justify-content-between'}
					</header>
				</div>
			</div>
		</div>
		{if $prefs.feature_layoutshadows eq 'y'}{eval var=$prefs.header_shadow_end}</div>{/if}
{/if}

	<div class="middle_outer" id="middle_outer" >
		<div class="container{if $smarty.session.fullscreen eq 'y'}-fluid{/if} clearfix middle" id="middle">
			<div class="topbar bg-dark" id="topbar">
				{modulelist zone=topbar class='topbar_modules d-flex justify-content-between'}
			</div>
			<div class="row row-middle" id="row-middle">
				{if (zone_is_empty('left') or $prefs.feature_left_column eq 'n') and (zone_is_empty('right') or $prefs.feature_right_column eq 'n')}
					<div class="col col1 col-md-12" id="col1">

						{if $prefs.feature_layoutshadows eq 'y'}
						<div id="tiki-center-shadow">{eval var=$prefs.center_shadow_start}{/if}
							{if $prefs.module_zones_pagetop eq 'fixed' or ($prefs.module_zones_pagetop ne 'n' && ! zone_is_empty('pagetop'))}
								{modulelist zone=pagetop}
							{/if}
							{feedback}
							{block name=quicknav}{/block}
							{block name=title}{/block}
							{block name=navigation}{/block}
							{block name=content}{/block}
							{if $prefs.module_zones_pagebottom eq 'fixed' or ($prefs.module_zones_pagebottom ne 'n' && ! zone_is_empty('pagebottom'))}
								{modulelist zone=pagebottom}
							{/if}
							{if $prefs.feature_layoutshadows eq 'y'}{eval var=$prefs.center_shadow_end}</div>{/if}

					</div>
				{elseif zone_is_empty('left') or $prefs.feature_left_column eq 'n'}
				{if $prefs.feature_right_column eq 'user'}
					<div class="side-col-toggle-container justify-content-end">
						{$icon_name = (not empty($smarty.cookies.hide_zone_right)) ? 'toggle-left' : 'toggle-right'}
						{icon name=$icon_name class='toggle_zone right' href='#' title='{tr}Toggle right modules{/tr}'}
					</div>
				{/if}
					<div class="col col1 col-md-12 col-lg-9" id="col1">
						{if $prefs.feature_layoutshadows eq 'y'}
						<div id="tiki-center-shadow">{eval var=$prefs.center_shadow_start}{/if}
							{if $prefs.module_zones_pagetop eq 'fixed' or ($prefs.module_zones_pagetop ne 'n' && ! zone_is_empty('pagetop'))}
								{modulelist zone=pagetop}
							{/if}
							{feedback}
							{block name=quicknav}{/block}
							{block name=title}{/block}
							{block name=navigation}{/block}
							{block name=content}{/block}
							{if $prefs.module_zones_pagebottom eq 'fixed' or ($prefs.module_zones_pagebottom ne 'n' && ! zone_is_empty('pagebottom'))}
								{modulelist zone=pagebottom}
							{/if}
							{if $prefs.feature_layoutshadows eq 'y'}{eval var=$prefs.center_shadow_end}</div>{/if}
					</div>
					<div class="col col3 col-md-12 col-lg-3" id="col3">
						{modulelist zone=right}
					</div>
				{elseif zone_is_empty('right') or $prefs.feature_right_column eq 'n'}
				{if $prefs.feature_left_column eq 'user'}
					<div class="side-col-toggle-container justify-content-start">
						{$icon_name = (not empty($smarty.cookies.hide_zone_left)) ? 'toggle-right' : 'toggle-left'}
						{icon name=$icon_name class='toggle_zone left' href='#' title='{tr}Toggle left modules{/tr}'}
					</div>
				{/if}
					<div class="col col1 col-md-12 col-lg-9 order-md-1 order-lg-2" id="col1">
						{if $prefs.feature_layoutshadows eq 'y'}
						<div id="tiki-center-shadow">{eval var=$prefs.center_shadow_start}{/if}
							{if $prefs.module_zones_pagetop eq 'fixed' or ($prefs.module_zones_pagetop ne 'n' && ! zone_is_empty('pagetop'))}
								{modulelist zone=pagetop}
							{/if}
							{feedback}
							{block name=quicknav}{/block}
							{block name=title}{/block}
							{block name=navigation}{/block}
							{block name=content}{/block}
							{if $prefs.module_zones_pagebottom eq 'fixed' or ($prefs.module_zones_pagebottom ne 'n' && ! zone_is_empty('pagebottom'))}
								{modulelist zone=pagebottom}
							{/if}
							{if $prefs.feature_layoutshadows eq 'y'}{eval var=$prefs.center_shadow_end}</div>{/if}
					</div>
					<div class="col col2 col-md-12 col-lg-3 order-sm-2 order-md-2 order-lg-1" id="col2">
						{modulelist zone=left}
					</div>
				{else}
				<div class="side-col-toggle-container d-flex">
					{if $prefs.feature_left_column eq 'user'}
						<div class="text-left side-col-toggle flex-fill">
							{$icon_name = (not empty($smarty.cookies.hide_zone_left)) ? 'toggle-right' : 'toggle-left'}
							{icon name=$icon_name class='toggle_zone left' href='#' title='{tr}Toggle left modules{/tr}'}
						</div>
					{/if}
					{if $prefs.feature_right_column eq 'user'}
						<div class="text-right side-col-toggle flex-fill">
							{$icon_name = (not empty($smarty.cookies.hide_zone_right)) ? 'toggle-left' : 'toggle-right'}
							{icon name=$icon_name class='toggle_zone right' href='#' title='{tr}Toggle right modules{/tr}'}
						</div>
					{/if}
				</div>
					<div class="col col1 col-sm-12 col-lg-8 order-xs-1 order-lg-2" id="col1">
						{if $prefs.feature_layoutshadows eq 'y'}
						<div id="tiki-center-shadow">{eval var=$prefs.center_shadow_start}{/if}
							{if $prefs.module_zones_pagetop eq 'fixed' or ($prefs.module_zones_pagetop ne 'n' && ! zone_is_empty('pagetop'))}
								{modulelist zone=pagetop}
							{/if}
							{feedback}
							{block name=quicknav}{/block}
							{block name=title}{/block}
							{block name=navigation}{/block}
							{block name=content}{/block}
							{if $prefs.module_zones_pagebottom eq 'fixed' or ($prefs.module_zones_pagebottom ne 'n' && ! zone_is_empty('pagebottom'))}
								{modulelist zone=pagebottom}
							{/if}
							{if $prefs.feature_layoutshadows eq 'y'}{eval var=$prefs.center_shadow_end}</div>{/if}
					</div>
					<div class="col col2 col-sm-6 col-lg-2 order-md-2 order-lg-1" id="col2">
						{modulelist zone=left}
					</div>
					<div class="col col3 col-sm-6 col-lg-2 order-md-3" id="col3">
						{modulelist zone=right}
					</div>
				{/if}
			</div>
		</div>
	</div>

	{if !isset($smarty.session.fullscreen) || $smarty.session.fullscreen ne 'y'}
	{if $prefs.feature_layoutshadows eq 'y'}
	<div id="footer-shadow">{eval var=$prefs.footer_shadow_start}{/if}
		<footer class="footer main-footer" id="footer">
			<div class="footer_liner">
				<div class="container{if $smarty.session.fullscreen eq 'y'}-fluid{/if}" style="padding-left: 0; padding-right: 0;">
					{modulelist zone=bottom class='bottom_modules px-3'}
				</div>
			</div>
		</footer>
		{if $prefs.feature_layoutshadows eq 'y'}{eval var=$prefs.footer_shadow_end}</div>{/if}
{/if}

	{if $prefs.feature_layoutshadows eq 'y'}{eval var=$prefs.main_shadow_end}</div>{/if}

{include file='footer.tpl'}
</body>
</html>
{if $prefs.feature_debug_console eq 'y' and not empty($smarty.request.show_smarty_debug)}
	{debug}
{/if}
