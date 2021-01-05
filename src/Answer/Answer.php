<?php

namespace Hepa19\Answer;

use Hepa19\MyActiveRecord\MyActiveRecord;

/**
 * A database driven model using the Active Record design pattern.
 */
class Answer extends MyActiveRecord
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
    public $accepted;
}
