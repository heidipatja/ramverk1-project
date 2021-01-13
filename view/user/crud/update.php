<?php

/**
 * View to update user
 */

namespace Anax\View;

// Gather incoming variables and use default values if not set
$form = isset($form) ? $form : null;



?><h1>Uppdatera profil</h1>

<?= $form ?>

<br>

<div class="user-register">
    <a href="<?= url("user/profile"); ?>">Tillbaka</a>
</div>
