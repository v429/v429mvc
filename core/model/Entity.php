<?php

namespace Core\Model;

interface Entity
{
	/**
	 * get recodes by primary key and fill it to self property
	 * 
	 * @author v429
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public static function find($id);

	/**
	 * save record [add or update]
	 * 
	 * @author v429
	 * @return [int] [bool or insert id]
	 */
	public function save();

	/**
	 * where condition fill
	 * 
	 * @author v429
	 * @param  [type] $field  [table field]
	 * @param  [type] $paramA [value or more condition]
	 * @param  string $paramB [value]
	 * @return [type] obj     [self]
	 */
	public static function where($fields, $paramA, $paramB = '');

	/**
	 * where between condition fill
	 * 
	 * @author v429
	 * @param  [type] $field     [table field]
	 * @param  array  $condition [where between condition array]
	 * @return [type]            [vModel]
	 */
	public function whereBetween($fields, array $condition);

	/**
	 * where in condition fill
	 * 
	 * @author v429
	 * @param  [type] $fields [table field]
	 * @param  array  $param  [in array]
	 * @return [type]         [description]
	 */
	public function whereIn($fields, array $param);

	/**
	 * or where condition
	 * @author v429
	 * @param  [type] $callback [where function callback]
	 * @return [type]           [description]
	 */
	public function orWhere($callback);

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
	public function join($table, $fieldA, $fieldB, $condtion = '=', $joinType = 'left');

	/**
	 * recode order by
	 * 
	 * @author v429
	 * @param  string $field [table field]
	 * @param  string $order [order condition]
	 */
	public function orderBy($field, $order = 'ASC');

	/**
	 * set select limit and offset
	 * 
	 * @author v429
	 * @param  [type] $offset [description]
	 * @param  [type] $limit  [description]
	 * @return [type]         [description]
	 */
	public function limit($offset, $limit);

	/**
	 * recodes fields select
	 * 
	 * @author v429
	 * @param  array  $fields [description]
	 * @return [type]         [description]
	 */
	public function select(array $fields);

	/**
	 * get recodes
	 * 
	 * @author v429
	 * @return [type] [description]
	 */
	public function get();

	/**
	 * get recodes with paginate
	 * 
	 * @param  [type] $offset [description]
	 * @param  [type] $limit  [description]
	 * @author v429
	 * @return [type] [description]
	 */
	public function paginate($limit);

	/**
	 * start mysql transaction
	 * 
	 * @author v429
	 * @return [type] [description]
	 */
	public static function startTransaction();

	/**
	 * commit mysql transaction
	 * 
	 * @author v429
	 * @return [type] [description]
	 */
	public static function commit();

	/**
	 * roll back mysql transaction
	 * @author v429
	 * @return [type] [description]
	 */
	public static function rollback();
}