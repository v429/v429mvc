<?php

namespace Core\Request;

class Validate
{
	/**
	 * @var boolean
	 */
	protected $isPass   = true;

	/**
	 * @var string
	 */
	protected $value    = '';

	/**
	 * @var string
	 */
	protected $errorMsg = '';

	/**
	 * construct the validate
	 * 
	 * @author v429
	 * @param  $value   checked param
	 * @param  string $ruleStr rule string
	 */
	public function __construct($value, $ruleStr = '')
	{
		$this->value = $value;

		if ($ruleStr) 
		{
			$rules = explode('|', $ruleStr);

			foreach ($rules as $rule)
			{
				//without params
				if (!strstr($rule, ':')) {
					$this->isPass = call_user_func(array('self', $rule), $this->value);
				} else {
				//with params
					$ruleArr = explode(':', $rule);

					$params = explode(',', $ruleArr[1]);

					$method = $ruleArr[0];
					$this->isPass = call_user_func_array(array('self',$method), $params);	
				}
			}
		}
	}

	public function errorMsgAdd($value, $method)
	{
		$this->errorMsg = $value . ':' . $method . ' validate failed!';
	}

	public function check()
	{
		return $this->isPass;
	}

	/**
	 * check param required
	 * 
	 * @author v429
	 * 
	 */
	public function required()
	{
		return $this->value ? true : false;
	}

	/**
	 * check param is numeric
	 * 
	 * @author v429
	 */
	public function numeric()
	{
		return is_numeric($this->value) ? true : false;
	}

	/**
	 * check param min value
	 * 
	 * @author v429
	 */
	public function min($min)
	{
		return $this->value >= $min ? true : false;
	}

	/**
	 * check param max value
	 * 
	 * @author v429
	 */
	public function max($max)
	{
		return $this->value <= $max ? true : false;
	}

	/**
	 * check param value between max and min
	 * 
	 * @author v429
	 */
	public function between($max, $min)
	{
		if ($this->value >= $min && $value <= $max)
		{
			return true;
		}

		return false;
	}

	/**
	 * check param is phone
	 * 
	 * @author v429
	 */
	public function phone()
	{
		if (!is_numeric($this->value)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $this->value) ? true : false;
	}

	/**
	 * check param is email
	 * 
	 * @author v429
	 */
	public function email()
	{
		if (ereg('^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+',$this->value)){
			return true;
		}

		return false;
	}



}