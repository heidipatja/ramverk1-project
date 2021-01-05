<?php

namespace Hepa19\Vote;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Vote extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Vote";



    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $user_id;
    public $post_id;
    public $type;
    public $vote;



    /**
    * Get all votes for a specific post
    *
    * @return array Results
    */
    public function getVotesForPost($postId, $type) : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from($this->tableName)
                        ->where("Vote.post_id = " . $postId)
                        ->andWhere("Vote.type = '{$type}'")
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }



    /**
    * Get all votes for a specific post
    *
    * @return array Results
    */
    public function getVote($userId, $postId, $type) : object
    {
        $this->checkDb();
        $this->db->connect()
                 ->select()
                 ->from($this ->tableName)
                 ->where("Vote.post_id = " . $postId)
                 ->andWhere("Vote.type = '{$type}'")
                 ->andWhere("Vote.user_id = " . $userId)
                 ->execute()
                 ->fetchInto($this);
        return $this;
    }



    /**
    * Get all votes for a specific post
    *
    * @return array Results
    */
    public function getVoteSum($postId, $type) : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select('SUM("Vote") AS "Sum"')
                        ->from($this->tableName)
                        ->where("Vote.post_id = " . $postId)
                        ->andWhere("Vote.type = '{$type}'")
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }
}
