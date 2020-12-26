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
    <p>Det finns inga frågor än!</p>
<?php
    return;
endif;
?>


<?php foreach ($questions as $question) : ?>
<div class="question">
    <div class="question-title"><a href="<?= url("question/view/{$question->id}"); ?>"> <h2><?= $question->title ?></h2></a></div>
    <div class="question-by">
        <img src="<?= $question->getGravatar($question->email, 25) ?>" alt="<?= $question->username ?>>"> Av <?= $question->username ?> <?= $question->created ?>
    </div>

    <div class="question-content">
        <?= $question->content ?>
    </div>
</div>
<?php endforeach; ?>
