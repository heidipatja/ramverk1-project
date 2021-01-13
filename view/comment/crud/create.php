<?php

/**
 * View to create a new comment.
 */

namespace Anax\View;

// Gather incoming variables and use default values if not set
$questionId = isset($questionId) ? $questionId : null;

?><h1>Ny kommentar</h1>

<?= $form ?>

<p>
    <a href="<?= url("question/view/{$questionId}"); ?>">Tillbaka till frågan</a>
</p>
