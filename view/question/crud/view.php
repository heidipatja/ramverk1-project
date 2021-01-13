<?php

/**
 * View to display all questions
 */

namespace Anax\View;

// Gather incoming variables and use default values if not set
$question = isset($question) ? $question : null;
$answers = isset($answers) ? $answers : null;
$comments = isset($comments) ? $comments : null;

?>

<?php if (!$question) : ?>
    <?php
    return;
endif;
?>

<div class="question">
    <div class="question-body">
        <div class="col1">
            <div class="votes q">
                <?= $upvoteQ ?>
                <p class="vote-sum"><?= $voteSum ?></p>
                <?= $downvoteQ ?>
            </div>
            <div class="length">
                <p class="answer-length number"><?= count($answers) ?></p>
                <p class="answer-length">svar</p>
            </div>
        </div>
        <div class="col2">
            <h1 class="question-heading"><?= $question->title ?></h1>
            <div class="created">
                <img src="<?= $question->getGravatar($question->email, 25) ?>" alt="<?= $question->username ?>>"> Av <a href="<?= url("user/view/{$question->username}"); ?>"><?= $question->username ?></a> <?= $question->created ?>
            </div>
            <div class="content">
                <?= $question->content ?>
            </div>
            <div class="tags">
                <?php foreach ($tags as $tag) : ?>
                    <?php if ($tag->question_id == $question->id) : ?>
                        <a href="<?= url("tag/view/{$tag->tag}"); ?>">
                            <div class="tag"><?= $tag->tag ?></div>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="links">
                <?php if ($activeUserId == $question->user_id) : ?>
                <div class="delete-link">
                    <a href="<?= url("question/delete/{$question->id}"); ?>">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
                <div class="edit-link">
                    <a href="<?= url("question/update/{$question->id}"); ?>">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
                <?php endif; ?>
                <div class="comment-link">
                    <a class="button comm" href="<?= url("comment/create?postId={$question->id}&questionId={$question->id}&type=question"); ?>">Kommentera</a>
                </div>
                <div class="answer-link">
                    <a class="button answ" href="<?= url("answer/create/{$question->id}"); ?>">Svara</a>
                </div>
            </div>

            <div class="comments">
                <div class="length">
                    <?php if (count($comments) == 1) : ?>
                        <?= count($comments) ?> kommentar
                    <?php else :?>
                        <?= count($comments) ?> kommentarer
                    <?php endif; ?>
                </div>
                <?php foreach ($comments as $comment) : ?>
                    <div class="comment">
                        <div class="comment-body">
                            <div class="col1">
                                <div class="votes">
                                    <?= $comment->upvote ?>
                                    <p class="vote-sum"><?= $comment->voteSum ?></p>
                                    <?= $comment->downvote ?>
                                </div>
                            </div>
                            <div class="col2">
                                <div class="by">
                                    <img src="<?= $question->getGravatar($comment->email, 25) ?>" alt="<?= $comment->username ?>>"> Av <a href="<?= url("user/view/{$comment->username}"); ?>"><?= $comment->username ?></a> <?= $comment->created ?>
                                </div>
                                <div class="comment-content"><?= $comment->content ?></div>
                            </div>
                        </div>
                        <?php if ($activeUserId == $comment->user_id) : ?>
                        <div class="links">
                            <div class="delete-link">
                                <a href="<?= url("comment/delete/{$comment->id}"); ?>">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                            <div class="edit-link">
                                <a href="<?= url("comment/update/{$comment->id}"); ?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="answers">
    <div class="cols">
        <div class="length">
            <h3><?= count($answers) ?> svar</h3>
        </div>
        <div class="orderby">
            <?= $orderForm ?>
        </div>
    </div>
    <?php foreach ($answers as $answer) : ?>
    <div class="answer">
        <div class="answer-body">
            <div class="col1">
                <div class="votes">
                    <?= $answer->upvote ?>
                    <p class="vote-sum"><?= $answer->votesum ?></p>
                    <?= $answer->downvote ?>
                </div>
                <div class="accepted">
                    <?= $answer->acceptForm ?>
                </div>
            </div>
            <div class="col2">
                <div class="by">
                    <img src="<?= $question->getGravatar($answer->email, 25) ?>" alt="<?= $answer->username ?>>"> Av <a href="<?= url("user/view/{$answer->username}"); ?>"><?= $answer->username ?></a> <?= $answer->created ?>
                </div>
                <div class="content"><?= $answer->content ?></div>
                <div class="links">
                    <?php if ($activeUserId == $answer->user_id) : ?>
                    <div class="delete-link">
                        <a href="<?= url("answer/delete/{$answer->id}"); ?>">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                    <div class="edit-link">
                        <a href="<?= url("answer/update/{$answer->id}"); ?>">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    <?php endif; ?>
                    <div class="comment-link">
                        <a class="button comm" href="<?= url("comment/create?&postId={$answer->id}&questionId={$question->id}&type=answer"); ?>">Kommentera</a></div>
                </div>
                <div class="comments">
                    <?php foreach ($answer->answerComments as $acomm) : ?>
                    <div class="comment">
                        <div class="col1">
                            <div class="votes">
                                <?= $acomm->upvote ?>
                                <p class="vote-sum"><?= $acomm->votesum ?></p>
                                <?= $acomm->downvote ?>
                            </div>
                        </div>
                        <div class="col2">
                            <div class="by">
                                <img src="<?= $question->getGravatar($acomm->email, 25) ?>" alt="<?= $acomm->username ?>>"> Av <a href="<?= url("user/view/{$acomm->username}"); ?>"><?= $acomm->username ?></a> <?= $acomm->created ?>
                            </div>
                            <div class="comment-content"><?= $acomm->content ?></div>
                            <?php if ($activeUserId == $acomm->user_id) : ?>
                            <div class="links">
                                <div class="delete-link">
                                    <a href="<?= url("comment/delete/{$acomm->id}"); ?>">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                                <div class="edit-link">
                                    <a href="<?= url("comment/update/{$acomm->id}"); ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <?php endforeach; ?>
</div>
