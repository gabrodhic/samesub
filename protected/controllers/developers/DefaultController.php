<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'Menu',
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', 
				'actions'=>array('index','contact'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function filterMenu($filterChain){
		//.$this->action->Id
		$arr_titles = array();
		$arr_titles['index'] = 'Home';
		$arr_titles['quickguide'] = 'Quick Guide';
		$arr_titles['documentation'] = 'API Documentation';
		$arr_titles['faq'] = 'FAQ';
		$arr_titles['blog'] = 'Blog';
		$arr_titles['community'] = 'Community';	
		$arr_titles['source'] = 'Source / Version Control';
		$arr_titles['issue'] = 'Issue Tracker';
		$arr_titles['terms'] = 'API Terms Of Use';
		$arr_titles['contact'] = 'Contact Us';
		/*$this->breadcrumbs=array(
		Yii::t('site','User')=>array('index'),
		$arr_titles[$this->action->Id] ,
		);*/

		$this->menu=array(
		array('label'=>$arr_titles['index'], 'url'=>array('developers/')),//Note:we have to indicate the controller also, for the CMenu widget activateItems propoerty work properly
		/*
		array('label'=>$arr_titles['faq'], 'url'=>array('developers/faq')),
		array('label'=>$arr_titles['blog'], 'url'=>array('developers/blog')),
		array('label'=>$arr_titles['community'], 'url'=>array('developers/community')),
		array('label'=>$arr_titles['source'], 'url'=>array('developers/source')),
		array('label'=>$arr_titles['issue'], 'url'=>array('developers/issue')),
		array('label'=>$arr_titles['terms'], 'url'=>array('developers/terms')),
		*/
		array('label'=>$arr_titles['contact'], 'url'=>array('developers/default/contact')),
		);
		SiteLibrary::remove_current_url_menu($this,$arr_titles);

		$filterChain->run();
	
	}
	
	
	/**
	 * Lists all options.
	 */
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionContact()
	{
		$this->render('contact');
	}


}
