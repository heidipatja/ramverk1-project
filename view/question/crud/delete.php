<?php

/**
 * View to delete a question
 */

namespace Anax\View;

// Create urls for navigation
$urlToView = url("question/view/{$id}");



?><h1>Radera fråga</h1>

<?= $form ?>

<p>
    <a href="<?= $urlToView ?>">Tillbaka</a>
</p>
