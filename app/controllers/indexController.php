<?php

namespace App\Controller;

use Core\Request\Controller;
use Core\Request\Input;
use App\Models\UserModel;
use App\Models\TestModel;
use Core\Response\Response;

class indexController extends Controller {
	
	public function index() 
	{
echo 'hello world';exit;
		$name = $this->input->get('user', 'required|min:2|phone|max:4', 'get');

		Response::redirect('http://www.baidu.com');
		echo '<pre>';print_r($this->input->getErrorMsg());exit;
/*		$test = new TestModel();
		$test->name = 'orm name';
		$test->content = 'orm content';
		$test->birthday = time();
		$test->sex = 1;

		$test->save();*/

/*		$result = TestModel::find(1);
		$result->name = 'update name orm';
		$result->save();*/
		
		$result = TestModel::where('sex', 2)->select(['name', 'sex', 'id'])
							->whereBetween('id', [4,10])->orderBy('sex', 'desc')->get();
		//$result = TestModel::find(1);
		foreach ($result as $key => $value) {
			$value->birthday = '19910429-';
			$value->save();
		}

		//$result->name = 'orm name save 2';
		//$result->save();
		echo '<pre>';print_r($result);exit;
		//$user = new UserModel();
/*
		$userInfo = $user->find(1);
		$data['user'] = $userInfo;

		$this->display('index-test', $data);*/
	}

	public function userList() {
		$user = $this->loadM('usermodel');

		$list = $user->get(['id' => ['>', 0]]);

		$data['list'] = $list;

		$this->display('index-list', $data);
	}

	public function getName() 
	{
		if (isset($_POST['sub-add-user'])) {
			$values = $_POST;

			$user = $this->loadM('usermodel');

			$result = $user->add($values);
			if ($result) {
				echo 'ok';
			}
		}
		$this->display('add-user', []);
	}

	public function testInsert() 
	{
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