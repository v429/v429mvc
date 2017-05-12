<?php

class indexController extends Controller {
	
	public function index() {
		$user = $this->loadM('usermodel');

		$userInfo = $user->find(1);
		$data['user'] = $userInfo;

		$this->display('index-test', $data);
	}

	public function userList() {
		$user = $this->loadM('usermodel');

		$list = $user->get(['id' => ['>', 0]]);

		$data['list'] = $list;

		$this->display('index-list', $data);
	}

	public function getName() 
	{
		if ($_POST['sub-add-user']) {
			$values = $_POST;

			$user = $this->loadM('usermodel');

			$result = $user->add($values);
			if ($result) {
				echo 'ok';
			}
		}
		$this->display('add-user', []);
	}

	public function testInsert() {
		$user = $this->loadM('usermodel');

		$data = [
			['name' => 'test-insert-1', 'content' => 'test-insert-1', 'sex' => 1, 'birthday' => '19910429'],
			['name' => 'test-insert-2', 'content' => 'test-insert-2', 'sex' => 2, 'birthday' => '19910429'],
			['name' => 'test-insert-3', 'content' => 'test-insert-3', 'sex' => 1, 'birthday' => '19910429'],
			['name' => 'test-insert-4', 'content' => 'test-insert-4', 'sex' => 1, 'birthday' => '19910429'],
		];

		$rs = $user->insert($data);

		var_dump($rs);
	}
}

