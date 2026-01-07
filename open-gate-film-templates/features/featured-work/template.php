<?php
if (!defined('ABSPATH')) {
    exit;
}

if (empty($items) || !is_array($items)) {
    return;
}
?>
<section class="featured-work ogft-featured-work" aria-label="Featured work">
    <div class="fw-grid">
        <?php foreach ($items as $item) :
            $link = isset($item['link']) ? esc_url($item['link']) : '#';
            $kicker = isset($item['kicker']) ? esc_html($item['kicker']) : '';
            $title = isset($item['title']) ? esc_html($item['title']) : '';
            $meta = isset($item['meta']) ? esc_html($item['meta']) : '';
            $video_src = isset($item['video_src']) ? esc_url($item['video_src']) : '';
            $poster_src = isset($item['poster_src']) ? esc_url($item['poster_src']) : '';
            $overlay_src = isset($item['overlay_src']) ? esc_url($item['overlay_src']) : '';
            ?>
            <a class="fw-card" href="<?php echo $link; ?>">
                <div class="fw-media">
                    <?php if ($video_src) : ?>
                        <video class="fw-video" muted playsinline preload="metadata"<?php echo $poster_src ? ' poster="' . $poster_src . '"' : ''; ?>>
                            <source src="<?php echo $video_src; ?>" type="video/mp4" />
                        </video>
                    <?php endif; ?>

                    <?php if ($overlay_src) : ?>
                        <img class="fw-overlay-img" src="<?php echo $overlay_src; ?>" alt="" aria-hidden="true" />
                    <?php endif; ?>

                    <div class="fw-overlay">
                        <?php if ($kicker) : ?>
                            <p class="fw-kicker"><?php echo $kicker; ?></p>
                        <?php endif; ?>
                        <?php if ($title) : ?>
                            <h3 class="fw-title"><?php echo $title; ?></h3>
                        <?php endif; ?>
                        <?php if ($meta) : ?>
                            <p class="fw-meta"><?php echo $meta; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>
