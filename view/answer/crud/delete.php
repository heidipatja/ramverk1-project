<?php

/**
 * View to delete answer
 */

namespace Anax\View;

// Gather incoming variables and use default values if not set
$questionId = isset($questionId) ? $questionId : null;



?><h1>Radera svar</h1>

<?= $form ?>

<p>
    <a href="<?= url("question/view/{$questionId}") ?>">Tillbaka till frågan</a>
</p>
