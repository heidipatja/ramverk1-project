<?php

namespace Hepa19\MyActiveRecord;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class MyActiveRecord extends ActiveRecordModel
{
    /**
     * Find and return all, ordered
     *
     * @return array of object of this class
     */
    public function findAllOrderBy($orderBy)
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from($this->tableName)
                        ->orderBy($orderBy)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }



    /**
    * Get all votes for a specific post
    *
    * @return array Results
    */
    public function where($where, $select = "*") : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select($select)
                        ->from($this->tableName)
                        ->where($where)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }



    /**
    * Get all votes for a specific post
    *
    * @return array Results
    */
    public function where2($where, $where2, $select = "*") : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select($select)
                        ->from($this->tableName)
                        ->where($where)
                        ->andWhere($where2)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }



    /**
    * Get all votes for a specific post
    *
    * @return array Results
    */
    public function where3($where, $where2, $where3, $select = "*") : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select($select)
                        ->from($this->tableName)
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
    public function joinWhere($select, $fromTable, $withTable, $condition, $where, $orderBy = "created DESC") : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select($select)
                        ->from($fromTable)
                        ->orderBy($orderBy)
                        ->join($withTable, $condition)
                        ->where($where)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }



    /**
    * Join with Question and Tag tables to get tag names
    *
    * @return array Results
    */
    public function join2($join, $join2, $condition, $condition2) : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from($this->tableName)
                        ->join($join, $condition)
                        ->join($join2, $condition2)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }



    /**
    * Join with another db table
    *
    * @return array Results
    */
    public function join2Where($fromTable, $withTable, $condition, $where, $where2, $orderBy = "created desc", $selected = "*") : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select($selected)
                        ->from($fromTable)
                        ->orderBy($orderBy)
                        ->join($withTable, $condition)
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
    public function join3where($fromTable, $withTable, $condition, $withTable2, $condition2, $withTable3, $condition3, $where, $select = "*") : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select($select)
                        ->from($fromTable)
                        ->join($withTable, $condition)
                        ->join($withTable2, $condition2)
                        ->join($withTable3, $condition3)
                        ->where($where)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }



    /**
    * Join with another db table
    *
    * @return array Results
    */
    public function join2where3($fromTable, $withTable, $condition, $withTable2, $condition2, $where, $where2, $where3) : array
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
    public function join2Where2($fromTable, $withTable, $condition, $withTable2, $condition2, $where, $where2) : array
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
    * Join with another db table
    *
    * @return array Results
    */
    public function join2leftWhere2($fromTable, $withTable, $condition, $withTable2, $condition2, $where, $where2, $orderBy, $select = "*") : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select($select)
                        ->from($fromTable)
                        ->leftJoin($withTable, $condition)
                        ->join($withTable2, $condition2)
                        ->where($where)
                        ->andWhere($where2)
                        ->orderBy($orderBy)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }



    /**
    * Join with another db table
    *
    * @return array Results
    */
    public function join2leftWhere($fromTable, $withTable, $condition, $withTable2, $condition2, $where, $orderBy, $select = "*") : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select($select)
                        ->from($fromTable)
                        ->leftJoin($withTable, $condition)
                        ->join($withTable2, $condition2)
                        ->where($where)
                        ->orderBy($orderBy)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }



    /**
    * Join with another db table
    *
    * @return array Results
    */
    public function joinWhere3($fromTable, $withTable, $condition, $where, $where2, $where3, $select = "*") : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select($select)
                        ->from($fromTable)
                        ->join($withTable, $condition)
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
    public function where2Joins($fromTable, $withTable, $condition, $withTable2, $condition2, $where, $select = "*") : array
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select($select)
                        ->from($fromTable)
                        ->join($withTable, $condition)
                        ->join($withTable2, $condition2)
                        ->where($where)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }
}
