<?php

/**
 * View to create a new question
 */

namespace Anax\View;

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

// Create urls for navigation
$urlToViewItems = url("question");



?><h1>Ny fråga</h1>

<?= $form ?>

<p>
    <a href="<?= $urlToViewItems ?>">Visa alla</a>
</p>
