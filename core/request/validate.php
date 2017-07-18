<?php

namespace Core\Request;

class Validate
{
	/**
	 * @var string
	 */
	public $errorMsg = array();

	/**
	 * add a error massage string
	 * 
	 * @author v429
	 */
	private function errorMsgAdd($name, $method)
	{
		$this->errorMsg[] = $name . ':' . $method . ' validate failed!';
	}

	/**
	 * check the param with rule string
	 * 
	 * @author v429
	 * @param  $value param value
	 * @param  $ruleStr rule string
	 * @param  $name param name
	 */
	public function check($value, $ruleStr, $name)
	{
		$isPass = true;

		if ($ruleStr) 
		{
			$rules = explode('|', $ruleStr);

			foreach ($rules as $rule)
			{
				//without params
				if (!strstr($rule, ':')) {
					$isPass = call_user_func(array('self', $rule), $value);

					if (!$isPass) 
					{
						$this->errorMsgAdd($name, $rule);
					}
				} else {
				//with params
					$ruleArr = explode(':', $rule);

					$params = explode(',', $ruleArr[1]);

					array_unshift($params, $value);

					$method = $ruleArr[0];
					$isPass = call_user_func_array(array('self',$method), $params);	

					if (!$isPass) 
					{
						$this->errorMsgAdd($name, $method);
					}
				}
			}
		}

		return $isPass;
	}

	/**
	 * check param required
	 * 
	 * @author v429
	 * 
	 */
	public function required($value)
	{
		return $value ? true : false;
	}

	/**
	 * check param is numeric
	 * 
	 * @author v429
	 */
	public function numeric($value)
	{
		return is_numeric($value) ? true : false;
	}

	/**
	 * check param min value
	 * 
	 * @author v429
	 */
	public function min($value, $min)
	{
		return strlen($value) >= $min ? true : false;
	}

	/**
	 * check param max value
	 * 
	 * @author v429
	 */
	public function max($value, $max)
	{
		return strlen($value) <= $max ? true : false;
	}

	/**
	 * check param value between max and min
	 * 
	 * @author v429
	 */
	public function between($value, $max, $min)
	{
		if (strlen($value) >= $min && strlen($value) <= $max)
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
	public function phone($value)
	{
		if (!is_numeric($value)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $value) ? true : false;
	}

	/**
	 * check param is email
	 * 
	 * @author v429
	 */
	public function email($value)
	{
		if (ereg('^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+',$value)){
			return true;
		}

		return false;
	}



}