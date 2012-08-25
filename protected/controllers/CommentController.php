<?php

class CommentController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
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
				'actions'=>array('index','push','vote'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}



	public function actionIndex($id=0)
	{
		echo $id;//$this->render('index');
	}
	
	public function actionPush($text="")
	{
		$this->model=new Comment;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($this->model);

		if(isset($_POST['Comment']) or $text or $_POST['text'])
		{
			if(isset($_POST['Comment'])) $this->model->attributes=$_POST['Comment'];
			if($text) $this->model->comment = $text;
			if($_POST['text']) $this->model->comment = $_POST['text'];
			$this->model->update_live = true;

			Comment::save_comment($this->model);
			//we need to jsonecode something, as not doing so, can provoke an 
			//ajax response error on the client site if the ajax request is of type json
			echo json_encode(array('success'=>'yes'));
			return;
		}

		$this->render('add',array(
			'model'=>$this->model,
		));
	}
	
	
	/**
	 * Adds one vote to the comment votes count, a like or dislike.
	 * @param integer $comment_id the ID of the comment
	 * @param string $vote the vote: either like or dislike
	 */
	public function actionVote($comment_id, $vote="")
	{
		
		
		
		if(! Yii::app()->user->isGuest){
			if($comment_count = Comment::add_vote($comment_id, $vote, Yii::app()->user->id)){				
				echo json_encode($comment_count);
			}else{
				echo json_encode(array('comment_id'=>0));
			}
		}else{
			echo json_encode(array('comment_id'=>0));
		}			
	}
	
}