<?php

namespace Hepa19\User;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Hepa19\User\HTMLForm\LoginUser;
use Hepa19\User\HTMLForm\CreateUser;
use Hepa19\User\HTMLForm\UpdateUser;
use Hepa19\Question\Question;
use Hepa19\Answer\Answer;
use Hepa19\Answer\HTMLForm\CreateAnswer;
use Hepa19\MyTextFilter\MyTextFilter;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class UserController implements ContainerInjectableInterface
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
     * Index page showing all users
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $page = $this->di->get("page");
        $user = new User();
        $user->setDb($this->di->get("dbqb"));

        $page->add("user/crud/view-all", [
            "users" => $user->findAll(),
        ]);

        return $page->render([
            "title" => "Användare",
        ]);
    }



    /**
     * View one user
     *
     * @param string $username the username of the user to view
     *
     * @return object as a response object
     */
    public function viewAction(string $username) : object
    {
        $page = $this->di->get("page");
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user = $user->find("username", $username);

        $activeUser = $this->di->get("session")->get("userId");

        $question = new Question();
        $question->setDb($this->di->get("dbqb"));

        $questions = $question->join2Where("User", "Question", "Question.user_id = User.id", "User.id = " . $user->id, "Question.deleted IS NULL");

        $answers = $question->join2where3("User", "Answer", "Answer.user_id = User.id", "Question", "Question.id = Answer.question_id", "User.id = " . $user->id, "Answer.deleted IS NULL", "Question.deleted IS NULL");

        foreach ($questions as $question) {
            $question->content = $this->filter->markdown($question->content);
            $question->content = $this->filter->substring($question->content, 100);
            $question->tags = $this->getTags($question->id);
        }

        $page->add("user/crud/view", [
            "user" => $user,
            "questions" => $questions,
            "answers" => $answers
        ]);

        $page->add("user/crud/sidebar", [
            "activeUser" => $activeUser
        ], "sidebar-right");

        $form = new LoginUser($this->di);
        $form->check();

        if (!$activeUser) {
            $page->add("anax/v2/article/default", [
                "activeUser" => $activeUser,
                "content" => $form->getHTML(),
            ], "sidebar-right");
        }

        return $page->render([
            "title" => "Se fråga",
        ]);
    }



    /**
     * Get tags related to question
     *
     * @return object as a response object
     */
    public function getTags($questionId) : array
    {
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));

        $tags = $question->join2Where2("Question", "TagToQuestion", "Question.id = TagToQuestion.question_id", "Tag", "TagToQuestion.tag_id = Tag.id", "Question.deleted IS NULL", "Question.id = " . $questionId);

        return $tags;
    }



    /**
     * Get all answers to question based on question id
     *
     * @param int $id the id of question
     *
     * @return array $answers
     */
    public function getAnswers(int $id) : array
    {
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answers = $answer->joinWhere("User", "Answer", "Answer.user_id = User.id", "Answer.question_id = " . $id);
        return $answers;
    }



    /**
     * Login page
     *
     * @return object as a response object
     */
    public function loginAction() : object
    {
        $page = $this->di->get("page");
        $form = new LoginUser($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        $page->add("user/login");

        return $page->render([
            "title" => "Logga in",
        ]);
    }



    /**
     * Logout route
     * Clear user information from session and redirect
     *
     * @return object as a response object
     */
    public function logoutAction() : object
    {
        $session = $this->di->get("session");
        $session->delete("username");
        $session->delete("userId");

        return $this->di->get("response")->redirect("user/login")->send();
    }



    /**
     * Create new user account
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        $page = $this->di->get("page");
        $form = new CreateUser($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Ny användare",
        ]);
    }



    /**
     * Edit user
     *
     * @param integer $id get details on user with id.
     *
     * @return object as a response objectf
     */
    public function updateAction($id) : object
    {
        $page = $this->di->get("page");
        $form = new UpdateUser($this->di, $id);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Redigera användare",
        ]);
    }



    /**
     * Profile page
     *
     * @return object as a response object
     */
    public function profileAction() : object
    {
        $page = $this->di->get("page");
        $session = $this->di->get("session");

        $user = new User();
        $username = $session->get("username") ?? null;

        if (!$username) {
            return $this->di->get("response")->redirect("user/login")->send();
        }

        $user->setDb($this->di->get("dbqb"));
        $user = $user->find("username", $username);
        $user->presentation = $this->filter->markdown($user->presentation);
        $gravatar = $user->getGravatar($user->email);

        $page->add("user/profile", [
            "user" => $user,
            "gravatar" => $gravatar
        ]);

        return $page->render([
            "title" => "Profil",
        ]);
    }
}
