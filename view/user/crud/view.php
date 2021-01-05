<?php

namespace Anax\View;

/**
 * View to display all questions
 */

 // Gather incoming variables and use default values if not set
 $questions = isset($questions) ? $questions : null;

// var_dump($questions);

 ?>

 <h1>Profil</h1>

 <div class="user-container">
     <h2><?= $user->username ?></h2>
     <div class="gravatar">
        <img src="<?= $user->getGravatar($user->email, 100) ?>" alt="<?= $user->username ?>>">
     </div>

     <div class="user-information">
         <h2>Information</h2>
         <p><b>Användarnamn:</b> <?= $user->username ?> </p>
         <p><b>E-post:</b> <a href="<?= url("mailto:") ?><?= $user->email ?>"><?= $user->email ?></a></p>
         <p><b>Poäng:</b> <?= $user->score ?></p>
     </div>

     <div class="user-presentation">
         <h2>Presentation</h2>
         <p><?= $user->presentation ?> </p>
     </div>
 </div>


<h2>Frågor</h2>

 <?php if (!$questions) : ?>
     <p><?= $user->username ?> har inte ställt några frågor än.</p>
 <?php
     return;
 endif;
 ?>

 <?php foreach ($questions as $question) : ?>
 <div class="question">
     <div class="question-title"><a href="<?= url("question/view/{$question->id}"); ?>"> <h3><?= $question->title ?></h3></a></div>
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


<h2>Svar</h2>

<?php if (!$answers) : ?>
    <p><?= $user->username ?> har inte svarat på några frågor än.</p>
<?php
    return;
endif;
?>

<div class="answers">
    <?php foreach ($answers as $answer) : ?>
        <div class="answer">
            <div class="answer-created">
                Postad <?= $answer->created ?> av <?= $answer->username ?>
            </div>
            <div class="answer-content">
            <?= $answer->content ?>
            </div>
            <div class="link">
                <a href="<?= url("question/view/{$answer->question_id}"); ?>">Till frågan</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
