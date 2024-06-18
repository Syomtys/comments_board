<?php

Class Model_Main extends Model
{
	private function check_DB()
	{
		$result = $this->db->query("SHOW TABLES LIKE '" . TABLENAME . "'");

		if ($result->num_rows == 0) {
			$create_table_query = "CREATE TABLE ". TABLENAME . " (
				id int(11) NOT NULL AUTO_INCREMENT,
				ip varchar(64) NOT NULL,
				browser varchar(256) NOT NULL,
				email varchar(128) NOT NULL,
				name varchar(64) NOT NULL,
				date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				comment TEXT NOT NULL,
				PRIMARY KEY (id)
			)";

			if ($this->db->query($create_table_query) === TRUE) {
				return [
					'status' => true,
					'message' => 'database created'
				];
			} else {
				return [
					'status' => false,
					'message' => $this->db->error
				];
			}
		} else {
			return [
				'status' => true,
				'message' => 'database exists'
			];
		}
	}
	public function get_data($getData)
	{
		$checkDB = $this->check_DB();
		if ($checkDB['status']){
			if ($getData['filter']){//user, email, date
				if ($getData['filter'] == 'name') {
					$filter = 'name';
				} elseif ($getData['filter'] == 'email') {
					$filter = 'name';
				} else {
					$filter = 'date';
				}
			} else {
				$filter = 'date';
			}

			if ($getData['sorted']){// desc, asc
				if ($getData['sorted'] == 'asc') {
					$sorted = 'ASC';
				} else {
					$sorted = 'DESC';
				}
			} else {
				$sorted = 'DESC';
			}
			$data = $this->db->query("SELECT comment, date, name, email FROM comments ORDER BY {$filter} {$sorted}");
			$data = $data->fetch_all(MYSQLI_ASSOC);

		} else {
			$data = false;
		}
		return [
			'data' => $data,
			'dbinfo' => $checkDB
		];
	}
	private function filter_data($data)
	{
		$filteredData = [];
		foreach ($data as $key => $value) {
			$filteredData[$key] = $this->db->real_escape_string($value);
		}
		return $filteredData;
	}

	public function insert_data($data)
	{
		$filteredData = $this->filter_data($data);
		$checkDB = $this->check_DB();
		if ($filteredData) {
			if ($checkDB['status']){
				$insert_table_query = "INSERT INTO ". TABLENAME .
					" (ip, browser, email, name, comment) 
            VALUES (?, ?, ?, ?, ?)";

				$stmt = $this->db->prepare($insert_table_query);
				if ($stmt) {
					// Привязываем параметры к подготовленному запросу и выполняем запрос
					$stmt->bind_param("sssss", $filteredData['ip'], $filteredData['browser'], $filteredData['email'], $filteredData['name'], $filteredData['comment']);
					$stmt->execute();
					$stmt->close();
					$data = true;
					$message = 'data inserted';
				} else {
					$data = false;
					$message = 'error inserting data';
				}
			} else {
				$data = false;
				$message = $checkDB['message'];
			}
		} else {
			$data = false;
			$message = 'data invalid';
		}
		return [
			'data' => $data,
			'dbinfo' => $checkDB,
			'message' => $message
		];
	}
}