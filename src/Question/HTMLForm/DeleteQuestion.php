<?php

namespace Hepa19\Question\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Hepa19\Question\Question;

/**
 * Form to delete an item.
 */
class DeleteQuestion extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $question = $this->getQuestion($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Radera fråga",
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
                    "label" => "Ämne",
                    "readonly" => true,
                ],

                "content" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "value" => $question->content,
                    "label" => "Innehåll",
                    "readonly" => true,
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Radera",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    /**
     * Get question info
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



    // /**
    //  * Get all items as array suitable for display in select option dropdown.
    //  *
    //  * @return array with key value of all items.
    //  */
    // protected function getAllItems() : array
    // {
    //     $userId = $this->di->get("session")->get("userId");
    //     if (!$userId) {
    //         $this->di->get("response")->redirect("user/login")->send();
    //     }
    //
    //     $question = new Question();
    //     $question->setDb($this->di->get("dbqb"));
    //
    //     $questions = ["-1" => "Välj fråga..."];
    //     foreach ($question->findAll() as $obj) {
    //         if ($obj->user_id == $userId) {
    //             $questions[$obj->id] = "{$obj->created}: {$obj->title} ({$obj->id})";
    //         }
    //     }
    //
    //     return $questions;
    // }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question->find("id", $this->form->value("id"));
        $question->deleted = date("Y-m-d H:i:s");
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
