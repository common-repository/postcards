<?php
$default_options['Card subject'] = '{receiver}, una cartolina da {sender}';

$default_options['Card message'] =
'Ciao {receiver}, {sender} ti ha mandato questa splendida cartolina mentre leggeva <a href="{url}">questa pagina</a>.
Che ne dici di rispondere?<br /><br />

<table width="650" cellpadding="15" style="border: 3px solid #999">
<tr>
<td align="center" valign="top"><img src="{image}" width="250"/></td>
<td align="left" valign="top" style="border-left: 1px solid #ddd">
Da: <strong>{sender}</strong><br />
A: <strong>{receiver}</strong><br />
<br />
{message}
<br /><br />
<a href="{url}"><strong><big>Rispondi!</big></strong></a>

</td>
</tr>
</table>
';

?>
