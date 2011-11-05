<?php
/**
*       ETextImage - This widget allows you to display the text as an image.
*
*       @author Vladimir Papaev <kosenka@gmail.com>
*       @version 0.1
*
*       usage:
*
*       1) Override CController::actions() and register an action of class ETextImageAction with ID 'textImage':
*	public function actions()
*	{
*		return array(
*			'textImage'=>array(
*				'class'=>'application.extensions.ETextImage.ETextImageAction',
*			),
*		);
*	}
*
*       2) In the controller view, insert a widget in the form.
*       $this->widget('application.extensions.ETextImage.ETextImage',
*                        array(
*                              'textImage' => "(495)1234567",
*                              'fontSize' => 10,
*                              'fontFile' => 'tahoma.ttf',
*                              'transparent'=>false,
*                              'foreColor'=>0x2040A0,
*                              'backColor'=>0x55FF00,
*                             )
*                       );
*
**/

class ETextImage extends CWidget
{
	public $thisAction='textImage';

	/**
	 * @var string
	 */
        public $textImage;

	/**
	 * @var integer the font size. Default to 8.
	 */
        public $fontSize=8;

	/**
	 * @var integer the background color. For example, 0x55FF00.
	 * Defaults to 0xFFFFFF, meaning white color.
	 */
	public $backColor=0xFFFFFF;

	/**
	 * @var integer the font color. For example, 0x55FF00. Defaults to 0x000000 (black color).
	 */
	public $foreColor=0x000000;

	/**
	 * @var boolean whether to use transparent background. Defaults to false.
	 */
	public $transparent=false;

	/**
	 * @var Font filename. Defaults to 'arial.ttf'.
	 */
	public $fontFile;

	/**
	 * Renders the widget.
	 */
	/**
 * Renders the widget.
 */
	public function run()
	{
		$this->textImage=urlencode($this->textImage);

		$base = $this->getController()->getId().'/'.$this->thisAction;
		$params = array( 'textImage'=>$this->textImage,
						 'fontSize'=>$this->fontSize,
						 'fontFile'=>$this->fontFile,
						 'backColor'=>$this->backColor,
						 'foreColor'=>$this->foreColor,
						 'transparent'=>$this->transparent,
						 'v'=>rand(0,10000)
				);

		echo CHtml::image( Yii::app()->createUrl( $base, $params )   ,'');
	}
}
