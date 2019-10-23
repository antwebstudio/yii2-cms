<?php
//namespace tests\codeception\common\cms\fieldtypes;

use yii\helpers\Html;
//use tests\codeception\common\UnitTester;
use ant\cms\models\Field;
use ant\cms\models\EntryType;
use ant\cms\models\Entry;
use ant\cms\models\ContentData;
use ant\file\models\FileStorageItem;
use ant\cms\fieldtypes\ImageFieldType;
use Intervention\Image\ImageManagerStatic;

class ImageFieldTypeCest
{
	protected $_tempFiles = [];
	
    public function _before(UnitTester $I)
    {
		\Yii::configure(\Yii::$app, [
			'components' => [
				'fileStorage' => [
					'class' => '\trntv\filekit\Storage',
					'baseUrl' => '/source',
					'filesystem' => [
						'class' => 'ant\components\filesystem\LocalFlysystemBuilder',
						'path' => '@tests/_output'
					],
					'as log' => [
						'class' => 'ant\behaviors\FileStorageLogBehavior',
						'component' => 'fileStorage'
					]
				],
			]
		]);
    }

    public function _after(UnitTester $I)
    {
		foreach ($this->_tempFiles as $file) {
			if (file_exists($file)) unlink($file);
		}
    }

    // tests
    public function testCreateImageField(UnitTester $I)
    {
		$field = new Field([
			'name' => 'test field',
			'handle' => 'testField',
			'type' => ImageFieldType::className()
		]);
		if (!$field->save()) throw new \Exception(Html::errorSummary($field));
    }
	
	public function testBeforeContentValidate(UnitTester $I) {
		$field = $I->grabFixture('field')->getModel('image');
		//$category = $I->grabFixture('category')->getModel(0);
		
		$entryType = new EntryType([
			'name' => 'test entry type',
			'handle' => 'testEntryType',
			'content_type' => Entry::className(),
		]);
		$entryType->setFieldIds([$field->id]);
		
		if (!$entryType->save()) throw new \Exception(Html::errorSummary($entryType));
		
		$entry = new Entry([
			'entryType' => $entryType->handle,
		]);
		$entry->name = 'test entry';
		$path = $this->getImagePath();
		$image = $this->createImage($path);
		$this->_tempFiles[] = $path;
		
		// For real case, this is done by UploadAction
		$path = \Yii::$app->fileStorage->save($path);
		
		$entry->{$field->handle} = [
			'path' => $path,
		];
		
		if (!$entry->save()) throw new \Exception(Html::errorSummary($entry));
		
		$query = new \yii\db\Query;
		$query->select('*')->from(Entry::tableName().' e')
			->leftJoin(ContentData::tableName().' d', 'e.content_uid = d.id')
			->where(['e.id' => $entry->id]);
		
		$result = $query->one();
		
		$file = FileStorageItem::findOne(['path' => $path]);
		
		$I->assertEquals([$field->id => $file->id], json_decode($result['data'], true));
	}
	
	public function testAfterContentSave(UnitTester $I) {
		$field = $I->grabFixture('field')->getModel('image');
		//$category = $I->grabFixture('category')->getModel(0);
		
		$entryType = new EntryType([
			'name' => 'test entry type',
			'handle' => 'testEntryType',
			'content_type' => Entry::className(),
		]);
		$entryType->setFieldIds([$field->id]);
		
		if (!$entryType->save()) throw new \Exception(Html::errorSummary($entryType));
		
		$entry = new Entry([
			'entryType' => $entryType->handle,
		]);
		$entry->name = 'test entry';
		$path = $this->getImagePath();
		$image = $this->createImage($path);
		$this->_tempFiles[] = $path;
		
		// For real case, this is done by UploadAction
		$path = \Yii::$app->fileStorage->save($path);
		
		$entry->{$field->handle} = [
			'path' => $path,
		];
		
		if (!$entry->save()) throw new \Exception(Html::errorSummary($entry));
		
		$file = FileStorageItem::findOne(['path' => $path]);
		$fileParams = [
			'id' => $file->id, 
			'name' => $file->name, 
			'size' => $file->size, 
			'type' => $file->type, 
			'base_url' => $file->base_url, 
			'path' => $path,
		];
		
		$I->assertEquals($fileParams, $entry->{$field->handle});
		$I->assertTrue(isset($file));
	}
	
	public function testAfterContentFind(UnitTester $I) {
		$field = $I->grabFixture('field')->getModel('image');
		//$category = $I->grabFixture('category')->getModel(0);
		
		$entryType = new EntryType([
			'name' => 'test entry type',
			'handle' => 'testEntryType',
			'content_type' => Entry::className(),
		]);
		$entryType->setFieldIds([$field->id]);
		
		if (!$entryType->save()) throw new \Exception(Html::errorSummary($entryType));
		
		$entry = new Entry([
			'entryType' => $entryType->handle,
		]);
		$entry->name = 'test entry';
		$path = $this->getImagePath();
		$image = $this->createImage($path);
		$this->_tempFiles[] = $path;
		
		// For real case, this is done by UploadAction
		$path = \Yii::$app->fileStorage->save($path);
		
		$entry->{$field->handle} = [
			'path' => $path,
		];
		
		if (!$entry->save()) throw new \Exception(Html::errorSummary($entry));
		
		$file = FileStorageItem::findOne(['path' => $path]);
		$fileParams = [
			'id' => $file->id, 
			'name' => $file->name, 
			'size' => $file->size, 
			'type' => $file->type, 
			'base_url' => $file->base_url, 
			'path' => $path,
		];
		
		$entry = Entry::findOne($entry->id);
		$I->assertEquals($fileParams, $entry->{$field->handle});
	}
	
	public function testPrepareValue(UnitTester $I) {
		
	}
	
	/*
	public function testCategoryFieldTypeAsChildWithValue(UnitTester $I)
    {
		$categoryEntryType = $I->grabFixture('entryType')->getModel(0);
    	$category = $I->grabFixture('category')->getModel(0);
		
    	$category->setEntryType($categoryEntryType);
    	$category->name = 'testCategory';
    	$category->save();
    	$categoryContentId = $category->content_uid;
    	
    	$field = $I->grabFixture('category')->getModel(1);
		$matrixField = $I->createCustomField($this->testFieldName2, 'Matrix', null, array('fields' => array($field->id)));
		
		$type = $I->createContentType(array($matrixField->id));
		
		$type = \EntryType::model()->findByPk($type->id);
    	$model = $I->createModel('\Entry');
		$model->setEntryType($type);
		$model->{$this->testFieldName2} = array(array($field->handle => $categoryContentId), array($field->handle => $categoryContentId));
		$I->assertTrue($model->save());

		// Test if the relation is inserted by the category field
		$relations = \Relation::model()->findAllByAttributes(array(
				'field_id' => $field->id,
				'source_id' => $model->content_id,
				'target_id' => $categoryContentId,
		));
		$I->assertEquals(1, count($relations));
    }
	*/
	
	protected function getImagePath() {
		$path = \Yii::getAlias('@tests');
		return $path.'/test.jpg';
	}
	
	protected function createImage($path) {
		$image = ImageManagerStatic::canvas(100, 100);
		return $image->save($path);
	}
	
	public function _fixtures()
    {
        return [
            'entry_type' => [
                'class' => \tests\fixtures\EntryTypeFixture::className(),
                'dataFile' => '@tests/fixtures/data/entry_type.php',
            ],
            'field' => [
                'class' => \tests\fixtures\FieldFixture::className(),
                'dataFile' => '@tests/fixtures/data/field.php',
            ],
            'content_data' => [
                'class' => \tests\fixtures\ContentDataFixture::className(),
                'dataFile' => '@tests/fixtures/data/content_data.php',
            ],
            /*'category' => [
                'class' => \tests\fixtures\CategoryFixture::className(),
                'dataFile' => '@tests/fixtures/data/category.php',
            ],*/
			'relation' => [
                'class' => \tests\fixtures\RelationFixture::className(),
                'dataFile' => '@tests/fixtures/data/relation.php',
            ],
        ];
    }
}
