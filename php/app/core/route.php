<?php

class Route
{
	public static function start()
	{

		$uri = stripos($_SERVER['REQUEST_URI'], '?')?explode('?', $_SERVER['REQUEST_URI'])[0]:$_SERVER['REQUEST_URI'];
		$rawRoutes = array_values(array_filter(explode('/', $uri)));

		$getData = [];
		if ($_GET) {
			$getData = $_GET;
		}

		if (!empty($rawRoutes[1])){
			$action_name = 'Action_' . $rawRoutes[count($rawRoutes)-1];
			$controller_name = 'Controller_' . $rawRoutes[count($rawRoutes)-2];
			$model_name = 'Model_' . $rawRoutes[count($rawRoutes)-2];
			unset($rawRoutes[count($rawRoutes)-1]);
			unset($rawRoutes[count($rawRoutes)-1]);
			$path = implode('/', $rawRoutes) . '/';
		} else {
			$controller_name = 'Controller_Main';
			$action_name = 'Action_index';
			$model_name = 'Model_Main';
			$path = '';
		}

		$model_file = strtolower($model_name).'.php';
		$model_path = "app/models/" . $path . $model_file;
		if(file_exists($model_path))
		{
			include_once($model_path);
		}

		$controller_file = strtolower($controller_name).'.php';
		$controller_path = "app/controllers/" . $path . $controller_file;
		if(file_exists($controller_path))
		{
			include_once($controller_path);
		}
		else
		{
			Route::ErrorPage404();
		}

		$controller = new $controller_name;
		$action = $action_name;

		if(method_exists($controller, $action))
		{
			$controller->$action($getData);
		}
		else
		{
			Route::ErrorPage404();
		}
	}

	public static function ErrorPage404()
	{
		header("HTTP/1.0 404 Not Found");
		exit();
	}
}