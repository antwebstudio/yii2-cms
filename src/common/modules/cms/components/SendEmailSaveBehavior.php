<?php
namespace common\modules\cms\components;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use Yii;


class SendEmailSaveBehavior extends Behavior{

	public function events()
	{
		return [
			ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert', 
		];
	}

	public function beforeInsert($event)
	{
		
	Yii::$app->mailer->compose()
    ->setFrom('nicholasemail6@gmail.com')
    ->setTo('chua.yikshuen@gmail.com')
    ->setSubject('Success')
    ->setTextBody('Success')
    ->setHtmlBody('<b>HTML content</b>')
    ->send();
	}
}
?>