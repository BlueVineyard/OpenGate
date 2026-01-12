<?php
if (!defined('ABSPATH')) {
    exit;
}

$items = isset($ogft_services_items) ? $ogft_services_items : [];
$heading = isset($ogft_services_heading) ? $ogft_services_heading : 'Our Services';
$section_id = isset($ogft_services_id) ? $ogft_services_id : 'ogft-services';

if (empty($items) || !is_array($items)) {
    return;
}
?>
<section class="ogft-services" id="<?php echo esc_attr($section_id); ?>">
    <div class="services__inner">
        <div class="services__header">
            <h2 class="services__title"><?php echo esc_html($heading); ?></h2>
        </div>
        <div class="services__list">
            <?php foreach ($items as $item):
                $title = isset($item['title']) ? esc_html($item['title']) : '';
                $excerpt = isset($item['excerpt']) ? esc_html($item['excerpt']) : '';
                $url = isset($item['url']) ? esc_url($item['url']) : '';
                ?>
                <article class="service-card">
                    <div class="service-card__content">
                        <?php if ($title): ?>
                            <h3 class="service-card__title"><?php echo $title; ?></h3>
                        <?php endif; ?>
                        <?php if ($excerpt): ?>
                            <p class="service-card__excerpt"><?php echo $excerpt; ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if ($url): ?>
                        <a class="service-card__cta" href="<?php echo $url; ?>">View Detail</a>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>