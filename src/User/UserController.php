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
use Hepa19\Vote\Vote;
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
    public function indexAction(): object
    {
        $page = $this->di->get("page");
        $user = new User();
        $user->setDb($this->di->get("dbqb"));

        $activeUserId = $this->di->get("session")->get("userId") ?? null;
        $loginForm = new LoginUser($this->di);
        $loginForm->check();

        $page->add("user/crud/view-all", [
            "users" => $user->findAll(),
        ]);

        $page->add("user/crud/sidebar-user", [
            "activeUser" => $activeUserId,
            "form" => $loginForm->getHTML()
        ], "sidebar-right");

        $page->add("start/flash", [], "flash");

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
    public function viewAction(string $username): object
    {
        $page = $this->di->get("page");
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user = $user->find("username", $username);
        $user->presentation = $this->filter->markdown($user->presentation);

        $activeUser = $this->di->get("session")->get("userId");

        $question = new Question();
        $question->setDb($this->di->get("dbqb"));

        $questions = $question->join2Where("User", "Question", "Question.user_id = User.id", "User.id = " . $user->id, "Question.deleted IS NULL");

        $answers = $question->join2Where("Answer", "User", "Answer.user_id = User.id", "Answer.deleted IS NULL", "User.id = " . $user->id, "created desc", "Answer.*, User.username, User.email");

        $loginForm = new LoginUser($this->di);
        $loginForm->check();

        $comments = $question->join3leftWhere2(
            "Comment",
            "User",
            "Comment.user_id = User.id",
            "(SELECT Answer.question_id FROM Answer GROUP BY Answer.question_id) AS a",
            "a.question_id = Comment.post_id",
            "(SELECT Question.id FROM Question GROUP BY Question.id) AS q",
            "q.id = Comment.post_id",
            "User.id = " . $user->id,
            "Comment.deleted IS NULL",
            "Comment.*, User.username, User.email, a.question_id as 'answer_id', q.id as 'question_id'"
        );

        $vote = new Vote();
        $vote->setDb($this->di->get("dbqb"));

        $votes = $vote->where("Vote.user_id = " . $user->id);

        foreach ($questions as $question) {
            $question->content = $this->filter->markdown($question->content);
            $question->content = $this->filter->substring($question->content, 100);
            $question->tags = $this->getTags($question->id);
        }

        $page->add("user/crud/view", [
            "user" => $user,
            "questions" => $questions,
            "answers" => $answers,
            "comments" => $comments,
            "votes" => count($votes),
            "activeUser" => $activeUser
        ]);

        $page->add("user/crud/sidebar2", [
            "activeUser" => $activeUser,
            "form" => $loginForm->getHTML()
        ], "sidebar-right");

        $page->add("start/flash", [], "flash");

        return $page->render([
            "title" => "Profil",
        ]);
    }



    /**
     * Get tags related to question
     *
     * @return object as a response object
     */
    public function getTags($questionId): array
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
    public function getAnswers(int $id): array
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
    public function loginAction(): object
    {
        $page = $this->di->get("page");
        $form = new LoginUser($this->di);
        $form->check();

        $page->add("user/crud/login", [
            "form" => $form->getHTML(),
        ]);

        $page->add("start/flash", [], "flash");

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
    public function logoutAction(): object
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
    public function createAction(): object
    {
        $page = $this->di->get("page");
        $form = new CreateUser($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        $page->add("start/flash", [], "flash");

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
    public function updateAction($id): object
    {
        $page = $this->di->get("page");
        $form = new UpdateUser($this->di, $id);
        $form->check();

        $page->add("user/crud/update", [
            "form" => $form->getHTML(),
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
    public function profileAction(): object
    {
        $page = $this->di->get("page");
        $activeUser = $this->di->get("session")->get("userId") ?? null;

        if (!$activeUser) {
            return $this->di->get("response")->redirect("user/login")->send();
        }

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user = $user->findById($activeUser);
        $user->presentation = $this->filter->markdown($user->presentation);

        $question = new Question();
        $question->setDb($this->di->get("dbqb"));

        $questions = $question->join2Where("User", "Question", "Question.user_id = User.id", "User.id = " . $user->id, "Question.deleted IS NULL");

        $answers = $question->join2Where("Answer", "User", "Answer.user_id = User.id", "Answer.deleted IS NULL", "User.id = " . $user->id, "created desc", "Answer.*, User.username, User.email");

        $comments = $question->join3leftWhere2(
            "Comment",
            "User",
            "Comment.user_id = User.id",
            "(SELECT Answer.question_id FROM Answer GROUP BY Answer.question_id) AS a",
            "a.question_id = Comment.post_id",
            "(SELECT Question.id FROM Question GROUP BY Question.id) AS q",
            "q.id = Comment.post_id",
            "User.id = " . $user->id,
            "Comment.deleted IS NULL",
            "Comment.*, User.username, User.email, a.question_id as 'answer_id', q.id as 'question_id'"
        );

        $vote = new Vote();
        $vote->setDb($this->di->get("dbqb"));

        $votes = $vote->where("Vote.user_id = " . $user->id);

        foreach ($questions as $question) {
            $question->content = $this->filter->markdown($question->content);
            $question->content = $this->filter->substring($question->content, 100);
            $question->tags = $this->getTags($question->id);
        }

        $page->add("user/crud/view", [
            "user" => $user,
            "questions" => $questions,
            "answers" => $answers,
            "comments" => $comments,
            "votes" => count($votes),
            "activeUser" => $activeUser
        ]);

        $page->add("user/crud/sidebar3", [
            "activeUser" => $activeUser
        ], "sidebar-right");

        $page->add("start/flash", [], "flash");

        return $page->render([
            "title" => "Profil",
        ]);
    }
}
