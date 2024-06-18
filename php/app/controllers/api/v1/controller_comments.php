<?php
class Controller_Comments extends Controller
{
	function __construct()
	{
		parent::__construct();
		$this->model = new Model_Main();
	}

	public function action_get($getData)
	{
		$data = $this->model->get_data($getData);
		$this->view->generate('main_view.php', 'template_api.php', $data);
	}
	public function action_insert($getData)
	{
		$data = $this->model->insert_data($getData);
		$this->view->generate('main_view.php', 'template_api.php', $data);
	}
}