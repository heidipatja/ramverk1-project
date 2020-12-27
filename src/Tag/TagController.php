<?php

namespace Hepa19\Tag;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Hepa19\Tag\HTMLForm\CreateForm;
use Hepa19\Tag\HTMLForm\EditForm;
use Hepa19\Tag\HTMLForm\DeleteForm;
use Hepa19\Tag\HTMLForm\UpdateForm;
use Hepa19\Question\Question;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class TagController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * View all tags
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $page = $this->di->get("page");
        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));

        $page->add("tag/crud/view-all", [
            "tags" => $tag->findAll(),
        ]);

        return $page->render([
            "title" => "Taggar",
        ]);
    }



    /**
     * View one tag
     *
     * @param string $tag id of the tag to view
     *
     * @return object as a response object
     */
    public function viewActionGet(string $tagString) : object
    {
        $page = $this->di->get("page");
        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));
        $tag = $tag->find("tag", $tagString);

        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $questions = $question->joinThreeTables("Question", "TagToQuestion", "TagToQuestion.question_id = Question.id", "Tag", "Tag.id = TagToQuestion.tag_id", "User", "User.id = Question.user_id", "Tag.tag = '". $tagString . "'");

        $tags = $this->getTags();

        $page->add("tag/crud/view", [
            "tag" => $tagString,
            "questions" => $questions,
            "tags" => $tags
        ]);

        return $page->render([
            "title" => "Frågor om " . $tag->tag,
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
}
