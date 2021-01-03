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
class AcceptForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $answerId, $userId, $status)
    {
        parent::__construct($di);
        $statusClass = $this->getClass($status);
        $this->form->create(
            [
                "id" => __CLASS__ . "\\" . $answerId,
                "legend" => "Acceptera svar",
                "escape-values" => false
            ],
            [
                "user-id" => [
                    "type" => "hidden",
                    "validation" => ["not_empty"],
                    "value" => $userId
                ],

                "answer-id" => [
                    "type" => "hidden",
                    "validation" => ["not_empty"],
                    "value" => $answerId
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "",
                    "callback" => [$this, "callbackSubmit"],
                    "class" => $statusClass
                ],
            ]
        );
    }



    /**
     * Return icon
     *
     * @return string
     */
    public function getClass($status) : string
    {
        if ($status == 1) {
            return "accepted fa";
        } else {
            return "fa";
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
     * Toggle accepted status
     */
    public function toggleAccepted($answer)
    {
        if ($answer->accepted == 1) {
            $answer->accepted = 0;
        } else {
            $this->removeAccepted();
            $answer->accepted = 1;
        }

        return $answer;
    }



    /**
     * Toggle accepted status
     */
    public function removeAccepted()
    {
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->find("accepted", 1);

        if ($answer->accepted != null) {
            $answer->accepted = 0;
            $answer->save();
        }
    }



    // /**
    //  * Checks if logged in user is the same as answer creator
    //  *
    //  * @return bool
    //  */
    // public function isOwnPost($userId, $answerId, $type) : bool
    // {
    //     $activeUserId = $this->di->get("session")->get("userId");
    //
    //     if ($type == "answer") {
    //         $answer = new Answer();
    //         $answer->setDb($this->di->get("dbqb"));
    //         $answer->findById($answerId);
    //
    //         if ($answer->user_id == $activeUserId) {
    //             return true;
    //         }
    //     }
    //
    //     return false;
    // }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $userId = $this->form->value("user-id");
        $answerId = $this->form->value("answer-id");
        $type = $this->form->value("type");

        if (!$userId) {
            return false;
        }

        // if ($this->isOwnPost($userId, $answerId, $type)) {
        //     return true;
        // }

        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->findById($answerId);
        $this->toggleAccepted($answer);

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
