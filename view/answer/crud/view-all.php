<?php

namespace Anax\View;

/**
 * View to display all answers to question
 */

// Gather incoming variables and use default values if not set
$answers = isset($answers) ? $answers : null;

?>

<?php if (!$answers) : ?>
<?php
    return;
endif;
?>


<div class="answers">
    <?php foreach ($answers as $answer) : ?>
        <div class="answer">
            <div class="answer-created">
                Postad <?= $answer->created ?> av <?= $answer->username ?>
            </div>
            <div class="answer-content">
            <?= $answer->content ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
