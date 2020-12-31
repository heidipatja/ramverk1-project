<?php

namespace Hepa19\Question\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Hepa19\Question\Question;
use Hepa19\Tag\Tag;
use Hepa19\Tag\TagToQuestion;

/**
 * Form to update an item.
 */
class UpdateQuestion extends FormModel
{
    /**
     * Constructor injects with DI container and the id to update.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     * @param integer             $id to update
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $question = $this->getQuestion($id);
        $question->tags = $this->getTags($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Redigera fråga",
                "escape-values" => false
            ],
            [
                "id" => [
                    "type" => "hidden",
                    "validation" => ["not_empty"],
                    "readonly" => true,
                    "value" => $question->id,
                ],

                "title" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "value" => $question->title,
                    "label" => "Ämne"
                ],

                "content" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "value" => $question->content,
                    "label" => "Innehåll"
                ],

                "tags" => [
                    "type" => "text",
                    "value" => $question->tags,
                    "label" => "Taggar"
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Spara",
                    "callback" => [$this, "callbackSubmit"],
                ],

                "reset" => [
                    "type"      => "reset",
                    "value" => "Återställ"
                ],
            ]
        );
    }



    /**
     * Get question info
     *
     * @param integer $id get details on question with id.
     *
     * @return Question
     */
    public function getQuestion($id) : object
    {
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question->find("id", $id);
        return $question;
    }



    /**
     * Get details on question
     *
     * @param integer $id get details on question with id.
     *
     * @return $tagString String with tags
     */
    public function getTags($id) : string
    {
        $tags = new TagToQuestion();
        $tags->setDb($this->di->get("dbqb"));
        $tags = $tags->find("question_id", $id);
        $tags = $tags->getTagNames();

        $tagString = "";

        if ($tags) {
            foreach ($tags as $tag) {
                if ($tag->question_id == $id) {
                    $tagString = $tagString . $tag->tag . " ";
                }
            }
            $tagString = substr($tagString, 0, -1);
        }
        return $tagString;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $title = $this->form->value("title");
        $content = $this->form->value("content");
        $id = $this->form->value("id");
        $tags = $this->form->value("tags");

        if (!$title) {
           $this->form->rememberValues();
           $this->form->addOutput("Frågan måste ha en ämnesrad.");
           return false;
        }

        if (!$content) {
           $this->form->rememberValues();
           $this->form->addOutput("Frågan måste ha en beskrivning.");
           return false;
        }

        if ($tags) {
            $this->removeTags($tags, $id);
            $this->saveTags($tags, $id);
        }

        $question = $this->getQuestion($id);
        $question->title = $title;
        $question->content = $content;
        $question->updated = date("Y-m-d H:i:s");
        $question->save();
        return true;
    }



    /**
     * Save Tags
     *
     * @param string $tags String with tags separated by " "
     * @param integer $id question id
     */
    public function saveTags($tags, $id)
    {
        $tags = explode(" ", $tags);
        foreach (array_unique($tags) as $uniqueTag) {
            $tag = new Tag();
            $tag->setDb($this->di->get("dbqb"));
            if ($tag->isTag($uniqueTag)) {
                $tag->tag = $uniqueTag;
                $tag->save();
            }
            $t2q = new TagToQuestion();
            $t2q->setDb($this->di->get("dbqb"));
            $t2q->tag_id = $tag->id;
            $t2q->question_id = $id;
            $t2q->save();

        }
    }




    /**
     * Remove tags
     *
     * @param string $tags String with tags separated by " "
     * @param integer $id question id
     */
    public function removeTags($tags, $id)
    {
        $tags = explode(" ", $tags);
        foreach (array_unique($tags) as $uniqueTag) {
            $t2q = new TagToQuestion();
            $t2q->setDb($this->di->get("dbqb"));
            $t2q->find("question_id", $id);
            $t2q->delete();
        }
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("question")->send();
    }



    /**
     * Callback what to do if the form was unsuccessfully submitted, this
     * happen when the submit callback method returns false or if validation
     * fails. This method can/should be implemented by the subclass for a
     * different behaviour.
     */
    public function callbackFail()
    {
        $this->di->get("response")->redirectSelf()->send();
    }
}
