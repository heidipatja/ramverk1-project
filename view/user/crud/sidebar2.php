<?php

/**
 * Sidebar on user page
 */

namespace Anax\View;

 // Gather incoming variables and use default values if not set
 $activeUser = isset($activeUser) ? $activeUser : null;

?>

<?php if (!$activeUser) : ?>
    <div class="user-sidebar">
        <h4>Logga in</h4>
        <p>Logga inför att svara, kommentera eller rösta eller för att redigera din profil.</p>
        <?= $form ?>
    </div>
    <?php
    return;
endif;
?>

<div class="user-sidebar">
    <h4>Användare</h4>
    <a href="<?= url("user"); ?>">Alla användare</a>
</div>
