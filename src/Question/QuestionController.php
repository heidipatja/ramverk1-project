<?php

namespace Hepa19\Question;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Hepa19\Question\HTMLForm\CreateQuestion;
use Hepa19\Question\HTMLForm\EditQuestion;
use Hepa19\Question\HTMLForm\DeleteQuestion;
use Hepa19\Question\HTMLForm\UpdateQuestion;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class QuestionController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * @var $data description
     */
    //private $data;



    // /**
    //  * The initialize method is optional and will always be called before the
    //  * target method/action. This is a convienient method where you could
    //  * setup internal properties that are commonly used by several methods.
    //  *
    //  * @return void
    //  */
    // public function initialize() : void
    // {
    //     ;
    // }


    /**
     * See if user id in session, otherwise redirect to login
     *
     */
    public function checkIfLoggedIn()
    {
        $session = $this->di->get("session");

        if (!$session->get("userId")) {
            return $this->di->response->redirect("user/login");
        }
    }



    /**
     * Show all questions
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $page = $this->di->get("page");
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));

        $questions2users = $question->findAll();
        $questions2users = $question->joinTable("User", "Question", "Question.user_id = User.id");

        $page->add("question/crud/view-all", [
            "questions" => $questions2users
        ]);

        return $page->render([
            "title" => "Frågor",
        ]);
    }



    /**
     * Handler with form to create a new item.
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        $this->checkIfLoggedIn();

        $page = $this->di->get("page");
        $form = new CreateQuestion($this->di);
        $form->check();

        $page->add("question/crud/create", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Ny fråga",
        ]);
    }



    /**
     * Handler with form to delete an item.
     *
     * @return object as a response object
     */
    public function deleteAction() : object
    {
        $this->checkIfLoggedIn();

        $page = $this->di->get("page");
        $form = new DeleteQuestion($this->di);
        $form->check();

        $page->add("question/crud/delete", [
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
        $form = new UpdateQuestion($this->di, $id);
        $form->check();

        $page->add("question/crud/update", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Redigera fråga",
        ]);
    }



    /**
     * View one question
     *
     * @param int $id the id to view
     *
     * @return object as a response object
     */
    public function viewAction(int $id) : object
    {
        $page = $this->di->get("page");
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question = $question->findById($id);

        $activeUserId = $this->di->get("session")->get("userId");
        $isAuthor = $question->isAuthor($activeUserId);

        $question = $question->joinTable("User", "Question", "Question.user_id = User.id")[0];

        $page->add("question/crud/view", [
            "question" => $question,
            "isAuthor" => $isAuthor
        ]);

        return $page->render([
            "title" => "Se fråga",
        ]);
    }



    // /**
    // * Get information about if active user is author of question
    // *
    // * @param int $id question id
    // *
    // * @return bool True if logged in user is author, else false
    // */
    // public function isAuthor(int $id) : bool
    // {
    //     $activeUserId = $this->di->get("session")->get("userId");
    //
    //     $question = new Question();
    //     $question->setDb($this->di->get("dbqb"));
    //     $res = $question->findById($id);
    //
    //     if ($res->user_id == $activeUserId) {
    //         return true;
    //     }
    //
    //     return false;
    // }
}
