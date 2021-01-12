<?php

namespace Hepa19\Comment\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Hepa19\Comment\Comment;

/**
 * Form to update an item.
 */
class UpdateComment extends FormModel
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
        $comment = $this->getComment($id);
        $this->questionId = $comment->post_id;
        $this->id = $id;
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Uppdatera kommentar",
                "escape-values" => false
            ],
            [
                "id" => [
                    "type" => "hidden",
                    "validation" => ["not_empty"],
                    "readonly" => true,
                    "value" => $comment->id,
                ],

                "content" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "value" => $comment->content,
                    "label" => "Kommentar"
                ],

                "user-id" => [
                    "type" => "hidden",
                    "value" => $comment->user_id,
                ],

                "post-id" => [
                    "type" => "hidden",
                    "value" => $comment->post_id,
                ],

                "type" => [
                    "type" => "hidden",
                    "value" => $comment->type,
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Spara",
                    "callback" => [$this, "callbackSubmit"]
                ],

                "reset" => [
                    "type"      => "reset",
                    "value" => "Återställ"
                ],
            ]
        );
    }



    /**
     * Get details on item to load form with.
     *
     * @param integer $id get details on item with id.
     *
     * @return Comment
     */
    public function getComment($id) : object
    {
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comment->find("id", $id);
        return $comment;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comment = $comment->find("id", $this->form->value("id"));
        $comment->content = $this->form->value("content");
        $comment->updated = date("Y-m-d H:i:s");
        $comment->save();
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
