<?php

/**
 * View to display user
 */

namespace Anax\View;

// Gather incoming variables and use default values if not set
$questions = isset($questions) ? $questions : null;

?>

<h1>Profil</h1>

 <div class="profile">
     <div class="profile-block">
         <div class="col1">
             <div class="gravatar">
                <img class="profile-img" src="<?= $user->getGravatar($user->email, 100) ?>" alt="<?= $user->username ?>>">
             </div>
         </div>
         <div class="col2">
             <div class="user-information">
                 <h2 class="username"><?= $user->username ?></h2>
                 <p class="ranking"><b>Ranking:</b> <?= $user->score ?></p>
             </div>
         </div>
     </div>

     <div class="user-presentation">
         <h3>Presentation</h3>
         <p><?= $user->presentation ?> </p>
     </div>

     <?php if ($activeUser == $user->id) : ?>
     <div class="links">
         <div class="user-edit-button">
             <a class="button save edit" href="<?= url("user/update/" . $user->id) ?>">Redigera</a>
         </div>
     </div>
     <?php endif; ?>
 </div>


<h2>Frågor</h2>

 <?php if (!$questions) : ?>
     <p><?= $user->username ?> har inte ställt några frågor än.</p>
 <?php endif; ?>

<div class="posts">
<?php foreach ($questions as $question) : ?>
    <div class="post">
        <div class="post-created">
            Postad <?= $question->created ?> av <?= $question->username ?>
        </div>
        <div class="post-content">
        <?= $question->title ?>
        </div>
        <div class="post-link">
             <a href="<?= url("question/view/" . $question->id) ?>">Se fråga</a>
        </div>
    </div>
<?php endforeach; ?>
</div>


<h2>Svar</h2>

<?php if (!$answers) : ?>
    <p><?= $user->username ?> har inte svarat på några frågor än.</p>
<?php endif; ?>

<div class="posts">
    <?php foreach ($answers as $answer) : ?>
        <div class="post">
            <div class="post-created">
                Postad <?= $answer->created ?> av <?= $answer->username ?>
            </div>
            <div class="post-content">
            <?= $answer->content ?>
            </div>
            <div class="post-link">
                 <a href="<?= url("question/view/" . $answer->question_id) ?>">Se fråga</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<h2>Kommentarer</h2>

<?php if (!$comments) : ?>
    <p><?= $user->username ?> har inte skrivit några kommentarer än.</p>
<?php endif; ?>

<div class="posts">
    <?php foreach ($comments as $comment) : ?>
        <div class="post">
            <div class="post-created">
                Postad <?= $comment->created ?> av <?= $comment->username ?>
            </div>
            <div class="post-content">
            <?= $comment->content ?>
            </div>
            <div class="post-link">
                <?php if ($comment->type == "answer") : ?>
                 <a href="<?= url("question/view/" . $comment->answer_id) ?>">Se fråga</a>
                <?php endif; ?>
                <?php if ($comment->type == "question") : ?>
                 <a href="<?= url("question/view/" . $comment->question_id) ?>">Se fråga</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<h2>Röstningar</h2>

<?php if (!$votes) : ?>
    <p><?= $user->username ?> har inte röstat på något än.</p>
<?php endif; ?>

<div class="posts">
    <div class="post">
        <p><?= $user->username ?> har röstat <?= $votes ?> gånger.</p>
    </div>
</div>
