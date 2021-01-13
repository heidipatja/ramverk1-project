<?php

namespace Hepa19\Question;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Hepa19\Question\HTMLForm\CreateQuestion;
use Hepa19\Question\HTMLForm\EditQuestion;
use Hepa19\Question\HTMLForm\DeleteQuestion;
use Hepa19\Question\HTMLForm\UpdateQuestion;
use Hepa19\Question\HTMLForm\OrderQuestion;
use Hepa19\Answer\Answer;
use Hepa19\Answer\HTMLForm\CreateAnswer;
use Hepa19\Comment\Comment;
use Hepa19\Comment\HTMLForm\CreateComment;
use Hepa19\Vote\HTMLForm\VoteForm;
use Hepa19\Vote\Vote;
use Hepa19\Vote\HTMLForm\AcceptForm;
use Hepa19\User\HTMLForm\LoginUser;
use Hepa19\MyTextFilter\MyTextFilter;

/**
 * A sample controller to show how a controller class can be implemented.
 * @SuppressWarnings(PHPMD)
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
    public function indexAction(): object
    {
        $page = $this->di->get("page");
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));

        $orderBy = $this->di->get("request")->getGet("orderby") ?? "created DESC";

        $userId = $this->di->get("session")->get("userId");

        $questions = $question->join2leftWhere("Question", "(SELECT Vote.*, SUM(vote) AS 'votesum' FROM Vote WHERE Vote.type = 'question' GROUP BY Vote.post_id) AS v", "v.post_id = Question.id", "User", "Question.user_id = User.id", "Question.deleted IS NULL", $orderBy, "Question.*, User.username, User.email, v.votesum");

        $orderForm = new OrderQuestion($this->di);

        foreach ($questions as $question) {
            $question->content = $this->filter->markdown($question->content);
            $question->content = $this->filter->substring($question->content, 100);
            // $voteSum = $this->getVoteSum($question->id, "question");
            $question->votesum = $question->votesum ?? 0;
            $question->answerCount = $this->getAnswerCount($question->id)[0]->answerCount;
            $question->tags = $this->getTags($question->id);

            $upvote = new VoteForm($this->di, $question->id, $userId, "question", "up");
            $upvote->check();

            $downvote = new VoteForm($this->di, $question->id, $userId, "question", "down");
            $downvote->check();

            $question->upvote = $upvote->getHTML();
            $question->downvote = $downvote->getHTML();
        }

        $page->add("question/crud/view-all", [
            "questions" => $questions,
            "orderForm" => $orderForm->getHTML()
        ]);

        $page->add("start/flash", [], "flash");

        return $page->render([
            "title" => "Frågor",
        ]);
    }



    /**
     * Handler with form to create a new item.
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
     * Handler with form to create a new item.
     *
     * @return object as a response object
     */
    public function createAction(): object
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
    public function deleteAction($id): object
    {
        $this->checkIfLoggedIn();

        $page = $this->di->get("page");
        $form = new DeleteQuestion($this->di, $id);
        $form->check();

        $page->add("question/crud/delete", [
            "form" => $form->getHTML(),
            "id" => $id
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
    public function updateAction(int $id): object
    {
        $page = $this->di->get("page");
        $form = new UpdateQuestion($this->di, $id);
        $form->check();

        $page->add("question/crud/update", [
            "form" => $form->getHTML(),
            "id" => $id
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
    public function viewAction(int $id): object
    {
        $page = $this->di->get("page");
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question = $question->findById($id);

        if ($question->deleted) {
            return $this->di->response->redirect("question");
        }

        $activeUserId = $this->di->get("session")->get("userId") ?? null;

        $question = $question->joinWhere("*", "User", "Question", "Question.user_id = User.id", "Question.id = " . $id)[0];

        $question->content = $this->filter->markdown($question->content);

        $orderBy = $this->di->get("request")->getGet("orderby") ?? "created asc";
        $orderForm = new OrderQuestion($this->di);

        $tags = $this->getTags($id);
        $comments = $this->getComments($id, $activeUserId);
        $answers = $this->getAnswers($id, $activeUserId, $orderBy);
        $answers = $this->getCommentsToAnswers($answers, $activeUserId);
        $voteSum = $this->getVoteSum($id, "question") ?? 0;

        $upvoteQ = new VoteForm($this->di, $id, $activeUserId, "question", "up");
        $upvoteQ->check();

        $downvoteQ = new VoteForm($this->di, $id, $activeUserId, "question", "down");
        $downvoteQ->check();

        $loginForm = new LoginUser($this->di);
        $loginForm->check();

        $page->add("question/crud/view", [
            "question" => $question,
            "tags" => $tags,
            "comments" => $comments,
            "answers" => $answers,
            "activeUserId" => $activeUserId,
            "upvoteQ" => $upvoteQ->getHTML(),
            "downvoteQ" => $downvoteQ->getHTML(),
            "voteSum" => $voteSum,
            "orderForm" => $orderForm->getHTML()
        ]);

        $page->add("user/crud/sidebar", [
            "activeUser" => $activeUserId,
            "form" => $loginForm->getHTML()
        ], "sidebar-right");

        $page->add("start/flash", [], "flash");

        return $page->render([
            "title" => "Se fråga",
        ]);
    }



    /**
     * Get comments for question
     *
     * @return object as a response object
     */
    public function getVotes($postId, $type)
    {
        $vote = new Vote();
        $vote->setDb($this->di->get("dbqb"));
        $votes = $vote->where2("Vote.post_id = " . $postId, "Vote.type = '{$type}'");

        return $votes;
    }



    /**
     * Get comments for question
     *
     * @return object as a response object
     */
    public function getVoteSum($postId, $type)
    {
        $vote = new Vote();
        $vote->setDb($this->di->get("dbqb"));
        $voteSum = $vote->where2("Vote.post_id = " . $postId, "Vote.type = '{$type}'", 'SUM("Vote") AS "Sum"');

        return $voteSum[0]->Sum;
    }



    /**
     * Get comments for question
     *
     * @return object as a response object
     */
    public function getComments($id, $userId)
    {
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));

        $comments = $comment->joinWhere3("User", "Comment", "Comment.user_id = User.id", "Comment.post_id = " . $id, "Comment.type = 'question'", "Comment.deleted IS NULL");

        foreach ($comments as $comment) {
            $comment->content = $this->filter->markdown($comment->content);

            $upvote = new VoteForm($this->di, $comment->id, $userId, "comment", "up");
            $upvote->check();

            $downvote = new VoteForm($this->di, $comment->id, $userId, "comment", "down");
            $downvote->check();

            $votesum = $this->getVoteSum($comment->id, "comment") ?? 0;

            $comment->upvote = $upvote->getHTML();
            $comment->downvote = $downvote->getHTML();
            $comment->voteSum = $votesum;
        }

        return $comments;
    }



    /**
     * Get comments for answers
     *
     * @return object as a response object
     */
    public function getCommentsToAnswers($answers, $userId)
    {
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));

        foreach ($answers as $answer) {
            $answer->answerComments = $comment->joinWhere3("User", "Comment", "Comment.user_id = User.id", "Comment.post_id = " . $answer->id, "Comment.type = 'answer'", "Comment.deleted IS NULL");

            foreach ($answer->answerComments as $ansC) {
                $ansC->content = $this->filter->markdown($ansC->content);

                $upvote = new VoteForm($this->di, $ansC->id, $userId, "comment", "up");
                $upvote->check();

                $downvote = new VoteForm($this->di, $userId, $ansC->user_id, "comment", "down");
                $downvote->check();

                $votesum = $this->getVoteSum($ansC->id, "comment") ?? 0;

                $ansC->upvote = $upvote->getHTML();
                $ansC->downvote = $downvote->getHTML();
                $ansC->votesum = $votesum;
            }
        }

        return $answers;
    }



    /**
     * Get answers for question
     *
     * @return object as a response object
     */
    public function getAnswers($id, $userId, $orderBy)
    {
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));

        $answers = $answer->join2leftWhere2("Answer", "(SELECT Vote.*, SUM(vote) AS 'votesum' FROM Vote WHERE Vote.type = 'answer' GROUP BY Vote.post_id) AS v", "v.post_id = Answer.id", "User", "User.id = Answer.user_id", "Answer.question_id = " . $id, "Answer.deleted IS NULL", $orderBy, "Answer.*, User.username, User.email, v.votesum");

        foreach ($answers as $answer) {
            $answer->content = $this->filter->markdown($answer->content);

            $answer->votesum = $answer->votesum ?? 0;

            $upvote = new VoteForm($this->di, $answer->id, $userId, "answer", "up");
            $upvote->check();

            $downvote = new VoteForm($this->di, $answer->id, $userId, "answer", "down");
            $downvote->check();

            $acceptForm = new AcceptForm($this->di, $answer->id, $answer->user_id, $answer->accepted);
            $acceptForm->check();

            $answer->upvote = $upvote->getHTML();
            $answer->downvote = $downvote->getHTML();
            $answer->acceptForm = $acceptForm->getHTML();
        }

        return $answers;
    }



    /**
     * Get answers for question
     *
     * @return object as a response object
     */
    public function getAnswerCount($id)
    {
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));

        $answerCount = $answer->where("Answer.question_id = " . $id, 'COUNT("Answer") AS "answerCount"') ?? 0;

        return $answerCount;
    }
}
