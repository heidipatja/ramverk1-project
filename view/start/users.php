<?php

namespace Anax\View;

/**
 * Sidebar on start page with most popular users
 */

// Gather incoming variables and use default values if not set
$users = isset($users) ? $users : null;

?>

<?php if (!$users) : ?>
    <div class="user-sidebar">
        <h4>Aktiva användare</h4>
        <p>Det finns inga registrerade användare än!</p>
    </div>
<?php
    return;
endif;
?>


<div class="user-sidebar">
    <h4>Aktiva användare</h4>
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
</div>
