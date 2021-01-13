<?php

/**
 * View to display all questions matching tag
 */

namespace Anax\View;

// Gather incoming variables and use default values if not set
$tag = isset($tag) ? $tag : null;
$questions = isset($questions) ? $questions : null;
$tags = isset($tags) ? $tags : null;

// Create urls for navigation
$urlToViewTags = url("tag");

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

<div class="tag-questions">
    <?php foreach ($questions as $question) : ?>
    <div class="question">
        <div class="question-title"><a href="<?= url("question/view/{$question->id}"); ?>"> <h2><?= $question->title ?></h2></a></div>
        <div class="by">
            <img src="<?= $question->getGravatar($question->email, 25) ?>" alt="<?= $question->username ?>>"> Av <?= $question->username ?> <?= $question->created ?>
        </div>

        <div class="content">
            <?= $question->content ?>
        </div>

        <div class="question-tags tags">
            <?php foreach ($question->tags as $tag) : ?>
                <div class="tag">
                    <a class="tag" href="<?= url("tag/view/{$tag->tag}"); ?>"><?= $tag->tag ?></a>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
    <?php endforeach; ?>
</div>

<br>
<a href="<?= $urlToViewTags ?>">Se alla taggar</a>
