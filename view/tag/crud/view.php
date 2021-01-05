<?php

namespace Anax\View;

/**
 * View to display all books.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$tag = isset($tag) ? $tag : null;
$questions = isset($questions) ? $questions : null;
$tags = isset($tags) ? $tags : null;

// Create urls for navigation
$urlToViewTags = url("tag");

// var_dump($questions);
// var_dump($tags);

?>

<?php if (!$tag) : ?>
    <p>Det finns inga taggar än!</p>
<?php
    return;
endif;
?>

<h1>Frågor om <?= $tag ?></h1>

<?php if (!$questions) : ?>
    <p>Det finns inga frågor som matchar taggen <?= $tag ?>.</p>
    <a href="<?= $urlToViewTags ?>">Se alla taggar</a>
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

    <div class="question-tags">
        <?php foreach ($question->tags as $tag) : ?>
            <div class="tag"><?= $tag->tag ?></div>
        <?php endforeach; ?>

    </div>
</div>
<?php endforeach; ?>

<a href="<?= $urlToViewTags ?>">Se alla taggar</a>
