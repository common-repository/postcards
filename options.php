<?php

$options = get_option('postcards');

if (isset($_POST['save'])) {
    if (!check_admin_referer('save')) die('No hacking please');
    $options = stripslashes_deep($_POST['options']);
    update_option('postcards', $options);
}

?>
<div class="wrap">
        <div id="satollo-header">
        <a href="http://www.satollo.net/plugins/postcards" target="_blank">Get Help</a>
        <a href="http://www.satollo.net/forums" target="_blank">Forum</a>

        <form style="display: inline; margin: 0;" action="http://www.satollo.net/wp-content/plugins/newsletter/do/subscribe.php" method="post" target="_blank">
            Subscribe to satollo.net <input type="email" name="ne" required placeholder="Your email">
            <input type="hidden" name="nr" value="postcards">
            <input type="submit" value="Go">
        </form>

        <a href="https://www.facebook.com/satollo.net" target="_blank"><img style="vertical-align: bottom" src="http://www.satollo.net/images/facebook.png"></a>

        <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5PHGDGNHAYLJ8" target="_blank"><img style="vertical-align: bottom" src="http://www.satollo.net/images/donate.png"></a>
        <a href="http://www.satollo.net/donations" target="_blank">Even <b>1$</b> helps: read more</a>
    </div>

    <form method="post" action="">
        <?php wp_nonce_field('save'); ?>
        <h2>Postcards</h2>
        <p>
            See also the <a href="http://www.satollo.net/plugins/postcards" target="_blank">Postcards offcial page</a>.
        </p>

        <h3>Stats</h3>
        <table class="form-table">
            <tr valign="top">
                <th><label>Sent till now</label></th>
                <td>
                    <input name="options[count]" type="text" size="10" value="<?php echo htmlspecialchars($options['count'])?>"/>
                </td>
            </tr>
        </table>
        
        <h3>Sender details</h3>
        <p>
            Address and name of the emails sender (eg. the blog name and the blog email address). Postcards doesn't send
            Postcards with name and email address of the real sender, because it can be used to spam.
        </p>
        <table class="form-table">
            <tr valign="top">
                <th><label>Email address</label></th>
                <td>
                    <input name="options[sender_email]" type="text" size="50" value="<?php echo htmlspecialchars($options['sender_email'])?>"/>
                </td>
            </tr>
            <tr valign="top">
                <th><label>Name</label></th>
                <td>
                    <input name="options[sender_name]" type="text" size="50" value="<?php echo htmlspecialchars($options['sender_name'])?>"/>
                </td>
            </tr>
            <tr valign="top">
                <th><label>Send a copy to...</label></th>
                <td>
                    <input name="options[copy_email]" type="text" size="50" value="<?php echo htmlspecialchars($options['copy_email'])?>"/>
                    <br />
                    Leave empty to disable.
                </td>
            </tr>
        </table>

        <table class="form-table">
            <tr>
                <th>Card subject</th>
                <td>
                    <input name="options[subject]" id="subject" type="text" size="70" value="<?php echo htmlspecialchars($options['subject'])?>"/>
                    <br />
                    Tags: {sender} - the sender name, {receiver} - the receiver name.
                </td>
            </tr>

            <tr>
                <th>Card message body</th>
                <td>
                    <textarea name="options[message]" id="message" wrap="off" rows="10" cols="50" style="width: 500px"><?php echo htmlspecialchars($options['message'])?></textarea>
                    <br />
                    Tags: {image} - the selected image URL, {sender} - the sender name,
                    {receiver} - the receiver name, {url} - the post URL, {message} - the card text,
                    {blog_title} - the blog title, {blog_url}- the blog home URL.
                    <br /><br />
                    Other tags available:
                    <ul>
                      <li>[last_posts] - a list of last blog posts</li>
                      <li>[ads] - a randomly ad text or affiliate link. See below for detailed configuration of those tags</li>
                      <li>[related_posts] a list of related post</li>
                    </ul>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit" name="save" value="Save"/>
        </p>

        <h3>Extra configurations</h3>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label>Activate only to specific galleries</label></th>
                <td>
                    <input type="checkbox" name="options[gallery]" value="1" <?php echo $options['gallery']!= null?'checked':''; ?> />
                    <br />
                    When set, only post specifically enabled are enriched with postcards. To enable a specific
                    post for postcards, check the options on the bottom of the post editing page.
                </td>
            </tr>
            <tr>
                <th>Max last posts number</th>
                <td>
                    <input name="options[last_posts_max]" type="text" size="5" value="<?php echo htmlspecialchars($options['last_posts_max'])?>"/>
                </td>
            </tr>
            <tr>
                <th>Random ads</th>
                <td>
                    Ads code number 1<br />
                    <textarea name="options[ads_1]" rows="3" cols="50"><?php echo htmlspecialchars($options['ads_1'])?></textarea>
                    <br />
                    Ads code number 2<br />
                    <textarea name="options[ads_2]" rows="3" cols="50"><?php echo htmlspecialchars($options['ads_2'])?></textarea>
                    <br />
                    Ads code number 3<br />
                    <textarea name="options[ads_3]" rows="3" cols="50"><?php echo htmlspecialchars($options['ads_3'])?></textarea>
                    <br />
                    Put affiliate codes in the textareas and they will be displayed randomly in place of the [ads] tag. Empty
                    textarea will be ignored. Remeber to NOT use JavaScript code, they won't show in mail client.
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="save" value="Save"/>
        </p>


    </form>
</div>
