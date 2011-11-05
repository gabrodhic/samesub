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

class ETextImageAction extends CAction
{
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
	public $fontFile='arial';

	public function run()
	{
	        $this->transparent=$_GET['transparent'];

                $backColor=$_GET['backColor'];
	        $this->backColor=(!isset($backColor))?$this->backColor:$backColor;
	        
                $foreColor=$_GET['foreColor'];
	        $this->foreColor=(!isset($foreColor))?$this->foreColor:$foreColor;

	        $fontSize=$_GET['fontSize'];
	        $this->fontSize=(!isset($fontSize) or empty($fontSize))?$this->fontSize:$fontSize;
	        
                $this->textImage=$_GET['textImage'];
                $this->textImage = (!isset($this->textImage) or empty($this->textImage))?'CTextImage':urldecode($this->textImage);

                $fontFile=$_GET['fontFile'];
                $fontFile = (!isset($fontFile) or empty($fontFile))?$this->fontFile:$fontFile;
                $fontFile = dirname(__FILE__).DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.$fontFile.'.ttf';
                $this->fontFile=$fontFile;
                if(!file_exists($this->fontFile))
                {
                        throw new CException('ETextImage: Font "'.$this->fontFile.'" not found.');
                        Yii::app()->end;
                }
                
                $box = $this->calculateTextBox($this->textImage,$this->fontFile,$this->fontSize,0);
                $im=imagecreatetruecolor($box['width'],$box['height']);

		$backColor=imagecolorallocate($im,
			(int)($this->backColor%0x1000000/0x10000),
			(int)($this->backColor%0x10000/0x100),
			$this->backColor%0x100);

                imagefilledrectangle($im, 0, 0, $box['width'], $box['height'], $backColor);
                if($this->transparent) imagecolortransparent($im,$backColor);

		$foreColor=imagecolorallocate($im,
			(int)($this->foreColor%0x1000000/0x10000),
			(int)($this->foreColor%0x10000/0x100),
			$this->foreColor%0x100);

                imagettftext($im, $this->fontSize, 0, 0, $this->fontSize, $foreColor, $this->fontFile, $this->textImage);
                // Output to browser
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Transfer-Encoding: binary');
		header("Content-type: image/png");
                imagepng($im);
                imagedestroy($im);
		Yii::app()->end();
	}
	
        protected function calculateTextBox($text, $font_file, $font_size, $font_angle)
        {
            $box = imagettfbbox($font_size, $font_angle, $font_file, $text);

            $min_x = min(array($box[0], $box[2], $box[4], $box[6]));
            $max_x = max(array($box[0], $box[2], $box[4], $box[6]));
            $min_y = min(array($box[1], $box[3], $box[5], $box[7]));
            $max_y = max(array($box[1], $box[3], $box[5], $box[7]));

            return array(
                'left' => ($min_x >= -1) ? -abs($min_x + 1) : abs($min_x + 2),
                'top' => abs($min_y),
                'width' => $max_x - $min_x,
                'height' => $max_y - $min_y,
                'box' => $box
            );
        }

}
