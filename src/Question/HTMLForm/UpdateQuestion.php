<?php

namespace Hepa19\Question\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Hepa19\Question\Question;

/**
 * Form to update an item.
 */
class UpdateQuestion extends FormModel
{
    /**
     * Constructor injects with DI container and the id to update.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     * @param integer             $id to update
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $question = $this->getQuestion($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Redigera fråga",
                "escape-values" => false
            ],
            [
                "id" => [
                    "type" => "hidden",
                    "validation" => ["not_empty"],
                    "readonly" => true,
                    "value" => $question->id,
                ],

                "title" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "value" => $question->title,
                    "label" => "Ämne"
                ],

                "content" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "value" => $question->content,
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
     * Get details on question
     *
     * @param integer $id get details on question with id.
     *
     * @return Question
     */
    public function getQuestion($id) : object
    {
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question->find("id", $id);
        return $question;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $title = $this->form->value("title");
        $content = $this->form->value("content");
        $id = $this->form->value("id");

        if (!$title) {
           $this->form->rememberValues();
           $this->form->addOutput("Frågan måste ha en ämnesrad.");
           return false;
        }

        if (!$content) {
           $this->form->rememberValues();
           $this->form->addOutput("Frågan måste ha en beskrivning.");
           return false;
        }

        $question = $this->getQuestion($id);
        $question->title = $title;
        $question->content = $content;
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
