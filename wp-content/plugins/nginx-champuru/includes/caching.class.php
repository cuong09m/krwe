<?php

class NginxChampuru_Caching {

private $q = "nginx_get_commenter";
private $last_modified;

function __construct()
{
    add_action("wp", array(&$this, "wp"));
    add_action(
        'wp_enqueue_scripts',
        array(&$this, 'wp_enqueue_scripts')
    );
    add_filter(
        "wp_get_current_commenter",
        array(&$this, "wp_get_current_commenter"),
        9999
    );
    add_filter("got_rewrite", "__return_true");
    add_filter("pre_comment_user_ip", array(&$this, "pre_comment_user_ip"));
    add_action(
        'wp_ajax_'.$this->q,
        array(&$this, 'wp_ajax_nginx_get_commenter')
    );
    add_action(
        'wp_ajax_nopriv_'.$this->q,
        array(&$this, 'wp_ajax_nginx_get_commenter')
    );
    add_filter("nocache_headers", array(&$this, "nocache_headers"));
    add_action("template_redirect", array(&$this, "template_redirect"), 9999);
    add_filter("nonce_life", array(&$this, "nonce_life"));

    if (!is_admin()) {
        $this->last_modified = gmdate('D, d M Y H:i:s', time()) . ' GMT';
        add_action('template_redirect', array(&$this, 'send_http_header_last_modified'));
        add_action('wp_head',  array(&$this, 'last_modified_meta_tag'));
    }
}

public function nonce_life($life)
{
    $expires = get_option("nginxchampuru-cache_expires", array($life));
    $max = max(array_values($expires));
    if ($max > $life) {
        return $max;
    } else {
        return $life;
    }
}

public function template_redirect()
{
    global $nginxchampuru;
    $exp = $nginxchampuru->get_expire();
    header('X-Accel-Expires: '.intval($exp));
}

public function nocache_headers($h)
{
    $h["X-Accel-Expires"] = 0;
    return $h;
}

public function pre_comment_user_ip()
{
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $X_FORWARDED_FOR = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
        $REMOTE_ADDR = trim($X_FORWARDED_FOR[0]);
    } else {
        $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
    }
    return $REMOTE_ADDR;
}

public function wp()
{
    global $post;
    if (is_singular() && post_password_required($post->ID)) {
        nocache_headers();
    }
}

public function wp_enqueue_scripts()
{
    if (is_singular() && comments_open()) {
        wp_enqueue_script('jquery');
        add_action(
            "wp_print_footer_scripts",
            array(&$this, "wp_print_footer_scripts_admin_ajax")
        );
    } else if ($this->is_future_post()) {
        wp_enqueue_script('jquery');
        add_action(
            "wp_print_footer_scripts",
            array(&$this, "wp_print_footer_scripts_wp_clon")
        );
    }
}

public function wp_print_footer_scripts_admin_ajax()
{
    $js =<<<EOL
<script type="text/javascript">
(function($){
    $.getJSON("%s", function(json){
        $('#author').val(json.comment_author);
        $('#email').val(json.comment_author_email);
        $('#url').val(json.comment_author_url);
    });
})(jQuery);
</script>
EOL;
    printf($js, admin_url("admin-ajax.php?action=".$this->q));
}

public function wp_print_footer_scripts_wp_clon(){
    $js =<<<EOL
<script type="text/javascript">
(function($){
    $.get("%s");
})(jQuery);
</script>
EOL;
    printf($js, site_url('wp-cron.php'));
}

private function add_last_modified() {
    global $nginxchampuru;
    return (function_exists('is_user_logged_in') && !is_user_logged_in() && $nginxchampuru->add_last_modified());
}

public function send_http_header_last_modified()
{
    if ($this->add_last_modified()) {
		header("Last-Modified: {$this->last_modified}");
    }
}

public function last_modified_meta_tag()
{
    if (!is_feed() && $this->add_last_modified()) {
        $last_modified_meta_tag = "<meta http-equiv=\"Last-Modified\" content=\"{$this->last_modified}\" />\n";
        echo $last_modified_meta_tag;
    }
}

public function wp_ajax_nginx_get_commenter()
{
    nocache_headers();
    header('Content-type: application/json');
    echo json_encode(wp_get_current_commenter());
    exit;
}

public function wp_get_current_commenter($commenter)
{
    if (defined("DOING_AJAX") && DOING_AJAX &&
            isset($_GET["action"]) && $_GET["action"] === $this->q) {
        return $commenter;
    } else {
        return array(
            'comment_author'       => '',
            'comment_author_email' => '',
            'comment_author_url'   => '',
        );
    }
}

private function is_future_post()
{
    $cron = get_option("cron");
    foreach ($cron as $key => $jobs) {
        if (is_array($jobs)) {
            $res = array_key_exists("publish_future_post", $jobs);
            if ($res) {
                return true;
            }
        }
    }
    return false;
}

}

// EOF
