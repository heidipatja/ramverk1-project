<?php

namespace Hepa19\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Hepa19\User\User;

/**
 * Example of FormModel implementation.
 */
class LoginUser extends FormModel
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
                "legend" => "Logga in"
            ],
            [
                "username" => [
                    "type"        => "text",
                    "label"        => "Användarnamn",
                    //"description" => "Here you can place a description.",
                    //"placeholder" => "Here is a placeholder",
                ],

                "password" => [
                    "type"        => "password",
                    "label"        => "Lösenord",
                    //"description" => "Here you can place a description.",
                    //"placeholder" => "Here is a placeholder",
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Logga in",
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

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $res = $user->verifyPassword($username, $password);

        if (!$res) {
           $this->form->rememberValues();
           $this->form->addOutput("Användarnamn eller lösenord matchade inte.");
           return false;
        }

        return true;
    }



    /**
     * Get user id by username
     */
    public function getUserIdByUsername($username)
    {
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("username", $username);
        return $user->id;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $username = $this->form->value("username");
        $session = $this->di->get("session");
        $session->set("username", $username);
        $session->set("userId", $this->getUserIdByUsername($username));

        $this->di->get("response")->redirect("user/profile")->send();
    }



    /**
     * Callback what to do if the form was unsuccessfully submitted, this
     * happen when the submit callback method returns false or if validation
     * fails. This method can/should be implemented by the subclass for a
     * different behaviour.
     */
    public function callbackFail()
    {
        $this->di->get("response")->redirectSelf()->send();
    }
}
