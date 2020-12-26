<?php

namespace Hepa19\Answer;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Answer extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Answer";



    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $content;
    public $user_id;
    public $question_id;



    /**
    * Join with another db table
    *
    * @return array Results
    */
    public function joinUser($id) : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from("User")
                        ->join("Answer", "Answer.user_id = User.id")
                        ->where("Answer.question_id = " . $id)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }
}
