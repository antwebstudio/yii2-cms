<?php

//namespace tests\codeception\common\cms\models;

use yii\helpers\Html;
//use tests\codeception\common\UnitTester;
use ant\cms\models\EntryType;
use ant\cms\models\Field;
use ant\cms\models\Entry;
use ant\cms\models\ContentData;

class EntryCest {

    public function _before(UnitTester $I) {
        
    }

    public function _after(UnitTester $I) {
        
    }
	
	public function testCreateEntry(UnitTester $I)
    {
		// Prepare for test
		$entryType = $I->grabFixture('entryType')->getModel('entry');
		
		// Test create entry
		$entry = new $entryType->content_type;
		$entry->setEntryType($entryType->handle);
		$entry->attributes = [
			'name' => 'new entry name',
		];
		if (!$entry->save()) throw new \Exception(Html::errorSummary($entry));
		
		// Test before and after load entry
		$I->assertEquals($entryType->handle, $entry->entryType->handle);
		$entry = Entry::findOne($entry->id);
		$I->assertEquals($entryType->handle, $entry->entryType->handle);
    }
	
	public function testInvalidCustomField(UnitTester $I)
    {
		// Prepare for test
		$entryType = $I->grabFixture('entryType')->getModel('entry');
		
		// Test create entry
		$entry = new $entryType->content_type;
		$entry->setEntryType($entryType->handle);
		$entry->name = 'new entry name';
		if (!$entry->save()) throw new \Exception(Html::errorSummary($entry));
		
		// Test before and after load entry
		$I->assertFalse($entry->hasCustomField('xxx'));
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
		$entryType->name = 'Test Entry Type 3';
		$entryType->handle = 'testEntryType3';
		$entryType->content_type = Entry::className();
		$entryType->setFieldIds([$field->id]);
		
		if (!$entryType->save()) throw new \Exception(Html::errorSummary($entryType));
		
		// Test create entry
		$entry = new $entryType->content_type;
		$entry->setEntryType($entryType->handle);
		$entry->name = 'test entry name';
		$entry->{$field->handle} = $value;
		if (!$entry->save()) throw new \Exception(Html::errorSummary($entry));
		
		// Test before and after load entry
		//$I->assertTrue(isset($entry->contentData));
		$I->assertEquals($entryType->handle, $entry->entryType->handle);
		$I->assertTrue($entry->hasCustomField($field->handle));
		$I->assertEquals($field->handle, $entry->getCustomField($field->handle)->handle);
		$I->assertEquals($value, $entry->{$field->handle});
		
		$entry = Entry::findOne($entry->id);
		$I->assertTrue(isset($entry->contentData));
		$I->assertEquals($entryType->handle, $entry->entryType->handle);
		$I->assertTrue($entry->hasCustomField($field->handle));
		$I->assertEquals($field->handle, $entry->getCustomField($field->handle)->handle);
		$I->assertEquals($value, $entry->{$field->handle});
	}
	
	public function testDeleteEntry(UnitTester $I) {
		// Prepare for test
		$entryType = $I->grabFixture('entryType')->getModel('entry');
		
		// Test create entry
		$entry = new $entryType->content_type;
		$entry->setEntryType($entryType->handle);
		$entry->name = 'new entry name';
		if (!$entry->save()) throw new \Exception(Html::errorSummary($entry));
		
		$contentUid = $entry->content_uid;
		$id = $entry->id;
		
		$I->assertEquals(1, $entry->delete());
		
		$content = ContentData::findOne($contentUid);
		$entry = Entry::findOne($id);
		
		$I->assertFalse(isset($content));
		$I->assertFalse(isset($entry));
	}
	
	public function testFindByContentUid(UnitTester $I) {
		$entryType = $I->grabFixture('entryType')->getModel('entry');
		
		// Test create entry
		$entry = new $entryType->content_type;
		$entry->setEntryType($entryType->handle);
		$entry->name = 'new entry name';
		if (!$entry->save()) throw new \Exception(Html::errorSummary($entry));
		
		$id = $entry->id;
		
		$entry = Entry::findOne(['content_uid' => $entry->content_uid]);
		
		$I->assertTrue(isset($entry));
		$I->assertEquals($id, $entry->id);
		
		$entry = ContentData::findOne($entry->content_uid)->entry;
		
		$I->assertTrue(isset($entry));
		$I->assertEquals($id, $entry->id);
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
			'entry' => [
                'class' => \tests\fixtures\EntryFixture::className(),
                'dataFile' => '@tests/fixtures/data/entry.php',
            ],
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
