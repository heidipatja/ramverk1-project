<?php

namespace Hepa19\Comment\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Hepa19\Comment\Comment;

/**
 * Form to create an item.
 */
class CreateComment extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $userId, $postId, $questionId, $type)
    {
        parent::__construct($di);
        $this->questionId = $questionId;
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Ny kommentar",
                "escape-values" => false
            ],
            [
                "content" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                ],

                "user-id" => [
                    "type" => "hidden",
                    "value" => $userId,
                ],

                "post-id" => [
                    "type" => "hidden",
                    "value" => $postId,
                ],

                "type" => [
                    "type" => "hidden",
                    "value" => $type,
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
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comment->content = $this->form->value("content");
        $comment->user_id = $this->form->value("user-id");
        $comment->post_id = $this->form->value("post-id");
        $comment->type = $this->form->value("type");
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



    // /**
    //  * Callback what to do if the form was unsuccessfully submitted, this
    //  * happen when the submit callback method returns false or if validation
    //  * fails. This method can/should be implemented by the subclass for a
    //  * different behaviour.
    //  */
    // public function callbackFail()
    // {
    //     $this->di->get("response")->redirectSelf()->send();
    // }
}
