<?php

/*
  Plugin Name: Postcards
  Plugin URI: http://www.satollo.net/plugins/postcards
  Description: Send electornic postcards
  Version: 1.2.4
  Author: Stefano Lissa
  Author URI: http://www.satollo.net
  Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
 */

/* 	
  Copyright 2008-2013 Stefano Lissa (stefano@satollo.net)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

global $postcards_labels;

function postcards_init_labels() {
    global $postcards_labels;

    @include(dirname(__FILE__) . '/languages/en_US.php');
    if (WPLANG != '')
        @include(dirname(__FILE__) . '/languages/' . WPLANG . '.php');
}

function postcards_label($name) {
    global $postcards_labels;

    if (!$postcards_labels)
        postcards_init_labels();

    return $postcards_labels[$name];
}

function postcards_echo($name) {
    echo postcards_label($name);
}

function postcards_echo_js($name) {
    echo addslashes(postcards_label($name));
}

if (!is_admin()) {
    add_action('init', 'postcards_init', 0);
    add_action('wp_footer', 'postcards_wp_footer');
    add_filter('attachment_link', 'postcards_attachment_link', 10, 2);
}

function postcards_attachment_link($link, $id) {
    $mimetypes = array('image/jpeg', 'image/png', 'image/gif');
    $post = get_post($id);
    if (in_array($post->post_mime_type, $mimetypes))
        return wp_get_attachment_url($id);
    else
        return $link;
}

function postcards_init() {
    global $hyper_cache_stop;
    // Request to show the form to create the ecard
    if (isset($_GET['ecimg'])) {
        $options = get_option('postcards');
        $hyper_cache_stop = true;
        require 'popup.php';
        die();
    }

    if (isset($_POST['ecemail'])) {
        postcards_mail(stripslashes($_POST['ecsender']), $_POST['ecemail'], stripslashes($_POST['ecname']), htmlspecialchars(stripslashes($_POST['ecmsg'])), 'http://' . $_POST['ecimg'], 'http://' . $_POST['ecurl']);
        postcards_echo('Thank you message');

        $options = get_option('postcards');
        if (!isset($options['count']))
            $options['count'] = 1;
        else
            $options['count']++;

        die();
    }
}

function postcards_mail($sender, $to, $name, $msg, $img, $url) {
    $options = get_option('postcards');

    $sender_email = $options['sender_email'];
    $sender_name = $options['sender_name'];

    $sender = htmlspecialchars($sender);
    $name = htmlspecialchars($name);


    $subject = $options['subject'];
    $subject = str_replace('{receiver}', $name, $subject);
    $subject = str_replace('{sender}', $sender, $subject);

    $message = $options['message'];
    $message = str_replace('{receiver}', $name, $message);
    $message = str_replace('{sender}', $sender, $message);
    $message = str_replace('{url}', $url, $message);
    $message = str_replace('{image}', $img, $message);
    $message = str_replace('{message}', $msg, $message);
    $message = str_replace('{blog_title}', get_option('blogname'), $message);
    $message = str_replace('{blog_url}', get_option('home'), $message);

    $message = postcards_replace_extra($message);

    $headers = "MIME-Version: 1.0\n";
    $headers .= "Content-type: text/html; charset=UTF-8\n";
    $headers .= 'From: "' . $sender_name . '" <' . $sender_email . ">\n";

    wp_mail($to, $subject, $message, $headers);


    if ($options['copy_email']) {
        wp_mail($options['copy_email'], '[' . get_option('blogname') . '] New e-card sent to ' . $to, $message, $headers);
    }
}

function postcards_wp_footer() {
    global $post;

    if (!is_single() && !is_page())
        return;

    if (postcards_skip($post))
        return;

    echo '
<script type="text/javascript">
function postcards_send(img)
{
var width  = 570;
var height = 370;
var left   = (screen.width  - width)/2;
var top    = (screen.height - height)/2;
var params = "width=" + width + ", height=" + height + ", top=" + top + ", left=" + left;
params += ", directories=no,location=no,menubar=no,resizable=no,scrollbars=no,status=no,toolbar=no";
var awin = window.open("' . get_option('home') . '/?ecimg=" + img.substring(7) + "&ecurl=" + location.href.substring(7), "postcards", params);
if (window.focus) awin.focus();
}

var dtlist = document.getElementsByTagName("dt");
for(i=0; i<dtlist.length; i++)
{
if (dtlist[i].className.indexOf("postacards-no") >= 0) continue;
alist = dtlist[i].getElementsByTagName("a");
dtlist[i].innerHTML += "<br /><a href=\'javascript:void(postcards_send(\\"" + alist[0].href + "\\"))\'>' . postcards_label('Send as e-card') . '</a>";
}
</script>
';
}

function postcards_replace_extra($message) {
    $options = get_option('postcards');

    $max = $options['last_posts_max'];
    if (!is_numeric($max))
        $max = 4;
    $message = str_replace('[last_posts]', '<p>' . wp_get_archives('type=postbypost&limit=' . $max . '&format=custom&before=&after=<br />&echo=0') . '</p>', $message);

    // Random ads
    $ads = array();
    for ($i = 1; $i <= 3; $i++) {
        if (trim($options['ads_' . $i]) != '')
            $ads[] = $options['ads_' . $i];
    }
    if (count($ads) > 0) {
        $ad = $ads[rand(0, count($ads) - 1)];
    }

    $message = str_replace('[ads]', $ad, $message);

    return $message;
}

function postcards_skip() {
    global $postcards_post_id;

    $options = get_option('postcards');

    $disabled = get_post_meta($postcards_post_id, 'postcards_disabled', true);

    if ($disabled)
        return true;
    $enabled = get_post_meta($postcards_post_id, 'postcards_enabled', true);
    if ($options['gallery'] && !$enabled)
        return true;
}

$postcards_post_id = 0;
add_action('the_content', 'postcards_the_content');

function postcards_the_content($content) {
    global $post, $postcards_post_id;


    if ($postcards_post_id == 0)
        $postcards_post_id = $post->ID;
    return $content;
}

if (is_admin()) {
    include dirname(__FILE__) . '/admin.php';
}
?>
