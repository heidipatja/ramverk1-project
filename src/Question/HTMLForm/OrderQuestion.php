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
            ]
        );
    }
}
