<?php

//namespace tests\codeception\common\cms\models;

use yii\helpers\Html;
//use tests\codeception\common\UnitTester;
use common\modules\cms\models\EntryType;
use common\modules\cms\models\Field;
use common\modules\cms\models\Category;
use common\modules\cms\models\ContentData;

class CategoryCest {

    public function _before(UnitTester $I) {
        
    }

    public function _after(UnitTester $I) {
        
    }
	
	public function testCreateCategory(UnitTester $I)
    {
		// Prepare for test
		$entryType = $I->grabFixture('entryType')->getModel('category');
		
		// Test create category
		$category = new $entryType->content_type;
		$category->setEntryType($entryType->handle);
		$category->attributes = [
			'name' => 'new entry name',
		];
		if (!$category->save()) throw new \Exception(Html::errorSummary($category));
		
		// Test before and after load category
		$I->assertEquals($entryType->handle, $category->entryType->handle);
		$category = Category::findOne($category->id);
		$I->assertEquals($entryType->handle, $category->entryType->handle);
    }
	
	public function testInvalidCustomField(UnitTester $I)
    {
		// Prepare for test
		$entryType = $I->grabFixture('entryType')->getModel('category');
		
		// Test create category
		$category = new $entryType->content_type;
		$category->setEntryType($entryType->handle);
		$category->name = 'new category name';
		if (!$category->save()) throw new \Exception(Html::errorSummary($category));
		
		// Test before and after load category
		$I->assertFalse($category->hasCustomField('xxx'));
    }
	
	public function testCreateEntryWithFields(UnitTester $I) {
		// Prepare for test
		$value = 'Test value for field';
		
		$field = $I->grabFixture('field')->getModel('plainText');
		
		// Delete column if it exist to make sure when new EntryType is saved, it create the column.
		$existingColumns = \Yii::$app->db->schema->getTableSchema(ContentData::tableName(), true)->columns;
		if (isset($existingColumns[$field->handle])) {
			\Yii::$app->db->createCommand()->dropColumn(ContentData::tableName(), $field->handle)->execute();
		}
		
		$entryType = new EntryType;
		$entryType->name = 'Test Category Type 3';
		$entryType->handle = 'testEntryType3';
		$entryType->content_type = Category::className();
		$entryType->setFieldIds([$field->id]);
		
		if (!$entryType->save()) throw new \Exception(Html::errorSummary($entryType));
		
		// Test create category
		$category = new $entryType->content_type;
		$category->setEntryType($entryType->handle);
		$category->name = 'test category name';
		$category->{$field->handle} = $value;
		if (!$category->save()) throw new \Exception(Html::errorSummary($category));
		
		// Test before and after load entry
		//$I->assertTrue(isset($category->contentData));
		$I->assertEquals($entryType->handle, $category->entryType->handle);
		$I->assertTrue($category->hasCustomField($field->handle));
		$I->assertEquals($field->handle, $category->getCustomField($field->handle)->handle);
		$I->assertEquals($value, $category->{$field->handle});
		
		$category = Category::findOne($category->id);
		$I->assertTrue(isset($category->contentData));
		$I->assertEquals($entryType->handle, $category->entryType->handle);
		$I->assertTrue($category->hasCustomField($field->handle));
		$I->assertEquals($field->handle, $category->getCustomField($field->handle)->handle);
		$I->assertEquals($value, $category->{$field->handle});
	}
	
	public function testDeleteEntry(UnitTester $I) {
		
	}
	
	public function testFindByContentUid(UnitTester $I) {
		
	}
	
	public function testRelatedContent() {
		
	}
	
	/*
	// test that when expire date is set to empty string, it should be saved as null value into database
	public function testCreateEntryExpireDate(\Step\Unit\CmsEntry $I) {
		$type = $I->createContentType();
	
		$entry = new \Entry;
		if ($entry->hasAttribute('app_id')) {
			$entry->app_id = $this->testAppId;
		}
		$entry->setEntryTypeId($type->id);
		$entry->name = $this->testName;
		$entry->expire_date = ''; // HTML input will set to emptry string if use do not set date, the value save to database should be null
	
		$I->assertTrue($entry->save());
	
		// Assert expire_date of entry is null
		$criteria = new \CDbCriteria;
		$criteria->compare('t.id', $entry->id);
		$criteria->addCondition('t.expire_date IS NULL');
		
		$entry = \Entry::model()->find($criteria);
		
		$I->assertTrue(isset($entry));
	}
	
	public function testFieldIsset(\Step\Unit\CmsEntry $I)
    {
    	$fieldGroup = $I->createFieldGroup();
    	$field = $I->createCustomField($this->testFieldName, 'PlainText', $fieldGroup->id);
    	$type = $I->createContentType(array($field->id));
    
    	$entry = new \Entry;
    	$I->assertFalse(isset($entry->app_id)); // Should be before setEntryTypeId, this is to test to make sure if isset is called before setEntryTypeId won't cause exception.
    	$entry->setEntryTypeId($type->id);
    
    	$I->assertFalse(isset($entry->{$this->testFieldName}));
    	$entry->{$this->testFieldName} = $this->testCustomFieldValue;
    	$I->assertTrue(isset($entry->{$this->testFieldName}));
    
    }
	*/
	
	public function _fixtures()
    {
        return [
            'entryType' => [
                'class' => \tests\fixtures\EntryTypeFixture::className(),
                'dataFile' => '@tests/fixtures/data/entry_type.php',
            ],
            'field' => [
                'class' => \tests\fixtures\FieldFixture::className(),
                'dataFile' => '@tests/fixtures/data/field.php',
            ],
        ];
    }
     
}
