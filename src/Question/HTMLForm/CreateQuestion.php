<?php

namespace Hepa19\Question\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Hepa19\Question\Question;
use Hepa19\User\User;

/**
 * Form to create an item.
 */
class CreateQuestion extends FormModel
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
                "legend" => "Ny fråga",
                "escape-values" => false
            ],
            [
                "title" => [
                    "type" => "text",
                   "label" => "Ämne"
                ],

                "content" => [
                    "type" => "textarea",
                    "label" => "Innehåll"
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Spara",
                    "callback" => [$this, "callbackSubmit"],
                ],

                "reset" => [
                    "type"      => "reset",
                    "value" => "Återställ"
                ],
            ]
        );
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $userId = $this->di->get("session")->get("userId");
        if (!$userId) {
            $this->di->get("response")->redirect("user/login")->send();
        }

        $title  = $this->form->value("title");
        $content = $this->form->value("content");

        if (!$title || !$content) {
            $this->form->rememberValues();
            $this->form->addOutput("Du måste fylla i ämne och innehåll.");
            return false;
        }

        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question->title = $title;
        $question->content = $content;
        $question->user_id = $userId;
        $question->save();
        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("question")->send();
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
