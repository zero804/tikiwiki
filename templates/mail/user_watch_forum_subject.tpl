{* $Id$ *}{if $new_topic}{tr}New {$prefs.mail_template_custom_text}forum topic in <{$mail_forum|truncate:20:"..."}> by {$mail_author|username}:{/tr}{else}	{tr}New {$prefs.mail_template_custom_text}forum post in <{$mail_forum|truncate:20:"..."}> by {$mail_author|username}:{/tr}{/if} {$mail_title|truncate:50:"..."}
