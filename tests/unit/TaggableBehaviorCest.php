<?php

use common\modules\tag\models\Tag;

class TaggableBehaviorCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    // tests
    public function testAssignTag(UnitTester $I)
    {
		$model = new Organizer([
			'as taggable' => [
				'class' => 'common\modules\tag\behaviors\TaggableBehavior',
				'attribute' => 'myTags',
				'modelClassId' => \common\models\ModelClass::getClassId(Organizer::class),
			],
		]);
		
		$model->myTags = ['abc'];
		if (!$model->save()) throw new \Exception(print_r($model->errors, 1));
		
		$I->assertEquals(1, count($model->tags));
		
		$model->myTags = ['abc', 'def'];
		if (!$model->save()) throw new \Exception(print_r($model->errors, 1));
		
		$I->assertEquals(2, count($model->tags));
		
		$model = Organizer::findOne($model->id);

		$I->assertEquals(2, count($model->tags));
    }
	
    public function testMultipleAttributeAssignTag(UnitTester $I)
    {
		$model = new Organizer([
			'as taggable' => [
				'class' => 'common\modules\tag\behaviors\TaggableBehavior',
				'attribute' => 'firstTags',
				'modelClassId' => \common\models\ModelClass::getClassId(Organizer::class),
			],
			'as taggable2' => [
				'class' => 'common\modules\tag\behaviors\TaggableBehavior',
				'attribute' => 'secondTags',
				'relation' => 'secondTags',
				'modelClassId' => \common\models\ModelClass::getClassId((Organizer::class).':second'),
			],
		]);
		
		$model->firstTags = ['abc'];
		if (!$model->save()) throw new \Exception(print_r($model->errors, 1));
		
		$model->secondTags = ['abc', 'def'];
		if (!$model->save()) throw new \Exception(print_r($model->errors, 1));
		
		$I->assertEquals(1, count($model->tags));
		$I->assertEquals(2, count($model->secondTags));
		
		$model = Organizer::findOne($model->id);
		
		//throw new \Exception("\n".\common\models\ModelClass::getClassId((Organizer::class).':second').$this->displayTable('{{%model_class}}').$this->displayTable('{{%tag}}').$this->displayTable('{{%tag_map}}'));
		
		$I->assertEquals(1, count($model->tags));
		$I->assertEquals(2, count($model->secondTags));
    }
	
	protected function displayTable($tableName) {
		$data = (new \yii\db\Query)->select('*')->from($tableName)->all();
		return $tableName."\n".\yii\console\widgets\Table::widget([
			'headers' => array_keys($data[0]),
			'rows' => $data,
		])."\n";
	}
}

class Organizer extends \yii\db\ActiveRecord {
	//public $secondTags;
	public $firstTags;
	
	public static function tableName() {
		return '{{%test}}';
	}
	
	public function getTags()
	{
		return $this->getBehaviorRelation(\common\models\ModelClass::getClassId((self::class)));
	}
	
	public function getSecondTags()
	{
		return $this->getBehaviorRelation(\common\models\ModelClass::getClassId((self::class).':second'));
	}

	public function getBehaviorRelation($modelClassId) {
		return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
			->onCondition(['model_class_id' => $modelClassId])
			->viaTable('{{%tag_map}}', ['model_id' => 'id']);
	}
	
	public function rules() {
		return [
			[['name'], 'safe'],
			[['name'], 'default', 'value' => 'test'],
		];
	}
	
}