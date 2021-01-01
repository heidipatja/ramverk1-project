<?php

namespace Hepa19\Question;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Hepa19\Question\HTMLForm\CreateQuestion;
use Hepa19\Question\HTMLForm\EditQuestion;
use Hepa19\Question\HTMLForm\DeleteQuestion;
use Hepa19\Question\HTMLForm\UpdateQuestion;
use Hepa19\Answer\Answer;
use Hepa19\Answer\HTMLForm\CreateAnswer;
use Hepa19\Comment\Comment;
use Hepa19\Comment\HTMLForm\CreateComment;
use Hepa19\MyTextFilter\MyTextFilter;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class QuestionController implements ContainerInjectableInterface
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

        $questions = $question->findAll();
        $questions2users = $question->joinTableWhere("User", "Question", "Question.user_id = User.id", "Question.deleted IS NULL");

        foreach ($questions2users as $question) {
            $question->content = $this->filter->markdown($question->content);
            $question->content = $this->filter->substring($question->content, 100);
        }

        $newquestion = new Question();
        $newquestion->setDb($this->di->get("dbqb"));

        // $questions2tags = $newquestion->joinTwoTables("Question", "TagToQuestion", "Question.id = TagToQuestion.question_id", "Tag", "TagToQuestion.tag_id = Tag.id");

        $questions2tags = $newquestion->joinJoinWhere("Question", "TagToQuestion", "Question.id = TagToQuestion.question_id", "Tag", "TagToQuestion.tag_id = Tag.id", "Question.deleted IS NULL");

        $page->add("question/crud/view-all", [
            "questions" => $questions2users,
            "tags" => $questions2tags
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
     * @param int $id the id to delete
     *
     * @return object as a response object
     */
    public function deleteAction($id) : object
    {
        $this->checkIfLoggedIn();

        $page = $this->di->get("page");
        $form = new DeleteQuestion($this->di, $id);
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

        if ($question->deleted) {
            return $this->di->response->redirect("question");
        }

        $activeUserId = $this->di->get("session")->get("userId");
        $isAuthor = $question->isAuthor($activeUserId);

        $question = $question->joinTableWhere("User", "Question", "Question.user_id = User.id", "Question.id = " . $id)[0];

        $question->content = $this->filter->markdown($question->content);

        $tags = $this->getTags();

        $comments = $this->getComments($id);

        $answers = $this->getAnswers($id);

        $answers = $this->getCommentsToAnswers($answers);

        $answerForm = new CreateAnswer($this->di, $id, $activeUserId);
        $answerForm->check();

        $page->add("question/crud/view", [
            "question" => $question,
            "tags" => $tags,
            "comments" => $comments,
            "answers" => $answers,
            "activeUserId" => $activeUserId
        ]);

        $page->add("answer/crud/create", [
            "form" => $answerForm->getHTML()
        ]);

        $page->add("user/crud/sidebar", [
            "activeUser" => $activeUserId
        ], "sidebar-right");

        return $page->render([
            "title" => "Se fråga",
        ]);
    }



    /**
     * Get comments for question
     *
     * @return object as a response object
     */
    public function getComments($id)
    {
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));

        $comments = $comment->getCommentsToQuestion($id);

        foreach ($comments as $comment) {
            $comment->content = $this->filter->markdown($comment->content);
        }

        return $comments;
    }



    /**
     * Get comments for answers
     *
     * @return object as a response object
     */
    public function getCommentsToAnswers($answers)
    {
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));

        foreach ($answers as $answer) {
            $answer->answerComments = $comment->getCommentsToAnswers($answer->id);
        }

        return $answers;
    }



    /**
     * Get tags for question
     *
     * @return object as a response object
     */
    public function getTags()
    {
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));

        $questions2tags = $question->joinTwoTables("Question", "TagToQuestion", "Question.id = TagToQuestion.question_id", "Tag", "TagToQuestion.tag_id = Tag.id");

        return $questions2tags;
    }



    /**
     * Get answers for question
     *
     * @return object as a response object
     */
    public function getAnswers($id)
    {
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));

        $answers = $answer->getAnswers($id);

        foreach ($answers as $answer) {
            $answer->content = $this->filter->markdown($answer->content);
        }

        return $answers;
    }
}
