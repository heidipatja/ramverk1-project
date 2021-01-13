<?php

namespace Anax\View;

/**
 * View to create a new book.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$form = isset($form) ? $form : null;



?><h1>Logga in</h1>

<?= $form ?>

<br>

<div class="user-register">
    <a href="<?= url("user/create"); ?>">Registrera dig</a>
</div>
