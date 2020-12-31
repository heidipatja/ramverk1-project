<?php

namespace Hepa19\Question;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Question extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Question";


    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $title;
    public $content;
    public $user_id;




    /**
    * Get information on whether logged in user is author of question
    *
    * @param string $activeUserId The id of logged in user
    *
    * @return bool True if logged in user is author, else false
    */
    public function isAuthor($activeUserId) : bool
    {
        return $this->user_id == $activeUserId;
    }



    /**
    * Join with another db table
    *
    * @return array Results
    */
    public function joinTable($fromTable, $withTable, $condition) : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from($fromTable)
                        ->join($withTable, $condition)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }



    /**
    * Join with another db table
    *
    * @return array Results
    */
    public function joinTableWhere($fromTable, $withTable, $condition, $where) : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from($fromTable)
                        ->join($withTable, $condition)
                        ->where($where)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }



    /**
    * Join with another db table
    *
    * @return array Results
    */
    public function joinTableWhere2($fromTable, $withTable, $condition, $where, $where2) : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from($fromTable)
                        ->join($withTable, $condition)
                        ->where($where)
                        ->andWhere($where2)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }



    /**
    * Join with another db table
    *
    * @return array Results
    */
    public function joinTableWhere3($fromTable, $withTable, $condition, $withTable2, $condition2, $where, $where2, $where3) : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from($fromTable)
                        ->join($withTable, $condition)
                        ->join($withTable2, $condition2)
                        ->where($where)
                        ->andWhere($where2)
                        ->andWhere($where3)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }




    /**
    * Join with another db table
    *
    * @return array Results
    */
    public function joinJoinWhere($fromTable, $withTable, $condition, $withTable2, $condition2, $where) : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from($fromTable)
                        ->join($withTable, $condition)
                        ->join($withTable2, $condition2)
                        ->where($where)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }



    /**
    * Join with two tables
    *
    * @return array Results
    */
    public function joinTwoTables($fromTable, $withTable, $condition, $withTable2, $condition2) : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from($fromTable)
                        ->join($withTable, $condition)
                        ->join($withTable2, $condition2)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }



    /**
    * Join with two tables
    *
    * @return array Results
    */
    public function joinTwoTablesWhere($fromTable, $withTable, $condition, $withTable2, $condition2, $where, $where2) : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from($fromTable)
                        ->join($withTable, $condition)
                        ->join($withTable2, $condition2)
                        ->where($where)
                        ->andWhere($where2)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }



    /**
    * Join with three tables
    *
    * @return array Results
    */
    public function joinThreeTables($fromTable, $withTable, $condition, $withTable2, $condition2, $withTable3, $condition3, $where) : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from($fromTable)
                        ->join($withTable, $condition)
                        ->join($withTable2, $condition2)
                        ->join($withTable3, $condition3)
                        ->where($where)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }


    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $size Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $def Default imageset to use [ 404 | mp | identicon | monsterid | wavatar ]
     * @param string $rating Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source https://gravatar.com/site/implement/images/php/
     */
    public function getGravatar($email, $size = 160, $def = 'mp', $rating = 'g', $img = false, $atts = array())
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$size&d=$def&r=$rating";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }
        return $url;
    }
}
