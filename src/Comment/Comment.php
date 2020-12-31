<?php

namespace Hepa19\Comment;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Comment extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Comment";



    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $content;
    public $user_id;
    public $post_id;
    public $type;



    /**
    * Join with another db table
    *
    * @return array Results
    */
    public function getCommentsToAnswers($postId) : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from("User")
                        ->join("Comment", "Comment.user_id = User.id")
                        ->where("Comment.post_id = " . $postId)
                        ->andWhere("Comment.type = 'answer'")
                        ->andWhere("Comment.deleted IS NULL")
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }



    /**
    * Join with another db table
    *
    * @return array Results
    */
    public function getCommentsToQuestion($postId) : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from("User")
                        ->join("Comment", "Comment.user_id = User.id")
                        ->where("Comment.post_id = " . $postId)
                        ->andWhere("Comment.type = 'question'")
                        ->andWhere("Comment.deleted IS NULL")
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }
}
