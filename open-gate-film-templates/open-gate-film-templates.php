<?php
/**
 * Plugin Name: Open Gate Film Templates
 * Description: Feature templates and shortcodes for Open Gate Film.
 * Version: 0.1.0
 * Author: Open Gate Film
 */

if (!defined('ABSPATH')) {
    exit;
}

define('OGFT_VERSION', '0.1.0');
define('OGFT_PATH', plugin_dir_path(__FILE__));
define('OGFT_URL', plugin_dir_url(__FILE__));

function ogft_default_featured_work_items()
{
    return [
        [
            'link' => '/work/project-1',
            'kicker' => 'Brand / Web',
            'title' => 'Project One',
            'meta' => '2025 • Motion + UI',
            'video_src' => 'project-1.mp4',
            'poster_src' => 'project-1-poster.jpg',
            'overlay_src' => 'project-1-poster.jpg',
        ],
        [
            'link' => '/work/project-2',
            'kicker' => 'SaaS / Product',
            'title' => 'Project Two',
            'meta' => '2025 • Animation',
            'video_src' => 'project-1.mp4',
            'poster_src' => 'project-1-poster.jpg',
            'overlay_src' => 'project-1-poster.jpg',
        ],
        [
            'link' => '/work/project-3',
            'kicker' => 'Ecommerce',
            'title' => 'Project Three',
            'meta' => '2024 • Web + Video',
            'video_src' => 'project-1.mp4',
            'poster_src' => 'project-1-poster.jpg',
            'overlay_src' => 'project-1-poster.jpg',
        ],
    ];
}

function ogft_default_stats_items()
{
    return [
        [
            'kicker' => 'Performance',
            'value' => '98%',
            'label' => 'Client satisfaction',
            'bg_image' => 'https://picsum.photos/id/1011/900/600',
        ],
        [
            'kicker' => 'Growth',
            'value' => '+142%',
            'label' => 'Organic traffic',
            'bg_image' => 'https://picsum.photos/id/1015/900/600',
        ],
        [
            'kicker' => 'Delivery',
            'value' => '7 days',
            'label' => 'Avg. turnaround',
            'bg_image' => 'https://picsum.photos/id/1005/900/600',
        ],
        [
            'kicker' => 'Impact',
            'value' => '1.2M',
            'label' => 'Impressions served',
            'bg_image' => 'https://picsum.photos/id/1025/900/600',
        ],
    ];
}

function ogft_get_settings()
{
    $defaults = [
        'enable_featured_work' => '1',
        'enable_stats' => '1',
        'featured_work_items' => wp_json_encode(ogft_default_featured_work_items(), JSON_PRETTY_PRINT),
        'stats_items' => wp_json_encode(ogft_default_stats_items(), JSON_PRETTY_PRINT),
    ];

    $settings = get_option('ogft_settings', []);

    return wp_parse_args($settings, $defaults);
}

function ogft_parse_json_items($value, $fallback)
{
    if (!is_string($value) || $value === '') {
        return $fallback;
    }

    $decoded = json_decode($value, true);
    if (!is_array($decoded)) {
        return $fallback;
    }

    return $decoded;
}

function ogft_register_settings_page()
{
    add_menu_page(
        'Open Gate Film Templates',
        'Open Gate Film',
        'manage_options',
        'open-gate-film-templates',
        'ogft_render_settings_page',
        'dashicons-layout',
        58
    );
}
add_action('admin_menu', 'ogft_register_settings_page');

function ogft_register_settings()
{
    register_setting('ogft_settings_group', 'ogft_settings', [
        'sanitize_callback' => 'ogft_sanitize_settings',
    ]);
}
add_action('admin_init', 'ogft_register_settings');

function ogft_sanitize_settings($input)
{
    $sanitized = [];

    $sanitized['enable_featured_work'] = empty($input['enable_featured_work']) ? '0' : '1';
    $sanitized['enable_stats'] = empty($input['enable_stats']) ? '0' : '1';

    $sanitized['featured_work_items'] = isset($input['featured_work_items'])
        ? sanitize_textarea_field($input['featured_work_items'])
        : '';
    $sanitized['stats_items'] = isset($input['stats_items'])
        ? sanitize_textarea_field($input['stats_items'])
        : '';

    return $sanitized;
}

function ogft_render_settings_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $settings = ogft_get_settings();
    ?>
    <div class="wrap">
        <h1>Open Gate Film Templates</h1>
        <p>Enable features and update their content. Shortcodes are listed below each section.</p>

        <form method="post" action="options.php">
            <?php settings_fields('ogft_settings_group'); ?>

            <h2>Featured Work</h2>
            <p><strong>Shortcode:</strong> <code>[open_gate_featured_work]</code></p>
            <label>
                <input type="checkbox" name="ogft_settings[enable_featured_work]" value="1" <?php checked($settings['enable_featured_work'], '1'); ?> />
                Enable Featured Work
            </label>
            <p>JSON array of cards (link, kicker, title, meta, video_src, poster_src, overlay_src).</p>
            <textarea name="ogft_settings[featured_work_items]" rows="14" class="large-text code"><?php echo esc_textarea($settings['featured_work_items']); ?></textarea>

            <hr />

            <h2>Stats</h2>
            <p><strong>Shortcode:</strong> <code>[open_gate_stats]</code></p>
            <label>
                <input type="checkbox" name="ogft_settings[enable_stats]" value="1" <?php checked($settings['enable_stats'], '1'); ?> />
                Enable Stats
            </label>
            <p>JSON array of cards (kicker, value, label, bg_image).</p>
            <textarea name="ogft_settings[stats_items]" rows="14" class="large-text code"><?php echo esc_textarea($settings['stats_items']); ?></textarea>

            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}

function ogft_enqueue_featured_work_assets()
{
    wp_enqueue_style(
        'ogft-featured-work',
        OGFT_URL . 'features/featured-work/style.css',
        [],
        OGFT_VERSION
    );
    wp_enqueue_script(
        'ogft-featured-work',
        OGFT_URL . 'features/featured-work/script.js',
        [],
        OGFT_VERSION,
        true
    );
}

function ogft_enqueue_stats_assets()
{
    wp_enqueue_style(
        'ogft-stats',
        OGFT_URL . 'features/stats/style.css',
        [],
        OGFT_VERSION
    );

    wp_enqueue_script(
        'gsap',
        'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js',
        [],
        '3.12.5',
        true
    );

    wp_enqueue_script(
        'ogft-stats',
        OGFT_URL . 'features/stats/script.js',
        ['gsap'],
        OGFT_VERSION,
        true
    );
}

function ogft_shortcode_featured_work()
{
    $settings = ogft_get_settings();
    if (empty($settings['enable_featured_work'])) {
        return '';
    }

    $items = ogft_parse_json_items($settings['featured_work_items'], ogft_default_featured_work_items());
    if (!$items) {
        return '';
    }

    ogft_enqueue_featured_work_assets();

    ob_start();
    $template = OGFT_PATH . 'features/featured-work/template.php';
    include $template;
    return ob_get_clean();
}
add_shortcode('open_gate_featured_work', 'ogft_shortcode_featured_work');

function ogft_shortcode_stats()
{
    $settings = ogft_get_settings();
    if (empty($settings['enable_stats'])) {
        return '';
    }

    $items = ogft_parse_json_items($settings['stats_items'], ogft_default_stats_items());
    if (!$items) {
        return '';
    }

    ogft_enqueue_stats_assets();

    static $instance = 0;
    $instance++;
    $section_id = 'ogft-stats-' . $instance;

    ob_start();
    $template = OGFT_PATH . 'features/stats/template.php';
    include $template;
    return ob_get_clean();
}
add_shortcode('open_gate_stats', 'ogft_shortcode_stats');
