<?php

/**
 * View to login user
 */

namespace Anax\View;

// Gather incoming variables and use default values if not set
$form = isset($form) ? $form : null;



?><h1>Logga in</h1>

<?= $form ?>

<br>

<div class="user-register">
    <a href="<?= url("user/create"); ?>">Registrera dig</a>
</div>
