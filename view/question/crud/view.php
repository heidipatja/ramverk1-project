<?php

namespace Anax\View;

/**
 * View to display all books.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$question = isset($question) ? $question : null;
$urlToView = url("question");

// var_dump($question);

?>

<?php if (!$question) : ?>
<?php
    return;
endif;
?>

<div class="question-details">
    <h1><?= $question->title ?></h1>
    <div class="question-created">
        Postad <?= $question->created ?> av <?= $question->username ?>
    </div>
    <div class="question-content">
        <?= $question->content ?>
    </div>
    <div class="question-tags">
        <?php foreach ($tags as $tag)
            if ($tag->question_id == $question->id) { ?>
                <div class="tag"><?= $tag->tag ?></div>
                <?php
            } ?>
    </div>
    <?php if ($isAuthor) : ?>
    <div class="question-edit">
        <a href="<?= url("question/update/{$question->id}"); ?>">Redigera</a>
    </div>
    <?php endif; ?>
</div>

<p>
    <a href="<?= $urlToView ?>">Alla frågor</a>
</p>
