<?php

/**
 * View to display all users.
 */

namespace Anax\View;

// Gather incoming variables and use default values if not set
$users = isset($users) ? $users : null;


?><h1>Användare</h1>

<?php if (!$users) : ?>
    <p>Det finns inga användare än!</p>
    <?php
    return;
endif;
?>

<?php foreach ($users as $user) : ?>
<div class="user">
    <div class="col1">
        <div class="user-gravatar">
            <a href="<?= url("user/view/{$user->username}"); ?>"><img class="profile-img" src="<?= $user->getGravatar($user->email, 50) ?>" alt="<?= $user->username ?>>"></a>
        </div>
    </div>
    <div class="col2">
        <div class="user-info">
            <div class="user-username">
                <a class="username" href="<?= url("user/view/{$user->username}"); ?>"><?= $user->username ?></a>
            </div>
            <div class="user-score">
                <p class="ranking">Ranking: <?= $user->score ?></p>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
