<?php

$default_options['subject'] = '{receiver}, a card for you from {sender}';

$default_options['message'] =
'<br />Hi {receiver}, {sender} sent this card while reading <a href="{url}">this page</a>.
Would you give him/her an answer?<br /><br />

<table width="550" cellpadding="15" style="border: 3px solid #999">
<tr>
<td align="center" valign="top"><img src="{image}" width="250"/></td>
<td align="left" valign="top" style="border-left: 1px solid #ddd">
From: <strong>{sender}</strong><br />
To: <strong>{receiver}</strong><br />
<br />
{message}
<br /><br />
<a href="{url}"><strong><big>Reply!</big></strong></a>
</td>
</tr>
</table>
';

?>
