<?php

namespace Anax\View;

/**
 * View to display all users.
 */

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
    <div class="user-gravatar">
        <img src="<?= $user->getGravatar($user->email, 25) ?>" alt="<?= $user->username ?>>">
    </div>
    <div class="user-info">
        <div class="user-username">
            <a href="<?= url("user/view/{$user->username}"); ?>"> <h2 class="username"><?= $user->username ?></h2></a>
        </div>
        <div class="user-score">
            <p>Poäng: <?= $user->score ?></p>
        </div>
    </div>
</div>
<?php endforeach; ?>
