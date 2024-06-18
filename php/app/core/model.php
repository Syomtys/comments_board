<?php

class Model
{
	public function __construct()
	{
		$this->db = new mysqli(HOSTNAME, USERNAME, PASSWORD, DBNAME);
	}
	public function get_data($data)
	{
	}
}