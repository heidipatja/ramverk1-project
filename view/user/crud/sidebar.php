<?php

/**
 * View to display sidebar on user page
 */

namespace Anax\View;

// Gather incoming variables and use default values if not set
$activeUser = isset($activeUser) ? $activeUser : null;

?>

<?php if (!$activeUser) : ?>
    <div class="user-sidebar">
        <h4>Logga in</h4>
        <p>Logga in för att svara och rösta.</p>
        <?= $form ?>
    </div>
    <?php
    return;
endif;
?>

<div class="user-sidebar">
    <h4>Mina sidor</h4>
    <a href="<?= url("user/profile"); ?>">Mina sidor</a>
</div>
