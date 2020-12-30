<?php

namespace Hepa19\Answer;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Hepa19\Answer\HTMLForm\CreateAnswer;
use Hepa19\Answer\HTMLForm\DeleteAnswer;
use Hepa19\Answer\HTMLForm\UpdateAnswer;
use Hepa19\MyTextFilter\MyTextFilter;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class AnswerController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Initialize controller
     *
     */
    public function initialize()
    {
        $this->filter = new MyTextFilter();
    }



    /**
     * Show all items.
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $page = $this->di->get("page");
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));

        $page->add("answer/crud/view-all", [
            "items" => $answer->findAll(),
        ]);

        return $page->render([
            "title" => "A collection of items",
        ]);
    }



    /**
     * Handler with form to create a new item.
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        $page = $this->di->get("page");
        $form = new CreateForm($this->di);
        $form->check();

        $page->add("answer/crud/create", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Create a item",
        ]);
    }



    /**
     * Handler with form to delete an item.
     *
     * @return object as a response object
     */
    public function deleteAction() : object
    {
        $page = $this->di->get("page");
        $form = new DeleteAnswer($this->di);
        $form->check();

        $page->add("answer/crud/delete", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Radera fråga",
        ]);
    }



    /**
     * Handler with form to update an item.
     *
     * @param int $id the id to update.
     *
     * @return object as a response object
     */
    public function updateAction(int $id) : object
    {
        $page = $this->di->get("page");
        $form = new UpdateAnswer($this->di, $id);
        $form->check();

        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->findById($id);
        $answer->content = $this->filter->markdown($answer->content);

        $page->add("answer/crud/update", [
            "form" => $form->getHTML(),
            "questionId" => $answer->question_id
        ]);

        return $page->render([
            "title" => "Uppdatera fråga",
        ]);
    }
}
