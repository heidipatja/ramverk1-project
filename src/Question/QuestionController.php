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

/**
 * A sample controller to show how a controller class can be implemented.
 */
class QuestionController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

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
        $questions2users = $question->joinTable("User", "Question", "Question.user_id = User.id");


        $newquestion = new Question();
        $newquestion->setDb($this->di->get("dbqb"));

        $questions2tags = $newquestion->joinTwoTables("Question", "TagToQuestion", "Question.id = TagToQuestion.question_id", "Tag", "TagToQuestion.tag_id = Tag.id");

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

        $question = $question->joinTable("User", "Question", "Question.id =" . $id)[0];

        $questions2tags = $this->getTags();

        $answers = $this->getAnswers($id);
        $answerForm = new CreateAnswer($this->di, $id, $activeUserId);
        $answerForm->check();

        $page->add("question/crud/view", [
            "question" => $question,
            "isAuthor" => $isAuthor,
            "tags" => $questions2tags
        ]);

        $page->add("answer/crud/view-all", [
            "answers" => $answers
        ]);

        $page->add("answer/crud/create", [
            "form" => $answerForm->getHTML()
        ]);

        return $page->render([
            "title" => "Se fråga",
        ]);
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
        // $answers = $answer->findAllWhere("question_id = ?", $id);
        $answers = $answer->joinUser($id);
        return $answers;
    }
}
