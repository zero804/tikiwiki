{if $prefs.login_is_email ne 'y'}
				<tr>
					<td class="formcolor"><label for="email">{tr}Email:{/tr}</label>{if $trackerEditFormId}&nbsp;<strong class='mandatory_star'>*</strong>&nbsp;{/if}</td>
					<td class="formcolor"><input type="text" id="email" name="email" {if $prefs.feature_ajax eq 'y'}onkeyup="return check_mail()" onblur="return check_mail()"{/if}/>
						{if $prefs.feature_ajax eq 'y'}<span id="ajax_msg_mail" style="vertical-align: middle;"></span>{/if}
						{if $prefs.validateUsers eq 'y' and $prefs.validateEmail ne 'y'}
						<div class="highlight"><em class='mandatory_note'>{tr}A valid email is mandatory to register{/tr}</em></div>
						{/if}
					</td>
				</tr>
{/if}