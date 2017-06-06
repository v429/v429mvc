<?php

namespace Core\Model;

class QueryResult
{
	/**
	 * model table to create vmodel
	 * @var string
	 */
	protected $table = '';

	/**
	 * model primary key to create vmodel
	 * @var string
	 */
	protected $primaryKey = '';

	/**
	 * $fieldsValues
	 * @var array
	 */
	public $fieldsValues = [];

	/**
	 * construct query result object
	 * 
	 * @author v429
	 * @param  string $table      [description]
	 * @param  string $primaryKey [description]
	 */
	public function __construct($table = '', $primaryKey = '')
	{
		$this->table = $table;

		$this->primaryKey = $primaryKey;
	}

	/**
	 * save recodes
	 * 
	 * @author v429
	 * @return [type] [description]
	 */
	public function save() 
	{
		$vModel = new vModel($this->table, $this->primaryKey);
		
		foreach ($this->fieldsValues as $field => $value) {
			$vModel->$field = $value;
		}

		$vModel->save();

		return true;
	}

	/**
	 * create with vmodel field value
	 * 
	 * @author v429
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function create($data)
	{
		foreach ($data as $field => $value) {
			$this->fieldsValues[$field] = $value;
		}
	}

	/**
	 * magic method __get get self fieldsValues
	 * 
	 * @author v429
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function __get($name)  
	{
		if (isset($this->fieldsValues[$name])) {
			return $this->fieldsValues[$name];
		}

		return null;
	}

	/**
	 * magic method __set fill self fieldsValues
	 * 
	 * @author v429
	 * @param  [type] $name  [description]
	 * @param  [type] $value [description]
	 */
	public function __set($name, $value)
	{
		$this->fieldsValues[$name] = $value;
	}

}