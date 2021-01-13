<?php

/**
 * View to display all books.
 */

namespace Anax\View;

// Gather incoming variables and use default values if not set
$questions = isset($questions) ? $questions : null;

?><div class="wrap-sidebar"><h4>Senaste frågor</h4></div>

<?php if (!$questions) : ?>
    <div class="new-question">
        <a class="button" href="<?= $urlToCreate ?>">Ny fråga</a>
    </div>
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
        <div class="col1">
            <div class="votes q">
                <?= $question->upvote ?>
                <p class="vote-sum"><?= $question->votesum ?></p>
                <?= $question->downvote ?>
            </div>
            <div class="length">
                <p class="answer-length number"><?= $question->answerCount ?></p>
                <p class="answer-length">svar</p>
            </div>
        </div>
        <div class="col2">
            <div class="title"><a href="<?= url("question/view/{$question->id}"); ?>"> <h2><?= $question->title ?></h2></a></div>
            <div class="by">
                <img src="<?= $question->getGravatar($question->email, 25) ?>" alt="<?= $question->username ?>>"> Av <a href="<?= url("user/view/{$question->username}"); ?>"><?= $question->username ?></a> <?= $question->created ?>
            </div>

            <div class="content">
                <?= $question->content ?>
            </div>

            <div class="tags">
                <?php foreach ($question->tags as $tag) : ?>
                    <a href="<?= url("tag/view/{$tag->tag}"); ?>">
                        <div class="tag"><?= $tag->tag ?></div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
