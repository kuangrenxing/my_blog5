<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		//print_r($this->module);
		$this->render('index');
	}
}