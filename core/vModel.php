<?php

namespace Core;

use Core\Model;

/**
 * orm vmodel class
 */
class vModel
{
	protected $model;

	protected $primaryKey;

	protected $table;

	protected $fieldsValues = [];

	protected $querySql = [];

	protected $whereCondition = '';

	protected $orderCondition = '';

	protected $groupCondition = '';

	protected $joins = '';

	/**
	 * [construction]
	 * @author v429
	 */
	public function __construct() 
	{
		$this->model = new Model($this->table, $this->primaryKey);

		$this->_fillFiledProperty();
	}

	/**
	 * save record [add or update]
	 * 
	 * @author v429
	 * @return [int] [bool or insert id]
	 */
	public function save()
	{
		//init primary key str
		$primaryKey = $this->primaryKey;

		$data = array();
		foreach ($this->model->getFields() as $field) {
			if ($field == $this->primaryKey) {
				continue;
			}
			$data[$field] = $this->$field;
		}

		// do update query
		if ($this->$primaryKey) {
			$whereCondition = [$this->primaryKey => $this->$primaryKey];

			$result = $this->model->update($whereCondition, $data);

			$this->_afterQuery();

			return $result;
		}

		//do insert query 
		$this->$primaryKey = $this->model->add($data);

		$this->_afterQuery();

		return $this->$primaryKey;
	}

	/**
	 * get recodes by primary key and fill it to self property
	 * 
	 * @author v429
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public static function find($id) 
	{
		$self = new static;

		$fileds = $self->model->find($id);

		foreach ($fileds as $key => $value) {
			$self->$key = $value;
		}

		return $self;
	}

	/**
	 * fill table field as property
	 * 
	 * @author v429
	 * @return [type] [description]
	 */
	protected function _fillFiledProperty()
	{
		$fileds = $this->model->getFields();

		foreach ($fileds as $field) {
			$this->fieldsValues[$field] = '';
		}
	}

	/**
	 * clean some property and fill some property
	 * 
	 * @author v429
	 * @return [type] [description]
	 */
	private function _afterQuery() {
		$this->querySql[] = $this->model->sql;

		unset($this->model, $this->table, $this->primaryKey);
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