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
			if($comment_count = Comment::add_vote($comment_id, $vote, Yii::app()->user->id)){				
				$arr_response['comment_vote'] = $comment_count;
				$arr_response['ok_message'] = 'Vote sent.';
			}else{
				$arr_response['error'] = 77401;
				$arr_response['error_message'] = "There is an error, we could not add this vote.";
			}
		}else{
			$arr_response['error'] = 77402;
			$arr_response['error_message'] = "Sorry, you must log in to be able to vote.";
		}	
	}


}
