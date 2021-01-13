<?php

namespace Hepa19\Vote;

use Hepa19\MyActiveRecord\MyActiveRecord;

/**
 * A database driven model using the Active Record design pattern.
 */
class Vote extends MyActiveRecord
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
    * Get a specific vote
    *
    * @return array Results
    */
    public function getVote($userId, $postId, $type): object
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
}
