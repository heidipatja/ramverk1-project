<?php

namespace Hepa19\Comment;

use Hepa19\MyActiveRecord\MyActiveRecord;

/**
 * A database driven model using the Active Record design pattern.
 */
class Comment extends MyActiveRecord
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
}
