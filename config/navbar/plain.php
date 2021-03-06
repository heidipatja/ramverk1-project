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
    "class" => "my-navbar",

    // Here comes the menu items/structure
    "items" => [
        [
            "text" => "Hem",
            "url" => "",
            "title" => "Första sidan, börja här.",
        ],
        [
            "text" => "Frågor",
            "url" => "question",
            "title" => "Frågor",
        ],
        [
            "text" => "Taggar",
            "url" => "tag",
            "title" => "Taggar",
        ],
        [
            "text" => "Användare",
            "url" => "user",
            "title" => "Användare",
        ],
        [
            "text" => "Om",
            "url" => "about",
            "title" => "Om webbplatsen",
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
