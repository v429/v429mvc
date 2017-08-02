<?php

namespace Core\Response;

class Response
{

	const CODE_SUCCESS   = 200;
	const CODE_REDIRCT   = 302;
	const CODE_NOT_FOUND = 404;
	const CODE_ERROR     = 500;

	/**
	 * make a new response
	 * 
	 * @author v429
	 * @param  [type] $data   [description]
	 * @param  [type] $status [description]
	 * @param  string $header [description]
	 */
	public static function make($data, $type = 'application/json')
	{
		header('Content-type: ' . $type);

		$json = json_encode($data);

		echo $json;exit;
	}

	/**
	 * redirect to url
	 * 
	 * @author v429
	 * @param  string $url [description]
	 * @return [type]      [description]
	 */
	public static function redirect($url)
	{
		if ($url) 
		{
			Header("HTTP/1.1 " . self::CODE_REDIRCT . " See Other"); 
			Header("Location: " . $url);
			exit;
		}

		return true;
	}

} 