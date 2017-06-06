<?php

namespace Core\Model;

/**
 * mysql connect and data model base object
 */

class Model 
{

	public $sql = [];

	/**
	 * @param $db config db name
	 */
	protected $db;

	/**
	 * @param $table table name define
	 */
	protected $table;

	/**
	 * @var mysqli mysql resource
	 */
	protected $mysql;

	/**
	 * @var array table fields list
	 */
	protected $_fields = [];

	/**
	 * @var string table primary key define
	 */
	protected $primaryKey = 'id';



	/**
	 * construct v429 model !!!
	 */
	public function __construct($table = '', $primaryKey = '') 
	{
		$configs = include('app/config.php');
		$this->db = $configs['mysql']['db_database'];

		if (!$configs) {
			die('ERROR:config file not exist!');
		}
		//set mysql connect
		$this->mysql =  new \mysqli(
			$configs['mysql']['db_host'], 
			$configs['mysql']['db_user'], 
			$configs['mysql']['db_pwd'], 
			$configs['mysql']['db_database']
		);

		//connect error
		if ($this->mysql->connect_error) {
			die('Error : ('. $this->mysql->connect_errno .') '. $this->mysql->connect_error);
		}

		//set charset
		mysqli_set_charset($this->mysql, $configs['mysql']['db_charset']);

		//fill self table and primary key
		if ($table) {
			$this->table = $table;
		}

		if ($primaryKey) {
			$this->primaryKey = $primaryKey;
		}

		//init fields
		$this->_initAllFIeld();
	}

	/**
	 * get select result by sql
	 */
	public function _getSelectResult($sql) 
	{
		$this->sql[] = $sql;

		$query = $this->mysql->query($sql);

		$data = array();
		while($row = $query->fetch_assoc()) {
			array_push($data, $row);
		}

		return $data;
	}

	/**
	 * init all fields into this param fields
	 */
	protected function _initAllFIeld() 
	{
		$sql = 'select COLUMN_NAME from information_schema.COLUMNS where table_name = "' . $this->table.'"
				AND `TABLE_SCHEMA`="'. $this->db .'";';

		$this->_fields = array_column($this->_getSelectResult($sql), 'COLUMN_NAME');
	}

	/**
	 * split this table field as insert sql type
	 */
	protected function _splitFieldStr() 
	{
		$result = array();
		foreach ($this->_fields as $key => &$value) {
			//add ` to each field
			if ($value == 'id') {
				continue;
			}
			$result[$key] = '`'. $value.'`';
		}

		$str = implode(',', $result);
		$str = '(' . $str .')';
		return $str;
	}

	/**
	 * add "'" to string data
	 */
	protected function _stringData(&$data) 
	{
		foreach ($data as $key => $value) {
			if (gettype($value) == 'string') {
				$data[$key] = "'".$value."'";
			}
		}
	}

	/**
	 * split add or update data into sql type
	 */
	protected function _splitAddData($data) 
	{
		$result = array();
		//filp this fields 
		$tableFields = array_flip($this->_fields);

		//add '' to string value
		$this->_stringData($data);

		foreach ($data as $key => $value) {
			//filter fields
			if (!in_array($key, $this->_fields)) {
				continue;
			}
			//sort the add data with this fields sort
			$result[$tableFields[$key]] = $value;
		}

		ksort($result);
		$str = implode(',', $result);
		return $str;
	}

	/**
	 * spare update data sql from array
	 */
	protected function _splitUpdateData($data) 
	{
		$result = '';

		$this->_stringData($data);

		foreach ($data as $key => $value) {
			if (in_array($key, $this->_fields)) {
				$result .= '`' . $key . '`=' . $value . ',';				
			}
		}

		$result = substr($result, 0, -1);
		return $result;
	}

	/**
	 * make array to where condition sql string
	 */
	protected function _whereCondition($condition) 
	{
		$result = 'WHERE ';

		foreach ($condition as $key => $value) {
			if (in_array($key, $this->_fields)) {
				if (!is_array($value)) {
					$this->_stringData($condition);
					$result .= "`" . $key . "`=" . $value. ' AND ';
				} else {
					$result .= $this->_moreWhere($key, $value);
				}
			}
		}
		//substr last 'and'
		$result = substr($result, 0, -5);

		return $result;
	}

	/**
	 * more where condition split sql
	 */
	protected function _moreWhere($field, $condition) 
	{
		$result = '`' . $field . '`';

		$tag = strtolower($condition[0]);
		if (gettype($condition[1]) == 'string') {
			$condition[1] == "'" . $condition[1] . "'";
		}

		switch ($tag) {
			case 'in':
				$result .=  ' IN (' . implode(',', $condition[1]) . ')';
				break;
			case '>':
				$result .=  ' >' . $condition[1];
				break;
			case '<':
				$result .=  ' <' . $condition[1];
				break;
			case '<>':
				$result .=  ' <>' . $condition[1];
				break;
			case 'between':
				$result .= ' BETWEEN ' . $condition[1][0] . ' AND ' . $condition[1][1];
		}

		$result .= ' AND ';

		return $result;
	}

	/**
	 * make select fields array to sql string
	 */
	protected function _stringFieldsSelect(array $fields) 
	{
		$result = '';

		foreach ($fields as $key => $value) {
			if (in_array($value, $this->_fields)) {
				$result .= '`' . $value . '`,';
			}
		}
		//remove last ',' from sql
		$result = substr($result, 0, -1);

		return $result;
	}

	/**
	 * make select order by sql
	 */
	protected function _stringOrderBy($orderBy) 
	{
		$result = 'ORDER BY ';
		//if is string, explode it!
		if (!is_array($orderBy)) {
			$orderBy = explode(' ', $orderBy);
		}

		$result .= '`' . $orderBy[0] . '` ' . strtoupper($orderBy[1]);

		return $result;
	} 

	/**
	 * query the sql
	 */
	public function query($sql) 
	{
		$this->sql[] = $sql;

		if ($this->mysql->query($sql)) {
			return $this->mysql->insert_id;
		}

		die($this->mysql->error);
	}

	/**
	 * add data to table
	 */
	public function add($data) 
	{
		$sql = "INSERT INTO `" . $this->table . "`" . $this->_splitFieldStr();

		$sql .= ' VALUES(' . $this->_splitAddData($data). ');';

		$this->query($sql);

		return mysqli_insert_id($this->mysql);
	}

	/**
	 * insert many records to table
	 */
	public function insert($data) 
	{
		$sql = "INSERT INTO `" . $this->table . "`" . $this->_splitFieldStr() . ' VALUES';

		foreach ($data as $key => $value) {
			$sql .= '(' . $this->_splitAddData($value) . '),';
		}
		//remove last ','
		$sql = substr($sql, 0, -1) . ';';
		
		$this->query($sql);
	}

	/**
	 * update data for where condition
	 */
	public function update($whereCondition, $data) 
	{
		$sql = "UPDATE `" . $this->table . "` SET ";

		$sql .= $this->_splitUpdateData($data);

		$sql .= ' ' . $this->_whereCondition($whereCondition) . ';';

		$this->query($sql);
	}

	/**
	 * delete record by where condition
	 */
	public function delete($whereCondition) 
	{
		$sql = "DELETE FROM `" . $this->table . "` ";

		$sql .= $this->_whereCondition($whereCondition). ';';

		$this->query($sql);
	}

	/**
	 * get records by where condition and order condition and limit condition and fields
	 */
	public function select($whereCondition, $orderBy = '', $offset = 0, $limit = 0, $fields = []) 
	{
		$sql = "SELECT * FROM `" . $this->table . '` ';

		//get fields sql
		if ($fields && is_array($fields)) {
			$sql = "SELECT " . $this->_stringFieldsSelect($fields) . ' FROM `' . $this->table . '` ';
		}

		//get where sql
		$sql .= $this->_whereCondition($whereCondition);

		//get order by sql
		if ($orderBy) {
			$sql .=  ' ' . $this->_stringOrderBy($orderBy);
		}

		//get limit sql
		if ($limit) {
			$sql .= ' LIMIT '. $offset . ',' . $limit;
		}

		$sql .= ';';
//echo $sql;exit;
		$result = $this->_getSelectResult($sql);

		return $result;
	}

	/**
	 * get one record by primary key
	 */
	public function find($id) 
	{
		$result = $this->select([$this->primaryKey => $id]);

		return $result ? $result[0] : [];
	}

	/**
	 * get fields public function
	 * 
	 * @author v429
	 * @return array fields
	 */
	public function getFields()
	{
		return $this->_fields;
	}
}