<?php
namespace Core\Model;

use Core\Model\Model;
use Core\Model\Entity;
/**
 * orm vmodel class
 */
class vModel implements Entity
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
	 * [$selectFields description]
	 * @var array
	 */
	protected $selectFields = [];

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
	 * select limit
	 * @var array
	 */
	protected $limit = 0;

	/**
	 * select offset
	 * @var integer
	 */
	protected $offset = 0;

	/**
	 * [$isSelectPrimaryKey description]
	 * @var boolean
	 */
	protected $isSelectPrimaryKey = true;

	/**
	 * [$primaryValue description]
	 * @var string
	 */
	public $primaryValue = '';


	/**
	 * [construction]
	 * @author v429
	 */
	public function __construct($table = '', $primaryKey = '') 
	{
		if ($table) {
			$this->table = $table;

			$this->primaryKey = $primaryKey;
		}
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
		if ($this->primaryValue) {
			$whereCondition = [$this->primaryKey => $this->primaryValue];

			$result = $this->model->update($whereCondition, $data);

			$this->_afterQuery();

			return $result;
		}

		//do insert query
		$this->$primaryKey = $this->model->add($data);

		$this->_afterQuery();

		return $this->primaryValue;
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
				'value'          => $paramB,
			];
		} else {
			$self->whereCondition[$field] = [
				'value'          => $paramA,
				'more_condition' => '',
			];	
		}

		return $self;
	}

	/**
	 * where between condition fill
	 * 
	 * @author v429
	 * @param  [type] $field     [table field]
	 * @param  array  $condition [where between condition array]
	 * @return [type]            [vModel]
	 */
	public function whereBetween($field, array $condition)
	{
		$self = isset($this) ? $this : new static;

		//field is in table?
		if (!in_array($field, $self->model->getFields())) {
			die('ERROR field `' . $field . '` not exist in table '. $self->table . '!');
		}

		$self->whereCondition[$field] = ['more_condition' => 'between', 'value' => $condition];

		return $self;
	}

	/**
	 * where in condition fill
	 * 
	 * @author v429
	 * @param  [type] $fields [table field]
	 * @param  array  $param  [in array]
	 * @return [type]         [description]
	 */
	public function whereIn($field, array $param)
	{
		$self = isset($this) ? $this : new static;

		//field is in table?
		if (!in_array($field, $self->model->getFields())) {
			die('ERROR field `' . $field . '` not exist in table '. $self->table . '!');
		}

		$self->whereCondition[$field] = ['more_condition' => 'IN', 'value' => $param];

		return $self;
	}

	/**
	 * or where condition
	 * @author v429
	 * @param  [type] $callback [where function callback]
	 * @return [type]           [description]
	 */
	public function orWhere($callback)
	{
		//TODO:
	}

	/**
	 * join condition
	 * 
	 * @author v429
	 * @param  [type] $table    [join table]
	 * @param  [type] $fieldA   [main table field]
	 * @param  [type] $fieldB   [sec table field]
	 * @param  string $condtion [connect condition default equal]
	 * @param  string $joinType [table join type default left]
	 * @return [type]           [description]
	 */
	public function join($table, $fieldA, $fieldB, $condtion = '=', $joinType = 'left')
	{
		//TODO:
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
	 * set select limit and offset
	 * 
	 * @author v429
	 * @param  [type] $offset [description]
	 * @param  [type] $limit  [description]
	 * @return [type]         [description]
	 */
	public function limit($offset, $limit)
	{
		$this->limit = $limit;

		$this->offset = $offset;

		return $this;
	}

	/**
	 * recodes fields select
	 * 
	 * @author v429
	 * @param  array  $fields [description]
	 * @return [type]         [description]
	 */
	public function select(array $fields)
	{
		if (!in_array($this->primaryKey, $fields)) {
			$this->isSelectPrimaryKey = false;
		}

		$this->selectFields = $fields;

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
		$self = isset($this) ? $this : new static;

		$primaryKeyField = $self->primaryKey;

		$results = $whereCondition = [];

		//query where
		foreach ($self->whereCondition as $k => $v) {
			$whereCondition[$k] = $v['more_condition'] ? [$v['more_condition'], $v['value']] : $v['value'];
		}

		//set primary key
		if (!$this->isSelectPrimaryKey) {
			array_push($self->selectFields, $self->primaryKey);
		}

		//get select recodes
		$selects = $self->model->select($whereCondition, $self->orderCondition, $self->offset, $self->limit, $self->selectFields);

		//fill query result obj
		foreach ($selects as $recode) {
			$obj = new QueryResult($self->table, $self->primaryKey);

			$obj->primaryValue = $recode[$primaryKeyField];

			//not select primary key
			if (!$self->isSelectPrimaryKey) {
				unset($recode[$primaryKeyField]);
			}
			
			$obj->create($recode);
			$results[] = $obj;			
		}		

		return $results;
	}

	/**
	 * get recodes count
	 */
	public function count()
	{
		$self = isset($this) ? $this : new static;

		$whereCondition = [];

		//query where
		foreach ($self->whereCondition as $k => $v) {
			$whereCondition[$k] = $v['more_condition'] ? [$v['more_condition'], $v['value']] : $v['value'];
		}

		$count = $self->model->count($whereCondition);

		return $count;
	}

	/**
	 * paginate recodes
	 * 
	 * @author v429
	 * @param  [type] $limit [description]
	 * @return [type]        [description]
	 */
	public function paginate($limit)
	{
		if ($limit == 0) {
			return [];
		}
		$self = $this;

		$page   = isset($_POST['page']) ? $_POST['page'] : $_GET['page'];
		$offset = $page ? ($page-1) * $limit : 0;

		//set limit condition
		$self->limit($offset, $limit);

		$total = $self->count();

		$lastPage = ceil($total / $limit);

		$recodes = $self->get();

		return compact('total', 'lastPage',  'offset', 'limit', 'recodes');
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

		$result = new QueryResult($self->table, $self->primaryKey);

		$result->create($fields);

		return $result;
	}

	/**
	 * query the original sql query
	 * 
	 * @author v429
	 * @param  [type] $sql [original sql query]
	 * @return recodes or insert id or bool
	 */
	public static function query($sql)
	{
		$self = new static;

		$self->querySql = $sql;

		if (strstr(strtolower($sql), 'select')) {
			return $self->model->_getSelectResult($sql);
		}

		return $self->model->query($sql);
	}

		/**
	 * start mysql transaction
	 * 
	 * @author v429
	 * @return [type] [description]
	 */
	public static function startTransaction()
	{
		//TODO
	}

	/**
	 * commit mysql transaction
	 * 
	 * @author v429
	 * @return [type] [description]
	 */
	public static function commit()
	{
		//TODO
	}

	/**
	 * roll back mysql transaction
	 * @author v429
	 * @return [type] [description]
	 */
	public static function rollback()
	{
		//TODO
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
	protected function _afterQuery() {
		$this->querySql[] = $this->model->sql;

		//unset($this->model, $this->table, $this->primaryKey);
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