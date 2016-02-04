{* $Id: layout_view.tpl 48366 2013-11-08 16:12:24Z lphuberdeau $ *}<!DOCTYPE html>
<html lang="{if !empty($pageLang)}{$pageLang}{else}{$prefs.language}{/if}"{if !empty($page_id)} id="page_{$page_id}"{/if}>
	<head>
{include file='header.tpl'}
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body{html_body_attributes class="navbar-padding"}>
{$cookie_consent_html}

{include file="layout_fullscreen_check.tpl"}

{if $prefs.feature_ajax eq 'y'}
	{include file='tiki-ajax_header.tpl'}
{/if}

		<div class="container">

			<div class="row">

				<div class="{if	zone_is_empty('left') and zone_is_empty('right')}col-md-12 {elseif 
						zone_is_empty('left')}col-md-9 {elseif 
						zone_is_empty('right')}col-md-9 col-md-push-3 {else}col-md-8 col-md-push-2 {/if}col1" id="col1">
	{if $prefs.module_zones_pagetop eq 'fixed' or ($prefs.module_zones_pagetop ne 'n' && ! zone_is_empty('pagetop'))}
		{modulelist zone=pagetop}
	{/if}
	{error_report}
					<div class="pull-right">
						{block name=quicknav}{/block}
					</div>
	{block name=title}{/block}
	{block name=navigation}{/block}
	{block name=content}{/block}
	{if $prefs.module_zones_pagebottom eq 'fixed' or ($prefs.module_zones_pagebottom ne 'n' && ! zone_is_empty('pagebottom'))}
		{modulelist zone=pagebottom}
	{/if}
				</div> {* col1 *}
	{if zone_is_empty('left') and zone_is_empty('right')}
		{* show nothing *}
	{elseif zone_is_empty('left')}
				<div class="col-md-3" id="col3">
		{modulelist zone=right}
				</div>
	{elseif zone_is_empty('right')}
				<div class="col-md-3 col-md-pull-9" id="col2">
		{modulelist zone=left}
				</div>
	{else}
				<div class="col-md-2 col-md-pull-8" id="col2">
		{modulelist zone=left}
				</div>
				<div class="col-md-2" id="col3">
		{modulelist zone=right}
				</div>
	{/if} 
			</div> {* row *}
		</div> {* container *}

		<footer class="footer main-footer" id="footer">
			<div class="container">
				<div class="footer_liner">
{modulelist zone=bottom class=row} <!-- div.modules -->
				</div>
			</div>
		</footer>

		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse"
					data-target="#navbar-collapse-social">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="./">{if $prefs.sitelogo_icon}<img src="{$prefs.sitelogo_icon}">{/if} {$prefs.sitetitle|escape}</a>
				</div> {* navbar-header *}

				<div class="collapse navbar-collapse" id="navbar-collapse-social">
						{modulelist zone="topbar" class="navbar-left" id="topbar_modules_social"}

						<ul class="nav navbar-nav navbar-right">
{if $user}
							<li>
								<a href="{if $prefs.feature_sefurl eq 'y'}logout{else}tiki-logout.php{/if}">{tr}Log out{/tr}</a>
							</li>
{else}
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">{tr}Log in{/tr} <span
								class="caret"></span></a>
								<ul class="dropdown-menu">
									<li>
										<div>
	{module
		module=login_box
		mode="module"
		show_register=""
		show_forgot=""
		error=""
		flip=""
		decorations="n"
		nobox="y"
		notitle="y"
	}
										</div>
									</li>
								</ul>
							</li>
	{if $prefs.allowRegister eq 'y'}
							<li>
								<a href="{if $prefs.feature_sefurl eq 'y'}register{else}tiki-register.php{/if}">{tr}Register{/tr}</a>
							</li>
	{/if}
{/if}
						</ul>
				</div> {* navbar-collapse-social *}
			</div> {* container *}

		</nav>

{include file='footer.tpl'}
	</body>
	<script type="text/javascript">
		$(document).ready(function () {
			$('.tooltips').tooltip({
				'container': 'body'
			});
		});
	</script>
</html>
{if !empty($smarty.request.show_smarty_debug)}
	{debug}
{/if}
