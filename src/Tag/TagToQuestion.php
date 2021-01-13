<?php

namespace Hepa19\Tag;

use Hepa19\MyActiveRecord\MyActiveRecord;

/**
 * A database driven model using the Active Record design pattern.
 */
class TagToQuestion extends MyActiveRecord
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
}
