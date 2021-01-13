<?php

/**
 * Sidebar on start page with most popular tags
 */

namespace Anax\View;

// Gather incoming variables and use default values if not set
$tags = isset($tags) ? $tags : null;

?>

<?php if (!$tags) : ?>
    <div class="user-sidebar">
        <h4>Populära taggar</h4>
        <p>Det finns inga taggar än!</p>
    </div>
    <?php
    return;
endif;
?>

<div class="user-sidebar">
    <h4>Populära taggar</h4>
    <div class="tags">
        <?php foreach ($tags as $tag) : ?>
        <a href="<?= url("tag/view/{$tag->tag}"); ?>">
            <div class="tag"><?= $tag->tag ?> (<?= $tag->tagcount ?>)</div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
