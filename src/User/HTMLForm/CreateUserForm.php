<?php

namespace Hepa19\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Hepa19\User\User;

/**
 * Example of FormModel implementation.
 */
class CreateUserForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Ny användare",
            ],
            [
                "username" => [
                    "type"        => "text",
                    "label"        => "Användarnamn",
                ],

                "password" => [
                    "type"        => "password",
                    "label"        => "Lösenord",
                ],

                "password-again" => [
                    "type"        => "password",
                    "validation" => [
                        "match" => "password"
                    ],
                    "label"        => "Bekräfta lösenord",
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Spara",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        // Get values from the submitted form
        $username       = $this->form->value("username");
        $password      = $this->form->value("password");
        $passwordAgain = $this->form->value("password-again");

        // Check password matches
        if ($password !== $passwordAgain ) {
            $this->form->rememberValues();
            $this->form->addOutput("Lösenordet matchade inte.");
            return false;
        }

        // Save to database
        // $db = $this->di->get("dbqb");
        // $password = password_hash($password, PASSWORD_DEFAULT);
        // $db->connect()
        //    ->insert("User", ["username", "password"])
        //    ->execute([$username])
        //    ->fetch();
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->username = $username;
        $user->setPassword($password);
        $user->save();

        $this->form->addOutput("Användaren skapades.");
        return true;
    }
}
