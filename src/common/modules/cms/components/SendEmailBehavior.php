<?php
namespace common\modules\cms\components;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use Yii;
use common\models\User;



class SendEmailBehavior extends Behavior{

	public $toEmail;
	public $template;
	public $subject;

	public function events()
	{
		return [
			ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert', 
		];
	}

	public function afterInsert($event)
	{
	Yii::$app->mailer->compose($this->template)
    ->setFrom('nicholasemail6@gmail.com')
    ->setTo($this->toEmail)
    ->setSubject($this->subject)
    ->send();
	}

}
?>