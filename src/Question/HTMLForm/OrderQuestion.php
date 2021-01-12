<?php

namespace Hepa19\Question\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Hepa19\Question\Question;
use Hepa19\User\User;
use Hepa19\Tag\Tag;
use Hepa19\Tag\TagToQuestion;

/**
 * Form to create an item.
 */
class OrderQuestion extends FormModel
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
                "id" => "orderbyform",
                "escape-values" => false,
                "method" => "get"
            ],
            [
                "orderby" => [
                   "type" => "select",
                   "label" => "",

                   "options" => [
                       "default" => "Välj sortering",
                       "created desc" => "Skapad fallande",
                       "created asc" => "Skapad stigande",
                       "v.votesum desc" => "Ranking fallande",
                       "v.votesum asc" => "Ranking stigande"
                   ],
                ],

                // "submit" => [
                //     "type" => "submit",
                //     "value" => "Sortera",
                //     "callback" => [$this, "callbackSubmit"],
                // ],
            ]
        );
    }


    //
    // /**
    //  * Callback for submit-button which should return true if it could
    //  * carry out its work and false if something failed.
    //  *
    //  * @return bool true if okey, false if something went wrong.
    //  */
    // public function callbackSubmit() : bool
    // {
    //     return true;
    // }
    //
    //
    //
    //
    //
    // /**
    //  * Callback what to do if the form was successfully submitted, this
    //  * happen when the submit callback method returns true. This method
    //  * can/should be implemented by the subclass for a different behaviour.
    //  */
    // public function callbackSuccess()
    // {
    //     $this->di->get("response")->redirectSelf()->send();
    // }
    //
    //
    //
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
