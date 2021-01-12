<?php

namespace Anax\View;

/**
 * Sidebar on user page
 */

 // Gather incoming variables and use default values if not set
 $activeUser = isset($activeUser) ? $activeUser : null;

?>


<div class="user-sidebar">
    <h4>Länkar</h4>
    <div>
        <a href="<?= url("user/logout"); ?>">Logga ut</a>
    </div>
</div>
