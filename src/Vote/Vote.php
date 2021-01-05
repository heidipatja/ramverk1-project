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
}
