<?php
require_once('config.php');
require_once('mysql.class.php');
require_once('BaseModel.php');
require_once('common.php');
require_once('redis.php');
abstract class ActiveRecord extends BaseModel
{
    private static $db;
    private static $dbhost;
    private static $dbuser;
    private static $dbpass;
    private static $dbname;

    private $conditions = [];
    private $orderBy = [];

    private static function getConfig() {
        global $dbhost, $dbuser, $dbpass, $dbname;
        self::$dbhost = $dbhost;
        self::$dbuser = $dbuser;
        self::$dbpass = $dbpass;
        self::$dbname = $dbname;
    }

    public function table($table)
    {
        global $pre;
        return $pre .$table ;
    }

    public static function getInstanceDB() {
        if (empty(self::$db)) {
            self::getConfig();
            self::$db = new mysql(self::$dbhost,self::$dbuser,self::$dbpass,self::$dbname, CHARSET_DEFAULT);
        }
        return self::$db;
    }
    
    public function validate() {
        try {
            return $this->validateRule();
        } catch (Exception $ex) {
            $this->addMessage($ex->getMessage());
            return false;
        }
    }

    public function addWhere($condition = []) {
        if (empty($condition)) {
            return $this;
        }
        $this->conditions[] = $condition;
        return $this;
    }

    public function save() {
        if (!$this->validate()) {
            return false;
        }
        return self::getInstanceDB()->inserttable($this->tablename(), $this->toDbArray());
    }

    public function update() {
        if (!$this->validate()) {
            return false;
        }
        $where = $this->createCondition();
        $this->clearConditions();
        return self::getInstanceDB()->updatetable($this->tablename(), $this->toDbArray(), implode('', $where));
    }

    public function one() {
        $sql = "select * from " . $this->tablename() . " where 1=1 ";
        $where = $this->createCondition();
        if (!empty($where)) {
            $sql .= ' AND ';
        }
        $sql .= implode(' AND ', $where);
        $orderBy = implode(' ', $this->orderBy);
        $sql .= $orderBy;
        $sql .= ' limit 1';
        $this->clearConditions();
        return self::getInstanceDB()->getone($sql);
    }

    public function all() {
        $sql = "select * from " . $this->tablename() . " where 1=1";
        $where = $this->createCondition();
        if (!empty($where)) {
            $sql .= ' AND ';
        }
        $sql .= implode(' AND ', $where);
        $orderBy = implode(' ', $this->orderBy);
        $sql .= $orderBy;
        $this->clearConditions();
        return self::getInstanceDB()->getall($sql);
    }

    public function createCondition() {
        $where = [];
        foreach ($this->conditions as $condition) {
            $conSize = count($condition);
            if ($conSize == 1) {
                $field = key($condition);
                $where[] =  $field . ' = \'' . $condition[$field] . '\'';
            } elseif ($conSize == 3) {
                $op = $condition[0];
                $field = $condition[1];
                $value = $condition[2];
                if (in_array($this->getFieldType($field), ['int'])) {
                    if ($op == 'like') {
                        $where[] =  $field . ' ' . $op . '\'%' . $value . '%\'';
                    } elseif ($op == 'in') {
                        $where[] =  $field . ' ' . $op . '(' . $value . ')';
                    } else {
                        if (!in_array($op, ['>', '>=', '=', '<=', '<'])) {
                            $op = '=';
                        }
                        $where[] =  $field . ' ' . $op . " '" .trim($value) . "'";
                    }
                } else {
                    if (!in_array($op, ['like','='])) {
                        $op = '=';
                    }
                    if ($op == 'like') {
                        $where[] =  $field . ' ' . $op . '\'%' . $value . '%\'';
                    } else {
                        $where[] =  $field . ' ' . $op . '\'' . $value . '\'';
                    }
                }
            } elseif ($conSize == 4) {
                $op = $condition[0];
                $field = $condition[1];
                $value1 = $condition[2];
                $value2 = $condition[3];
                if (in_array($this->getFieldType($field), ['datetime'])) {
                    if($op == 'between') {
                        $where[] =  $field . ' ' . $op . '\'' . $value1 . '\' and \'' . $value2 . '\'';
                    }
                }
            }
        }
        return $where;
    }

    public function clearConditions() {
        $this->conditions = [];
    }

    public function orderBy($orderBy = '') {
        if (empty($orderBy)) {
            return;
        }
        $this->orderBy = ['order by', $orderBy];
    }
}