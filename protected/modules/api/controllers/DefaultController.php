<?php

class DefaultController extends ApiController
{
	public function actionIndex()
	{
		$this->redirect('developers/');//This should do nothing but show developers documentation
		//$this->render('index');
	}

	

}
