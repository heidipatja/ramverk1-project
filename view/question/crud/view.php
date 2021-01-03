<?php

namespace Anax\View;

use Hepa19\Vote\HTMLForm\VoteForm;

/**
 * View to display all questions
 */

// Gather incoming variables and use default values if not set
$question = isset($question) ? $question : null;
$answers = isset($answers) ? $answers : null;
$comments = isset($comments) ? $comments : null;

// var_dump($question);
// var_dump($votes);
// var_dump($voteSum);

?>

<?php if (!$question) : ?>
<?php
    return;
endif;
?>

<h1><?= $question->title ?></h1>
<div class="question">
    <div class="col1">
        <div class="created">
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
    <div class="col2">
        <div class="votes">
            <?= $upvoteQ ?>
            <p><?= $voteSum ?></p>
            <?= $downvoteQ ?>
        </div>
    </div>
</div>

<div class="links">
    <?php if ($activeUserId == $question->user_id) : ?>
    <div class="delete">
        <a href="<?= url("question/delete/{$question->id}"); ?>">Radera</a>
    </div>
    <div class="edit">
        <a href="<?= url("question/update/{$question->id}"); ?>">Redigera</a>
    </div>
<?php endif; ?>
    <div class="comment">
        <a href="<?= url("comment/create?postId={$question->id}&questionId={$question->id}&type=question"); ?>">Kommentera</a>
    </div>
</div>

<div class="comments">
    <div class="question-comments-length">
        <?php if (count($comments) == 1) : ?>
        <?= count($comments) ?> kommentar
        <?php else :?>
        <?= count($comments) ?> kommentarer
        <?php endif; ?>
    </div>
    <?php foreach ($comments as $comment) : ?>
        <div class="comment">
            <div class="col1">
                <div class="comment-by">
                    <img src="<?= $question->getGravatar($comment->email, 25) ?>" alt="<?= $comment->username ?>>"> Av <?= $comment->username ?> <?= $comment->created ?>
                </div>
                <div class="comment-content"><?= $comment->content ?></div>
            </div>
            <div class="col2">
                <div class="votes">
                    <?= $comment->upvote ?>
                    <?= $comment->voteSum ?>
                    <?= $comment->downvote ?>
                </div>
            </div>
        </div>
        <?php if ($activeUserId == $comment->user_id) : ?>
        <div class="links">
            <div class="comment-edit">
                <a href="<?= url("comment/delete/{$comment->id}"); ?>">Radera</a>
            </div>
            <div class="comment-edit">
                <a href="<?= url("comment/update/{$comment->id}"); ?>">Redigera</a>
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<div class="answers">
    <div class="question-answers-length">
        <h3><?= count($answers) ?> svar</h3>
    </div>
    <?php foreach ($answers as $answer) : ?>
    <div class="answer">
        <div class="col1">
            <div class="by">
                <img src="<?= $question->getGravatar($answer->email, 25) ?>" alt="<?= $answer->username ?>>"> Av <?= $answer->username ?> <?= $answer->created ?>
            </div>
            <div class="content"><?= $answer->content ?></div>
            <div class="links">
                <?php if ($activeUserId == $answer->user_id) : ?>
                <div class="answer-delete">
                    <a href="<?= url("answer/delete/{$answer->id}"); ?>">Radera</a>
                </div>
                <div class="answer-edit">
                    <a href="<?= url("answer/update/{$answer->id}"); ?>">Redigera</a>
                </div>
                <?php endif; ?>
                <div class="add-comment"><a href="<?= url("comment/create?&postId={$answer->id}&questionId={$question->id}&type=answer"); ?>">Kommentera</a></div>
            </div>
            <div class="comments">
                <div class="col1">
                    <?php foreach ($answer->answerComments as $acomm) : ?>
                    <div class="comment-by">
                        <img src="<?= $question->getGravatar($acomm->email, 25) ?>" alt="<?= $acomm->username ?>>"> Av <?= $acomm->username ?> <?= $acomm->created ?>
                    </div>
                    <div class="comment"><?= $acomm->content ?></div>
                    <?php if ($activeUserId == $acomm->user_id) : ?>
                    <div class="links">
                        <div class="comment-edit">
                            <a href="<?= url("comment/delete/{$acomm->id}"); ?>">Radera</a>
                        </div>
                        <div class="comment-edit">
                            <a href="<?= url("comment/update/{$acomm->id}"); ?>">Redigera</a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="col2">
                    <div class="votes">
                        <?= $acomm->upvote ?>
                        <?= $acomm->voteSum ?>
                        <?= $acomm->downvote ?>
                    </div>
                    <div class="accepted">
                        <?= $answer->acceptForm ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col2">
            <div class="votes">
                <?= $answer->upvote ?>
                <?= $answer->voteSum ?>
                <?= $answer->downvote ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
