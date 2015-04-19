<?php namespace GameOfLife;


class Config {

	public $data = [];

	/**
	 * @param $key
	 * @return mixed array
	 */
	public function getData($key)
	{
		return $this->data[$key];
	}

	/**
	 * @param $key
	 * @param mixed $data
	 */
	public function setData($key, $data)
	{
		$this->data[$key] = $data;
	}



}