<?php

namespace Hepa19\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Hepa19\User\User;

/**
 * Example of FormModel implementation.
 */
class CreateUser extends FormModel
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
                "escape-values" => false
            ],
            [
                "username" => [
                    "type"        => "text",
                    "label"        => "Användarnamn",
                ],

                "email" => [
                    "type"        => "email",
                    "label"        => "E-post",
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
        $email      = $this->form->value("email");
        $password      = $this->form->value("password");
        $passwordAgain = $this->form->value("password-again");

        // Check password matches
        if ($password !== $passwordAgain ) {
            $this->form->rememberValues();
            $this->form->addOutput("Lösenordet matchade inte.");
            return false;
        }

        try {
            $user = new User();
            $user->setDb($this->di->get("dbqb"));
            $user->username = $username;
            $user->email = $email;
            $user->setPassword($password);
            $user->save();
            return true;
        } catch (\Anax\Database\Exception\Exception $e) {
            return false;
        }
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("user/login")->send();
    }



    /**
     * Callback what to do if the form was unsuccessfully submitted, this
     * happen when the submit callback method returns false or if validation
     * fails. This method can/should be implemented by the subclass for a
     * different behaviour.
     */
    public function callbackFail()
    {
        $this->form->addOutput("E-post eller användarnamn används redan. Prova igen.");
        $this->di->get("response")->redirectSelf()->send();
    }
}
