<?php

namespace WPPerformance\LazyIframe;

/**
 * Plugin Name:       Lazy iframe
 * Description:       Pass iframe to lazy load
 * Update URI:        wp-performance-lazy-iframe
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Version:           0.0.1
 * Author:            Faramaz Patrick <infos@goodmotion.fr>
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-performance-lazy-iframe
 *
 * @package           wp-performance
 */
require_once(dirname(__FILE__) . '/inc/parser.php');

/**
 * filter the content
 */
add_filter('the_content', function ($content) {
    return inc\parser\parse($content);
});

/**
 * add script to front
 */
function frontend_scripts()
{
    $content = get_the_content();
    if (strpos($content,  'iframe') !== false || strpos($content,  'wp-block-embed__wrapper')) {
        wp_enqueue_script(
            'wpp-lazy-iframe-blazy',
            plugins_url('public/blazy.min.js', __FILE__),
            [],
            filemtime(plugin_dir_path(__FILE__) . 'public/blazy.min.js'),
            true
        );
        wp_enqueue_script(
            'wpp-lazy-iframe-front',
            plugins_url('public/index.js', __FILE__),
            ['wpp-lazy-iframe-blazy'],
            filemtime(plugin_dir_path(__FILE__) . 'public/index.js'),
            true
        );
    }
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\frontend_scripts');
