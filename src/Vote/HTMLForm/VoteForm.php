<?php

namespace Hepa19\Vote\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Hepa19\Vote\Vote;
use Hepa19\Answer\Answer;
use Hepa19\Comment\Comment;
use Hepa19\Question\Question;

/**
 * Form to create an item.
 */
class VoteForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $postId, $userId, $type, $voteType)
    {
        parent::__construct($di);
        $icon = $this->getIcon($voteType);
        $this->vote = $this->getVote($voteType);
        $this->form->create(
            [
                "id" => __CLASS__ . "\\" . $type . $postId . "\\user" . $userId . "\\" . $voteType,
                "legend" => "Rösta",
                "escape-values" => false
            ],
            [
                "user-id" => [
                    "type" => "hidden",
                    "validation" => ["not_empty"],
                    "value" => $userId
                ],

                "post-id" => [
                    "type" => "hidden",
                    "validation" => ["not_empty"],
                    "value" => $postId
                ],

                "type" => [
                    "type" => "hidden",
                    "validation" => ["not_empty"],
                    "value" => $type
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => $icon,
                    "callback" => [$this, "callbackSubmit"],
                    "class" => "fa"
                ],
            ]
        );
    }



    /**
     * Return icon
     *
     * @return string
     */
    public function getIcon($voteType) : string
    {
        if ($voteType == "up") {
            return "";
        } else {
            return "";
        }
    }



    /**
     * Return vote
     *
     * @return int
     */
    public function getVote($voteType) : int
    {
        if ($voteType == "up") {
            return 1;
        } else {
            return -1;
        }
    }



    /**
     * Checks if logged in user is the same as post creator
     *
     * @return bool
     */
    public function isOwnPost($userId, $postId, $type) : bool
    {
        $activeUserId = $this->di->get("session")->get("userId");

        if ($type == "question") {
            $question = new Question();
            $question->setDb($this->di->get("dbqb"));
            $question->findById($postId);

            if ($question->user_id == $activeUserId) {
                return true;
            }
        }

        if ($type == "comment") {
            $comment = new Comment();
            $comment->setDb($this->di->get("dbqb"));
            $comment->findById($postId);

            if ($comment->user_id == $activeUserId) {
                return true;
            }
        }

        if ($type == "answer") {
            $answer = new Answer();
            $answer->setDb($this->di->get("dbqb"));
            $answer->findById($postId);

            if ($answer->user_id == $activeUserId) {
                return true;
            }
        }

        return false;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $userId = $this->form->value("user-id");
        $postId = $this->form->value("post-id");
        $type = $this->form->value("type");

        if (!$userId) {
            return false;
        }

        if ($this->isOwnPost($userId, $postId, $type)) {
            return true;
        }

        $vote = new Vote();
        $vote->setDb($this->di->get("dbqb"));
        $vote->user_id = $userId;
        $vote->post_id = $postId;
        $vote->type = $type;
        $vote->vote = $this->vote;
        $vote->save();
        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirectSelf()->send();
    }



    /**
     * Callback what to do if the form was unsuccessfully submitted, this
     * happen when the submit callback method returns false or if validation
     * fails. This method can/should be implemented by the subclass for a
     * different behaviour.
     */
    public function callbackFail()
    {
        $this->di->get("response")->redirect("user/login")->send();
    }
}
