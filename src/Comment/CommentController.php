<?php

namespace Hepa19\Comment;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Hepa19\Comment\HTMLForm\CreateComment;
use Hepa19\Comment\HTMLForm\DeleteComment;
use Hepa19\Comment\HTMLForm\UpdateComment;
use Hepa19\Answer\Answer;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class CommentController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Show all items.
     *
     * @return object as a response object
     */
    public function indexActionGet(): object
    {
        $page = $this->di->get("page");
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));

        $page->add("comment/crud/view-all", [
            "items" => $comment->findAll(),
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
    public function createAction(): object
    {
        $page = $this->di->get("page");
        $userId = $this->di->get("session")->get("userId") ?? null;
        $postId = $this->di->get("request")->getGet("postId") ?? null;
        $questionId = $this->di->get("request")->getGet("questionId") ?? null;
        $type = $this->di->get("request")->getGet("type") ?? null;

        if ($userId && $postId && $questionId && $type) {
            $form = new CreateComment($this->di, $userId, $postId, $questionId, $type);
            $form->check();
        } else {
            return $this->di->get("response")->redirect("user/login");
        }

        $page->add("comment/crud/create", [
            "form" => $form->getHTML(),
            "questionId" => $questionId
        ]);

        return $page->render([
            "title" => "Ny kommentar",
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
        $page = $this->di->get("page");
        $questionId = $this->getQuestionId($id);
        $form = new DeleteComment($this->di, $id, $questionId);
        $form->check();

        $page->add("comment/crud/delete", [
            "form" => $form->getHTML(),
            "questionId" => $questionId
        ]);

        return $page->render([
            "title" => "Radera kommentar",
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
        $form = new UpdateComment($this->di, $id);
        $form->check();

        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comment->findById($id);
        $questionId = $this->getQuestionId($id);

        $page->add("comment/crud/update", [
            "form" => $form->getHTML(),
            "questionId" => $questionId
        ]);

        return $page->render([
            "title" => "Uppdatera kommentar",
        ]);
    }



    /**
     * Handler with form to delete an item.
     *
     * @param int $id the id of the comment
     *
     * @return string question id
     */
    public function getQuestionId($id)
    {
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comment->findById($id);

        if ($comment->type == "question") {
            return $comment->post_id;
        } else {
            $answer = new Answer();
            $answer->setDb($this->di->get("dbqb"));
            $answer->findById($comment->post_id);
            return $answer->question_id;
        }
    }
}
