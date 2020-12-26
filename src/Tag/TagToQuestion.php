<?php

namespace Hepa19\Tag;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class TagToQuestion extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "TagToQuestion";



    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $tag_id;
    public $question_id;



    /**
    * Join with Question and Tag tables to get tag names
    *
    * @return array Results
    */
    public function getTagNames() : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from($this->tableName)
                        ->join("Question", "TagToQuestion.question_id = Question.id")
                        ->join("Tag", "TagToQuestion.tag_id = Tag.id")
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }
}
