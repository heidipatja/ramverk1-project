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
use Hepa19\Vote\HTMLForm\VoteForm;
use Hepa19\Vote\Vote;
use Hepa19\Vote\HTMLForm\AcceptForm;
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

        $questions = $question->joinWhere("Question.*, User.username, User.email", "Question", "User", "Question.user_id = User.id", "Question.deleted IS NULL", "created DESC");

        foreach ($questions as $question) {
            $question->content = $this->filter->markdown($question->content);
            $question->content = $this->filter->substring($question->content, 100);
            $voteSum = $this->getVoteSum($question->id, "question");
            $question->voteSum = $voteSum ?? 0;
            $question->answerCount = $this->getAnswerCount($question->id)[0]->answerCount;
            $question->tags = $this->getTags($question->id);
        }

        $page->add("question/crud/view-all", [
            "questions" => $questions
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
    public function getTags($questionId) : array
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

        $question = $question->joinWhere("*", "User", "Question", "Question.user_id = User.id", "Question.id = " . $id)[0];

        $question->content = $this->filter->markdown($question->content);

        $tags = $this->getTags($id);
        $comments = $this->getComments($id, $activeUserId);
        $answers = $this->getAnswers($id, $activeUserId);
        $answers = $this->getCommentsToAnswers($answers, $activeUserId);

        $answerForm = new CreateAnswer($this->di, $id, $activeUserId);
        $answerForm->check();

        $votes = $this->getVotes($id, "question");
        $voteSum = $this->getVoteSum($id, "question") ?? 0;

        $upvoteQ = new VoteForm($this->di, $id, $activeUserId, "question", "up");
        $upvoteQ->check();

        $downvoteQ = new VoteForm($this->di, $id, $activeUserId, "question", "down");
        $downvoteQ->check();

        $page->add("question/crud/view", [
            "question" => $question,
            "tags" => $tags,
            "comments" => $comments,
            "answers" => $answers,
            "activeUserId" => $activeUserId,
            "votes" => $votes,
            "upvoteQ" => $upvoteQ->getHTML(),
            "downvoteQ" => $downvoteQ->getHTML(),
            "voteSum" => $voteSum,
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
     * Get accepted status for question
     *
     * @return bool true if accepted, false if not
     */
    public function getAcceptedStatus($answerId) : bool
    {
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->findById($answerId);

        if ($answer->accepted == 1) {
            return true;
        }

        return false;
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
        $votes = $vote->getVotesForPost($postId, $type);

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
        $voteSum = $vote->getVoteSum($postId, $type);

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

            $voteSum = $this->getVoteSum($comment->id, "comment") ?? 0;

            $comment->upvote = $upvote->getHTML();
            $comment->downvote = $downvote->getHTML();
            $comment->voteSum = $voteSum;
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

                $voteSum = $this->getVoteSum($ansC->id, "comment") ?? 0;

                $ansC->upvote = $upvote->getHTML();
                $ansC->downvote = $downvote->getHTML();
                $ansC->voteSum = $voteSum;
            }
        }

        return $answers;
    }



    /**
     * Get answers for question
     *
     * @return object as a response object
     */
    public function getAnswers($id, $userId)
    {
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));

        $answers = $answer->join2Where("User", "Answer", "User.id = Answer.user_id", "Answer.question_id = " . $id, "Answer.deleted IS NULL");

        foreach ($answers as $answer) {
            $answer->content = $this->filter->markdown($answer->content);

            $upvote = new VoteForm($this->di, $answer->id, $userId, "answer", "up");
            $upvote->check();

            $downvote = new VoteForm($this->di, $answer->id, $userId, "answer", "down");
            $downvote->check();

            $voteSum = $this->getVoteSum($answer->id, "answer");

            $acceptForm = new AcceptForm($this->di, $answer->id, $answer->user_id, $answer->accepted);
            $acceptForm->check();

            $answer->upvote = $upvote->getHTML();
            $answer->downvote = $downvote->getHTML();
            $answer->voteSum = $voteSum ?? 0;
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
