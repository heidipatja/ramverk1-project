<?php

namespace Hepa19\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Hepa19\User\User;

/**
 * Edit user form
 */
class UpdateUser extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $user = $this->getUser($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Redigera användare",
                "escape-values" => false
            ],
            [
                "id" => [
                    "type" => "hidden",
                    "validation" => ["not_empty"],
                    "readonly" => true,
                    "value" => $user->id,
                ],
                "username" => [
                    "type"        => "text",
                    "label"        => "Användarnamn",
                    "value" => $user->username,
                    "readonly" => true
                ],

                "presentation" => [
                    "type"        => "textarea",
                    "label"        => "Presentation",
                    "value" => $user->presentation,
                ],

                "old-password" => [
                    "type"        => "password",
                    "label"        => "Lösenord",
                ],

                "new-password" => [
                    "type"        => "password",
                    "label"        => "Nytt lösenord",
                ],

                "new-password-again" => [
                    "type"        => "password",
                    "label"        => "Bekräfta nytt lösenord",
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Spara",
                    "callback" => [$this, "callbackSubmit"]
                ],

                "reset" => [
                    "type" => "reset",
                    "value" => "Återställ"
                ],
            ]
        );
    }



    /**
     * Get details on user to load form with.
     *
     * @param integer $id get details user with id
     *
     * @return User
     */
    public function getUser($id) : object
    {
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("id", $id);
        return $user;
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
        $oldPassword      = $this->form->value("old-password");
        $newPassword      = $this->form->value("new-password");
        $newPasswordAgain      = $this->form->value("new-password-again");
        $presentation = $this->form->value("presentation");

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $validPassword = $user->verifyPassword($username, $oldPassword);

        if (!$validPassword) {
           $this->form->rememberValues();
           $this->form->addOutput("Fel lösenord. Prova igen.");
           return false;
        }

        if ($newPassword !== $newPasswordAgain) {
           $this->form->rememberValues();
           $this->form->addOutput("De nya lösenorden matchade inte.");
           return false;
        }

        if ($newPassword) {
            $user->setPassword($newPassword);
        }

        $user->presentation = $presentation;
        $user->save();

        return true;
    }




    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
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
