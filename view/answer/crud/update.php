<?php

namespace Anax\View;

/**
 * View to update an answer
 */

// Gather incoming variables and use default values if not set
$questionId = isset($questionId) ? $questionId : null;

// Create urls for navigation
$urlToView = url("answer");



?><h1>Uppdatera fråga</h1>

<?= $form ?>

<p>
    <a href="<?= url("question/view/{$questionId}") ?>">Tillbaka till frågan</a>
</p>
