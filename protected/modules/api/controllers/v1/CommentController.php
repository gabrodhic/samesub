<?php

class CommentController extends ApiController
{
	
	//Notice that default filters are defined in ApiController class

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', 
				'actions'=>array('vote'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Adds one vote to the comment votes count, a like or dislike.
	 * @param integer $comment_id the ID of the comment
	 * @param string $vote the vote: either like or dislike
	 */
	public function actionVote($comment_id, $vote="")
	{
		global $arr_response;
		if(! Yii::app()->user->isGuest){
			$comment_count = Comment::add_vote($comment_id, $vote, Yii::app()->user->id);
			if($comment_count['success']==true){
				$arr_response['comment_vote'] = $comment_count;
				$arr_response['response_message'] = Yii::t('comment','Vote sent.');
			}else{
				$arr_response['response_code'] = 409;
				$arr_response['response_message'] = $comment_count['message'];
			}
		}else{
			$arr_response['response_code'] = 401;
			$arr_response['response_message'] = Yii::t('comment','Sorry, you must log in to be able to vote.');
		}	
	}


}
