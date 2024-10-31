<html>
    <head>
        <title>Postcard</title>
        <style type="text/css">
            body, td, input, textarea {
                font-family: verdana;
                font-size: 11px;
            }
            input, textarea {
                border: 1px solid #999;
            }
        </style>
        <script type="text/javascript">
            function check(f)
            {
                if (f.ecsender.value == "") {
                    alert("<?php postcards_echo_js('Sender name error'); ?>");
                    f.ecsender.focus();
                    return false;
                }
                if (f.ecname.value == "") {
                    alert("<?php postcards_echo_js('Receiver name error'); ?>");
                    f.ecname.focus();
                    return false;
                }

                var re = /^.+@.+\..+$/;
                if (!re.test(f.ecemail.value)) {
                    alert("<?php postcards_echo_js('Receiver email error'); ?>");
                    f.ecemail.focus();
                    return false;
                }

                if (f.ecmsg.value == "") {
                    alert("<?php postcards_echo_js('Message error'); ?>");
                    f.ecmsg.focus();
                    return false;
                }
                return true;
            }
        </script>
    </head>
    <body bgcolor="#333333">
        <form method="post" action="<?php echo get_option('home'); ?>/" onsubmit="return check(this)">
            <input type="hidden" name="ecimg" value="<?php echo htmlspecialchars($_GET['ecimg']); ?>"/>
            <input type="hidden" name="ecurl" value="<?php echo htmlspecialchars($_GET['ecurl']); ?>"/>
            <table width="500" cellpadding="10" style="border: 3px solid #999" align="center" bgcolor="#ffffff">
                <tr>
                    <td width="220" align="center" valign="top"><div style="overflow: hidden; height: 250px"><img src="http://<?php echo htmlspecialchars($_GET['ecimg']); ?>" width="200"/></div></td>
                    <td align="left" valign="top" style="border-left: 1px solid #bbb">
                        <small><?php postcards_echo('Your name'); ?></small><br />
                        <input type="text" name="ecsender" /><br />
                        <br />
                        <strong><?php postcards_echo('To'); ?></strong><br />
                        <small><?php postcards_echo('Receiver name'); ?></small><br /><input type="text" name="ecname"/><br />
                        <small><?php postcards_echo('Receiver email'); ?></small><br /><input type="text" name="ecemail"/><br />
                        <small><?php postcards_echo('Message'); ?></small><br />
                        <textarea style="width: 100%" rows="5" name="ecmsg"></textarea><br />
                        <input type="submit" value="<?php postcards_echo('Send'); ?>"/>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
