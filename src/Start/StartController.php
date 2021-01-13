<?php

namespace Hepa19\Start;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Hepa19\Question\Question;
use Hepa19\Answer\Answer;
use Hepa19\Comment\Comment;
use Hepa19\Vote\HTMLForm\VoteForm;
use Hepa19\Vote\Vote;
use Hepa19\User\User;
use Hepa19\MyTextFilter\MyTextFilter;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class StartController implements ContainerInjectableInterface
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

        $userId = $this->di->get("session")->get("userId");

        $questions = $question->join2leftWhere("Question", "(SELECT Vote.*, SUM(vote) AS 'votesum' FROM Vote GROUP BY Vote.post_id) AS v", "v.post_id = Question.id", "User", "Question.user_id = User.id", "Question.deleted IS NULL", "Question.created DESC", "Question.*, User.username, User.email, v.votesum", 3);

        $tags = $question->joinGroupOrder("TagToQuestion", "Tag", "TagToQuestion.tag_id = Tag.id", "tagcount DESC", "TagToQuestion.tag_id", "TagToQuestion.tag_id, count(tag_id) as tagcount, Tag.tag", 10);

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $users = $user->findAllOrderBy("score DESC", 5);

        foreach ($questions as $question) {
            $question->content = $this->filter->markdown($question->content);
            $question->content = $this->filter->substring($question->content, 120);

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

        $page->add("start/info");

        $page->add("start/questions", [
            "questions" => $questions,
        ]);

        $page->add("start/flash", [], "flash");

        $page->add("start/tags", [
            "tags" => $tags,
        ], "sidebar-right");

        $page->add("start/users", [
            "users" => $users
        ], "sidebar-right");

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
