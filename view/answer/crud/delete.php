<?php

namespace Anax\View;

/**
 * View to delete answer
 */

// Gather incoming variables and use default values if not set
$questionId = isset($questionId) ? $questionId : null;



?><h1>Radera svar</h1>

<?= $form ?>

<p>
    <a href="<?= url("question/view/{$questionId}") ?>">Tillbaka till frågan</a>
</p>
