<?php

namespace Anax\View;

/**
 * View to display all questions
 */

// Gather incoming variables and use default values if not set
$question = isset($question) ? $question : null;
$answers = isset($answers) ? $answers : null;
$comments = isset($comments) ? $comments : null;
$urlToView = url("question");

// var_dump($question);

?>

<?php if (!$question) : ?>
<?php
    return;
endif;
?>

<div class="question">
    <h1><?= $question->title ?></h1>
    <div class="created">
        <img src="<?= $question->getGravatar($question->email, 25) ?>" alt="<?= $question->username ?>>"> Av <?= $question->username ?> <?= $question->created ?>
    </div>
    <div class="content">
        <?= $question->content ?>
    </div>
    <div class="tags">
        <?php foreach ($tags as $tag)
            if ($tag->question_id == $question->id) { ?>
                <div class="tag"><?= $tag->tag ?></div>
                <?php
            } ?>
    </div>
    <div class="comment-link">
        <a href="<?= url("comment/create?postId={$question->id}&questionId={$question->id}&type=question"); ?>">Kommentera</a>
    </div>
    <?php if ($activeUserId == $question->user_id) : ?>
    <div class="question-edit">
        <a href="<?= url("question/update/{$question->id}"); ?>">Redigera fråga</a>
    </div>
    <?php endif; ?>
</div>

<div class="comments">
    <div class="question-comments-length">Antal kommentarer: <?= count($comments) ?></div>
    <?php foreach ($comments as $comment) : ?>
        <div class="comment">
            <div class="comment-by">
                <img src="<?= $question->getGravatar($comment->email, 25) ?>" alt="<?= $comment->username ?>>"> Av <?= $comment->username ?> <?= $comment->created ?>
            </div>
            <div class="comment-content"><?= $comment->content ?></div>
        </div>
        <?php if ($activeUserId == $comment->user_id) : ?>
        <div class="question-edit">
            <a href="<?= url("comment/update/{$comment->id}"); ?>">Redigera kommentar</a>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<div class="answers">
    <div class="question-answers-length">Antal svar: <?= count($answers) ?></div>
    <?php foreach ($answers as $answer) : ?>
    <div class="answer">
        <div class="answer-by">
            <img src="<?= $question->getGravatar($answer->email, 25) ?>" alt="<?= $answer->username ?>>"> Av <?= $answer->username ?> <?= $answer->created ?>
        </div>
        <div class="answer-content"><?= $answer->content ?></div>
        <div class="add-comment"><a href="<?= url("comment/create?&postId={$answer->id}&questionId={$question->id}&type=answer"); ?>">Kommentera svar</a></div>
        <?php if ($activeUserId == $answer->user_id) : ?>
        <div class="answer-edit">
            <a href="<?= url("answer/update/{$answer->id}"); ?>">Redigera svar</a>
        </div>
        <?php endif; ?>
        <div class="answer-comments">
            <?php foreach ($answer->answerComments as $acomm) : ?>
            <div class="comment-by">
                <img src="<?= $question->getGravatar($acomm->email, 25) ?>" alt="<?= $acomm->username ?>>"> Av <?= $acomm->username ?> <?= $acomm->created ?>
            </div>
            <div class="comment"><?= $acomm->content ?></div>
            <?php if ($activeUserId == $acomm->user_id) : ?>
            <div class="question-edit">
                <a href="<?= url("comment/update/{$acomm->id}"); ?>">Redigera kommentar</a>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
