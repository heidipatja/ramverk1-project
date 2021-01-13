<?php

namespace Hepa19\Tag;

use Hepa19\MyActiveRecord\MyActiveRecord;

/**
 * A database driven model using the Active Record design pattern.
 */
class Tag extends MyActiveRecord
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Tag";



    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $tag;



    /**
    * Get information on whether logged in user is author of question
    *
    * @param string $activeUserId The id of logged in user
    *
    * @return bool True if logged in user is author, else false
    */
    public function isTag($tag): bool
    {
        $res = $this->find("tag", $tag);

        if ($res) {
            return true;
        }

        return false;
    }
}
