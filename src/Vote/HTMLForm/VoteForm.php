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
        $this->voteValue = $this->getVoteValue($voteType);
        $this->userId = $userId;
        $this->voted = $this->getClass($userId, $postId, $type, $voteType);
        $this->form->create(
            [
                "id" => __CLASS__ . "\\" . $type . $postId . "\\user" . $userId . "\\" . $voteType,
                "escape-values" => false,
                "class" => "voteform"
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

                "vote-type" => [
                    "type" => "hidden",
                    "validation" => ["not_empty"],
                    "value" => $voteType
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => $icon,
                    "callback" => [$this, "callbackSubmit"],
                    "class" => "fa " . $this->voted
                ],
            ]
        );
    }



    /**
     * Return icon
     *
     * @return string
     */
    public function getIcon($voteType): string
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
    public function getVoteValue($voteType): int
    {
        if ($voteType == "up") {
            return 1;
        } else {
            return -1;
        }
    }



    /**
     * Return class for arrow
     *
     * @return string
     */
    public function getClass($userId, $postId, $type, $voteType)
    {
        if (!$userId || !$postId) {
            return;
        }

        $vote = new Vote();
        $vote->setDb($this->di->get("dbqb"));
        $voted = $vote->getVote($userId, $postId, $type);

        if ($voted->vote == 1 && $voteType == "up") {
            return "voted up";
        } elseif ($voted->vote == -1 && $voteType == "down") {
            return "voted down";
        }
    }



    /**
     * Checks if user has already voted on the post
     *
     * @return bool true if already voted, else false
     */
    public function hasAlreadyVoted($userId, $postId, $type): bool
    {
        $vote = new Vote();
        $vote->setDb($this->di->get("dbqb"));
        $voted = $vote->getVote($userId, $postId, $type);

        if ($voted->id == null) {
            return false;
        }

        return true;
    }



    /**
     * Checks if logged in user is the same as post creator
     *
     * @return bool
     */
    public function isOwnPost($userId, $postId, $type): bool
    {
        if ($type == "question") {
            $question = new Question();
            $question->setDb($this->di->get("dbqb"));
            $question = $question->findById($postId);

            if ($question->user_id == $userId) {
                return true;
            }
        }

        if ($type == "comment") {
            $comment = new Comment();
            $comment->setDb($this->di->get("dbqb"));
            $comment = $comment->findById($postId);

            if ($comment->user_id == $userId) {
                return true;
            }
        }

        if ($type == "answer") {
            $answer = new Answer();
            $answer->setDb($this->di->get("dbqb"));
            $answer = $answer->findById($postId);

            if ($answer->user_id == $userId) {
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
    public function callbackSubmit(): bool
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

        if ($this->hasAlreadyVoted($userId, $postId, $type)) {
            $vote = new Vote();
            $vote->setDb($this->di->get("dbqb"));
            $voteToChange = $vote->getVote($userId, $postId, $type);
            $voteToChange->vote = $this->voteValue;
            $voteToChange->save();
            return true;
        }

        $newVote = new Vote();
        $newVote->setDb($this->di->get("dbqb"));
        $newVote->user_id = $userId;
        $newVote->post_id = $postId;
        $newVote->type = $type;
        $newVote->vote = $this->voteValue;
        $newVote->save();
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
