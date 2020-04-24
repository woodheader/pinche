<?php
require_once('help.class.php');
class mysql {
	var $linkid=null;
    function __construct($dbhost, $dbuser, $dbpw, $dbname = '', $dbcharset = 'utf8', $connect = 1) {
    	$this -> connect($dbhost, $dbuser, $dbpw, $dbname, $dbcharset, $connect);
    }

    function connect($dbhost, $dbuser, $dbpw, $dbname = '', $dbcharset = 'gbk', $connect=1){
    	$func = empty($connect) ? 'mysql_pconnect' : 'mysqli_connect';
    	if(!$this->linkid = @$func($dbhost, $dbuser, $dbpw)){
    		$this->dbshow('Can not connect to Mysql!');
    	} else {
    		if($this->dbversion() > '4.1'){
    			mysqli_query( $this->linkid, "SET NAMES " . $dbcharset);
    			if($this->dbversion() > '5.0.1'){
    				mysqli_query($this->linkid, "SET sql_mode = ''");
					mysqli_query($this->linkid,"SET character_set_connection=".$dbcharset.", character_set_results=".$dbcharset.", character_set_client=binary");
    			}
    		}
    	}
    	if($dbname){
    		if(mysqli_select_db($this->linkid, $dbname)===false){
    			$this->dbshow("Can't select MySQL database($dbname)!");
    		}
    	}
    }

    function select_db($dbname){
    	return mysqli_select_db($this->linkid, $dbname);
    }
    function query($sql){
        $sql=help::CheckSql($sql);

    	if(!$query=@mysqli_query($this->linkid, $sql)){
    		$this->dbshow("Query error:$sql");
    	}else{
    		return $query;
    	}
    }
    function inserttable($tablename, $insertsqlarr, $returnid=0, $replace = false, $silent=0){
        $insertkeysql = $insertvaluesql = $comma = '';
        foreach ($insertsqlarr as $insert_key => $insert_value) {
            $insertkeysql .= $comma.'`'.$insert_key.'`';
            $insertvaluesql .= $comma.'\''.$insert_value.'\'';
            $comma = ', ';
        }
        $method = $replace?'REPLACE':'INSERT';
        $state = $this->query($method." INTO $tablename ($insertkeysql) VALUES ($insertvaluesql)");
		
        if($returnid && !$replace) {
			
            return $this->insert_id();
        }else {
			
            return $state;
        } 
    }
    function updatetable($tablename, $setsqlarr, $wheresqlarr)
    {
        $setsql = $comma = '';
        foreach ($setsqlarr as $set_key => $set_value) {
            if(is_array($set_value)) {
                $setsql .= $comma.'`'.$set_key.'`'.'=\''.$set_value[0].'\'';
            } else {
                $setsql .= $comma.'`'.$set_key.'`'.'=\''.$set_value.'\'';
            }
            $comma = ', ';
        }
        $where = $comma = '';
        if(empty($wheresqlarr)) {
            $where = '1';
        } elseif(is_array($wheresqlarr)) {
            foreach ($wheresqlarr as $key => $value) {
                $where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';
                $comma = ' AND ';
            }
        } else {
            $where = $wheresqlarr;
        }
        $sql = "UPDATE ".($tablename)." SET ".$setsql." WHERE ".$where;
        return $this->query($sql);
    }
    function getall($sql, $type=MYSQLI_ASSOC){
    	$query = $this->query($sql);
    	while($row = mysqli_fetch_array($query,$type)){
    		$rows[] = $row;
    	}
    	return help::htmlspecialchars_($rows);
    }

    function getone($sql, $type=MYSQLI_ASSOC){
    	$query = $this->query($sql);
    	$row = mysqli_fetch_array($query, $type);
        foreach ($row as $key => $value) {
           $row[$key]=$value;
        }
    	return help::htmlspecialchars_($row);
    }
	function get_total($sql)
	{
		$row = $this->getall($sql);
		$v=0;
		if (!empty($row) && is_array($row))
		{			
			foreach($row as $n)
			{
			$v=$v+$n['num'];
			}			
		}
		return $v;
 	}
    function getfirst($sql, $type=MYSQLI_NUM) {
    	$query = $this->query($sql);
    	$row = mysqli_fetch_array($query, $type);
    	return $row[0];
    }
	function fetch_array($result,$type = MYSQLI_ASSOC){
		return mysqli_fetch_array($result,$type);
	}

    function affected_rows(){
    	return mysqli_affected_rows($this->linkid);
    }

    function num_rows(){
    	return mysqli_num_rows($this->linkid);
    }

    function num_fields($result){
    	return mysqli_num_fields($result);
    }

    function insert_id(){
    	return mysqli_insert_id($this->linkid);
    }

    function free_result($result){
    	return mysqli_free_result($result);
    }
	
	function escape_string($string)
    {
        if (PHP_VERSION >= '4.3')
        {
            return mysqli_real_escape_string($string, $this->linkid);
        }
        else
        {
            return mysqli_escape_string($string, $this->linkid);
        }
    }
    function error(){
    	return mysqli_error($this->linkid);
    }

    function errno(){
    	return mysqli_errno($this->linkid);
    }

    function close(){
    	return mysqli_close($this->linkid);
    }

    function dbversion(){
    	return mysqli_get_server_info($this->linkid);
    }

    function dbshow($err)
	{
    	if($err){
    		$info = "Error：".$err;
    	}else{
    		$info = "Errno：".$this->errno()." Error：".$this->error();
    	}
    	exit($info);
        // exit("数据库错误,请联系网站管理员！");
    }
}
?>