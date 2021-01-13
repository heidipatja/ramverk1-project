<?php

/**
 * View to create a new book.
 */

namespace Anax\View;

// Gather incoming variables and use default values if not set
$item = isset($item) ? $item : null;

// Create urls for navigation
$urlToView = url("question/view/{$id}");



?><h1>Uppdatera fråga</h1>

<?= $form ?>

<p>
    <a href="<?= $urlToView ?>">Tillbaka</a>
</p>
