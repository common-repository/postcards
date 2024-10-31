<?php
register_activation_hook('postcards/plugin.php', 'postcards_activate');

function postcards_activate() {
    $options = get_option('postcards');

    @include_once(dirname(__FILE__) . '/languages/en_US_options.php');
    if (WPLANG != '')
        @include_once(dirname(__FILE__) . '/languages/' . WPLANG . '_options.php');

    if (is_array($options))
        $options = array_merge($default_options, $options);
    else
        $options = $default_options;

    update_option('postcards', $options);
}

add_action('edit_form_advanced', 'postcards_post_form_meta');
add_action('edit_page_form', 'postcards_post_form_meta');
add_action('edit_post', 'postcards_post_meta');
add_action('publish_post', 'postcards_post_meta');
add_action('save_post', 'postcards_post_meta');
add_action('edit_page_form', 'postcards_post_meta');

function postcards_post_form_meta() {
    global $post;
    $enabled = get_post_meta($post->ID, 'postcards_enabled', true);
    $disabled = get_post_meta($post->ID, 'postcards_disabled', true);
    ?>
    <div id="postcards" class="postbox if-js-closed">
        <h3>Postcards</h3>
        <div class="inside">
            <p class="meta-options">
                <input type="hidden" value="1" name="postcards_edit" />
                <input name="postcards_disabled" value="1" type="checkbox" <?php echo $disabled ? "checked" : ""; ?>> Disable Postcards for this post/page<br />
                <input name="postcards_enabled" value="1" type="checkbox" <?php echo $enabled ? "checked" : ""; ?>> Enable Postcards for this post/page when not globally<br />
            </p>
        </div>
    </div>
    <?php
}

function postcards_post_meta($post_id) {
    if (!isset($_POST['postcards_edit']))
        return;

    if (isset($_POST['postcards_disabled'])) {
        // The true param avoids multiple inserts
        add_post_meta($post_id, 'postcards_disabled', '1', true);
    } else {
        delete_post_meta($post_id, 'postcards_disabled');
    }
    if (isset($_POST['postcards_enable'])) {
        // The true param avoids multiple inserts
        add_post_meta($post_id, 'postcards_enable', '1', true);
    } else {
        delete_post_meta($post_id, 'postcards_enable');
    }
}

add_action('admin_menu', 'postcards_admin_menu');

add_action('admin_head', 'postcards_admin_head');

function postcards_admin_head() {
    if (isset($_GET['page']) && strpos($_GET['page'], 'postcards/') === 0) {
        echo '<link type="text/css" rel="stylesheet" href="' . plugins_url('admin.css', __FILE__) . '">';
    }
}

function postcards_admin_menu() {
    add_options_page('Postcards', 'Postcards', 'manage_options', 'postcards/options.php');
}
