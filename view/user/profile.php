<?php

namespace Anax\View;

// var_dump($user);

?>

<h1>Profil</h1>

<div class="user-container">
    <div class="gravatar">
        <img src="<?= $gravatar ?>" alt="<?= $user->username ?>">
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

    <div class="user-edit-button">
        <a class="button save edit" href="<?= url("user/update/" . $user->id) ?>">Redigera</a>
    </div>

    <div class="user-logout">
        <a class="button logout" href="<?= url("user/logout") ?>">Logga ut</a>
    </div>
</div>
