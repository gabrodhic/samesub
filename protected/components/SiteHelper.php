<?php
/**
 * SiteHelper is a helper class for the site.
 * It contatins methods that provide easier and reusable code to print custom elements in views.
 */
class SiteHelper extends CHtml
{
	
	/**
	 * Generates the proper html depending on the content_type_id
	 * @param object $subject the an instance of the Subject class
	 * @return string the html content
	 */
	public function subject_content($subject)
	{
		switch ($subject->content_type_id) {
			case 1:
				$html = '<img src="'.Yii::app()->getRequest()->getBaseUrl(true).'/'.$subject->content_image->path.'/'.$subject->content_image->id.'.'.$subject->content_image->extension.'" width="100%">';//Yii::app()->getRequest()->getHostInfo().
				break;
			case 2:
				$html = $subject->content_text->text;
				break;
			case 3:
				$html = $subject->content_video->embed_code;
				break;
		}
		return $html;
	}
	
	/**
	 * Generates a yes or no depending on the boolean value
	 * @param boolean $value the value
	 * @return string the correspondent text
	 */
	public function yesno($value)
	{
		return ($value) ? 'Yes' : 'No';
	}
	
	/**
	 * Generates a on or off depending on the boolean value
	 * @param boolean $value the value
	 * @return string the correspondent text
	 */
	public function onoff($value)
	{
		return ($value) ? 'On' : 'Off';
	}
	
}