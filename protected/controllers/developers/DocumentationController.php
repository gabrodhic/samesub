<?php

class DocumentationController extends Controller
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
		);
	}



	public function filterMenu($filterChain){
		//.$this->action->Id
		$arr_titles = array();
		$arr_titles['index'] = 'Home';
		$arr_titles['quickguide'] = 'Quick Guide';
		$arr_titles['api'] = 'API';
		$arr_titles['authentication'] = 'Authentication';

		$this->breadcrumbs=array(
		'Developers'=>array('developers/'),
		'Documentation'=>array('index'),
		$arr_titles[$this->action->Id] ,
		);

		$this->menu=array(
		array('label'=>$arr_titles['index'], 'url'=>array('developers/documentation')),//Note:we have to indicate the controller also, for the CMenu widget activateItems propoerty work properly
		array('label'=>$arr_titles['quickguide'], 'url'=>array('developers/documentation/quickguide')),
		array('label'=>$arr_titles['api'], 'url'=>array('developers/documentation/api')),
		array('label'=>$arr_titles['authentication'], 'url'=>array('developers/documentation/authentication')),
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
	public function actionQuickguide()
	{
		//Import the php class GeSHi syntax highlighter 
		Yii::import('application.vendors.*');
		require_once('geshi/geshi.php');
		$this->render('quickguide');
	}
	public function actionApi()
	{
		$this->render('api');
	}
	public function actionAuthentication()
	{
		$this->render('authentication');
	}

}
