<h1>{tr}My Transactions{/tr}</h1>
<div class="navbar">
{if $tiki_p_tinvoice_edit eq 'y'}<a class="linkbut" href="tiki-tinvoice_transaction_edit.php?id_emitter={$me_tikiid}">{tr}new transaction{/tr}</a>&nbsp;{/if}
<a class="linkbut" href="tiki-tinvoice_prefs.php">{tr}preferences{/tr}</a>
<a class="linkbut" href="tiki-tinvoice_graph.php">{tr}Graphs{/tr}</a>
<a class="linkbut" href="tiki-tinvoice_banks.php">{tr}Banks{/tr}</a>
</div>

