<?php

namespace Hepa19\Answer\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Hepa19\Answer\Answer;

/**
 * Form to create an item.
 */
class CreateAnswer extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $questionId, $userId)
    {
        parent::__construct($di);
        $this->questionId = $questionId;
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Nytt svar",
                "escape-values" => false,
            ],
            [
                "content" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "label" => "Svar",
                ],

                "question-id" => [
                    "type" => "hidden",
                    "value" => $questionId,
                ],

                "user-id" => [
                    "type" => "hidden",
                    "value" => $userId,
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
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->content  = $this->form->value("content");
        $answer->question_id = $this->form->value("question-id");
        $answer->user_id = $this->form->value("user-id");
        $answer->accepted = 0;
        $answer->save();
        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("question/view/{$this->questionId}")->send();
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
