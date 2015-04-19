<?php namespace GameOfLife;


class Config {

	public static $data;

	/**
	 * @param $key
	 * @return mixed array
	 */
	public static function getData($key)
	{
		return self::$data[$key];
	}

	/**
	 * @param $key
	 * @param mixed $data
	 */
	public static function setData($key, $data)
	{
		self::$data[$key] = $data;
	}



}