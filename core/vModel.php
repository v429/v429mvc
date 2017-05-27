<?php

namespace Core;

use Core\Model;

/**
 * orm vmodel class
 */
class vModel
{
	//more condition enum
	const MORE_WHERE_CONDITION = ['>', '<', '<>', 'like', 'in', 'between'];

	/**
	 * recodes fields value
	 * @var array
	 */
	protected $fieldsValues = [];

	/**
	 * model object
	 * @var object
	 */
	protected $model;

	/**
	 * table primary key
	 * @var string
	 */
	protected $primaryKey;

	/**
	 * table
	 * @var string
	 */
	protected $table;

	/**
	 * query sqls 
	 * @var array
	 */
	protected $querySql = [];

	/**
	 * where conditions
	 * @var array
	 */
	protected $whereCondition = [];

	/**
	 * order conditions
	 * @var array
	 */
	protected $orderCondition = [];

	/**
	 * group conditions
	 * @var array
	 */
	protected $groupCondition = [];

	/**
	 * table joins
	 * @var array
	 */
	protected $joins = [];


	/**
	 * [construction]
	 * @author v429
	 */
	public function __construct() 
	{
		//new model
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

		//do update query
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
	 * where condition fill
	 * 
	 * @author v429
	 * @param  [type] $field  [table field]
	 * @param  [type] $paramA [value or more condition]
	 * @param  string $paramB [value]
	 * @return [type] obj     [self]
	 */
	public function where($field, $paramA, $paramB = '')
	{
		$self = isset($this) ? $this : new static;

		//field is in table?
		if (!in_array($field, $self->model->getFields())) {
			die('ERROR field `' . $field . '` not exist in table '. $self->table . '!');
		}

		//has more where condition
		if (in_array($paramA, self::MORE_WHERE_CONDITION)) {
			$self->whereCondition[$field] = [
				'more_condition' => $paramA,
				'value'         => $paramB,
			];
		} else {
			$self->whereCondition[$field] = [
				'value'         => $paramA,
				'more_condition' => '',
			];
		}

		return $self;
	}

	/**
	 * recode order by
	 * 
	 * @author v429
	 * @param  string $field [table field]
	 * @param  string $order [order condition]
	 */
	public function orderBy($field, $order = 'ASC')
	{
		$this->orderCondition = [$field, $order];

		return $this;
	}

	/**
	 * get recodes
	 * 
	 * @author v429
	 * @return [type] [description]
	 */
	public function get()
	{
		$results = $whereCondition = [];		
		//query where
		foreach ($this->whereCondition as $k => $v) {
			$whereCondition[$k] = $v['more_condition'] ? [$v['more_condition'], $v['value']] : $v['value'];
		}

		$selects = $this->model->select($whereCondition, $this->orderCondition);

		foreach ($selects as $recode) {
			$obj = new $this;

			foreach ($recode as $field => $re) {
				$obj->$field = $re;
			}

			$results[] = $obj;
		}
		
		return $results;
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

		$fields = $self->model->find($id);

		foreach ($fields as $key => $value) {
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
		$fields = $this->model->getFields();

		foreach ($fields as $field) {
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