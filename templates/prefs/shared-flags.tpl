{if $p.helpurl}
	<a href="{$p.helpurl|escape}" target="tikihelp" class="tikihelp" title="{$p.name|escape}: {$p.description|escape}">
		{icon _id=help alt=''}
	</a>
{elseif $p.description}
	<span class="tikihelp" title="{$p.name|escape}: {$p.description|escape}">
		{icon _id=information alt=''}
	</span>
{/if}
{if $p.warning}
	<a href="" target="tikihelp" class="tikihelp" title="{tr}Warning{/tr}: {$p.warning|escape}">
		{icon _id=error alt=''}
	</a>
{/if}

{if $p.value neq $p.default_val}
	<input class="pref-reset system" type="checkbox" name="lm_reset[]" value="{$p.preference|escape}" style="display:none" />
	<input type="hidden" id="{$p.preference|escape}_default" value="{$p.default_val|escape}" />
{/if}

{jq}
$jq('.pref-reset')
	.change( function() {
		var $el = $jq(this).closest('.adminoptionbox').find('input:not(:hidden),select,textarea')
			.not('.system').attr( 'disabled', $jq(this).attr('checked') ? "disabled" : "" )
			.css("opacity", $jq(this).attr('checked') ? .6 : 1 );
		var defval = $jq("#" + $jq(this).val() + "_default").val();
		if ($el.attr("type") == "checkbox") {
			$el.attr('checked', $jq(this).attr('checked') ? (defval == "y" ? "checked" : "") : ($el.attr('checked') ? "" : "checked" ));
		} else {
			var temp = $jq("[name=" + $jq(this).val() + "]").val();
			$el.val( defval );
			$jq("#" + $jq(this).val() + "_default").val( temp );
		}
	} )
	.wrap('<span/>')
	.closest('span')
		.append('{{icon _id=arrow_undo alt="{tr}Reset to default{/tr}" href=#}}')
		.find('a')
			.click( function() {
				var box = $jq(this).closest('span').find(':checkbox');

				box.attr('checked', box.filter(':checked').length == 0).change();
				return false;
			} );
{/jq}

{$p.pages}
