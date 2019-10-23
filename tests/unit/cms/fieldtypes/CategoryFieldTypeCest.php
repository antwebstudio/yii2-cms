<?php
//namespace tests\codeception\common\cms\fieldtypes;

use yii\helpers\Html;
//use tests\codeception\common\UnitTester;
use ant\cms\models\Field;
use ant\cms\models\EntryType;
use ant\cms\models\Entry;
use ant\cms\models\Relation;
use ant\cms\models\Category;
use ant\cms\fieldtypes\CategoryFieldType;

class CategoryFieldTypeCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    // tests
    public function testCreateCategoryField(UnitTester $I)
    {
		$field = new Field([
			'name' => 'test field',
			'handle' => 'testField',
			'type' => CategoryFieldType::className()
		]);
		if (!$field->save()) throw new \Exception(Html::errorSummary($field));
    }
	
	public function testOnAfterContentSave(UnitTester $I) {
		$field = $I->grabFixture('field')->getModel('category');
		$category = $I->grabFixture('category')->getModel(0);
		
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
		$entry->{$field->handle} = [$category->id];
		
		if (!$entry->save()) throw new \Exception(Html::errorSummary($entry));
		
		$relations = Relation::findAll([
			'field_id' => $field->id,
			'source_id' => $entry->content_uid,
			'target_id' => $category->content_uid,
		]);
		
		//throw new \Exception($field->id.' : '.$entry->content_uid.' : '.$category->content_uid);
		
		$I->assertEquals(1, count($relations));
	}
	
	public function testOnAfterContentFind(UnitTester $I) {
		$field = $I->grabFixture('field')->getModel('category');
		$category = $I->grabFixture('category')->getModel(0);
		
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
		$entry->{$field->handle} = [$category->id];
		
		$I->assertEquals([$category->id], $entry->{$field->handle});
		
		if (!$entry->save()) throw new \Exception(Html::errorSummary($entry));
		
		$entry = Entry::findOne($entry->id);
		
		$I->assertEquals([$category->id], $entry->{$field->handle});
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
            'category' => [
                'class' => \tests\fixtures\CmsCategoryFixture::className(),
                'dataFile' => '@tests/fixtures/data/cms_category.php',
            ],
			'relation' => [
                'class' => \tests\fixtures\RelationFixture::className(),
                'dataFile' => '@tests/fixtures/data/relation.php',
            ],
        ];
    }
}
