<?php

class indexController extends Controller {
	
	public function index() {
		$user = $this->loadM('usermodel');

		$userInfo = $user->find(1);
		$data['user'] = $userInfo;

		$this->display('index-test', $data);
	}

	public function getName() 
	{
		echo 'get name page';
	}
}

