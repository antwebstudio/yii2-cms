<?php
namespace ant\cms\components;

use yii\base\Component;
use yii\db\ActiveRecord;
use Yii;
use ant\cms\models\BehaviorClass;
use ant\cms\models\BehaviorAttachment;
use ant\cms\models\Behaviors;

class ControlDynamicBehavior{

	private $getClassName;
	private $getBehavior;
	private $behaviorClassName;
	private $behaviorName;
	 

	public static function attachDynamicBehavior($className,$object)
	{	 
		 $getClassName = BehaviorClass::find()->where(['class_name'=>$className])->one();
		if($getClassName != null)
		{
			$getBehavior = 	BehaviorAttachment::find()->where(['class_id'=>$getClassName->id])->all();
			if($getBehavior !=null)
			{	
				 
				foreach ($getBehavior as $key) {
					$behaviorClassName = Behaviors::find()->where(['id'=>$key->behavior_id])->one();
					$x=0;
					$storeData =$behaviorClassName->behavior_name;
					$behaviorName = 'dynamicBehavior'+ $x++;
					
					$object->attachBehavior($behaviorName, array('class' => $storeData::className()) + json_decode($key->behavior_setting,true)
						);
				}
			}
				
			
		}

		
	}

	
}
?>