<?php

namespace Anax\View;

/**
 * View to display all books.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$questions = isset($questions) ? $questions : null;

// Create urls for navigation
$urlToCreate = url("question/create");
$urlToDelete = url("question/delete");



?><h1>Frågor</h1>


<a href="<?= $urlToCreate ?>">Ny fråga</a>


<?php if (!$questions) : ?>
    <div class="questions">
        <p>Det finns inga frågor än!</p>
    </div>
<?php
    return;
endif;
?>

<div class="questions">
    <?php foreach ($questions as $question) : ?>
    <div class="question">
        <div class="title"><a href="<?= url("question/view/{$question->id}"); ?>"> <h2><?= $question->title ?></h2></a></div>
        <div class="by">
            <img src="<?= $question->getGravatar($question->email, 25) ?>" alt="<?= $question->username ?>>"> Av <a href="<?= url("user/view/{$question->username}"); ?>"><?= $question->username ?></a> <?= $question->created ?>
        </div>

        <div class="content">
            <?= $question->content ?>
        </div>

        <div class="tags">
            <?php foreach ($tags as $tag)
                if ($tag->question_id == $question->id) { ?>
                    <a href="<?= url("tag/view/{$tag->tag}"); ?>">
                        <div class="tag"><?= $tag->tag ?></div>
                    </a>
                    <?php
                } ?>
        </div>
    </div>
<?php endforeach; ?>
</div>
