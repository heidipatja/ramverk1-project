<?php

namespace Anax\View;

/**
 * View to display all books.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$tags = isset($tags) ? $tags : null;

?><h1>Taggar</h1>

<?php if (!$tags) : ?>
    <p>Det finns inga taggar än!</p>
<?php
    return;
endif;
?>

<div class="tags">
    <?php foreach ($tags as $tag) : ?>
    <div class="tag">
        <a class="tag" href="<?= url("tag/view/{$tag->tag}"); ?>"><?= $tag->tag ?></a>
    </div>
    <?php endforeach; ?>
</div>
