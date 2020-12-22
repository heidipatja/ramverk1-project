<?php
/**
 * Supply the basis for the navbar as an array.
 */

$logout = [
    "text" => "Logga ut",
    "url" => "user/logout",
    "title" => "Logga ut"
];

$login = [
    "text" => "Logga in",
    "url" => "user/login",
    "title" => "Logga in"
];

$profile = [
    "text" => "Mina sidor",
    "url" => "user/profile",
    "title" => "Mina sidor"
];

$menu = [
    // Use for styling the menu
    "id" => "rm-menu",
    "wrapper" => null,
    "class" => "rm-default rm-mobile",

    // Here comes the menu items
    "items" => [
        [
            "text" => "Hem",
            "url" => "",
            "title" => "Första sidan, börja här.",
        ],
    ],
];

$username = $_SESSION["username"] ?? null;

if ($username) {
    array_push($menu["items"], $profile);
    array_push($menu["items"], $logout);
} else {
    array_push($menu["items"], $login);
}

return $menu;
