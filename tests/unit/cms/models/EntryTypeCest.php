<?php

//namespace tests\codeception\common\cms\models;

use yii\helpers\Html;
//use tests\codeception\common\UnitTester;
use ant\cms\models\EntryType;
use ant\cms\models\Field;
use ant\cms\models\Entry;
use ant\cms\models\ContentData;

class EntryTypeCest {

    public function _before(UnitTester $I) {
        
    }

    public function _after(UnitTester $I) {
        
    }
	
	public function testCreateEntryType(UnitTester $I) {
		$field = $I->grabFixture('field')->getModel('plainText');
		
		// Delete column if it exist to make sure when new EntryType is saved, it create the column.
		$existingColumns = \Yii::$app->db->schema->getTableSchema(ContentData::tableName(), true)->columns;
		if (isset($existingColumns[$field->handle])) {
			\Yii::$app->db->createCommand()->dropColumn(ContentData::tableName(), $field->handle)->execute();
		}
		
		$entryType = new EntryType;
		$entryType->name = 'Test Entry Type 2';
		$entryType->handle = 'testEntryType2';
		$entryType->content_type = Entry::className();
		$entryType->setFieldIds([$field->id]);
		
		if (!$entryType->save()) throw new \Exception(Html::errorSummary($entryType));
		
		// Test before and after load entry type
		$I->assertTrue(current($entryType->getFields()) instanceof Field);
		
		$entryType = EntryType::findOne($entryType->id);
		$I->assertTrue(current($entryType->getFields()) instanceof Field);
	}
	
	/*
	
	public function testCreateWithEmptyArrayFields(\Step\Unit\CmsEntry $I) {
		$type = $I->createModel('\ContentType');
    	
    	$type->name = 'Test Entry Type '.uniqid();
    	$type->handle = uniqid('test_handle_');
    	$type->content_type = 'Entry';
		$type->fieldIds = array();
    	
    	$I->assertTrue($type->save());
	}
	
	// To test when ContentType getFields() will get latest field (not cached fields) afterSave
	public function testContentTypeGetFields(\Step\Unit\CmsEntry $I) {
		$type = $I->createModel('\ContentType');
    	
    	$type->name = 'Test Entry Type '.uniqid();
    	$type->handle = uniqid('test_handle_');
    	$type->content_type = 'Entry';
		
		$fields = array(
			'rich' => 'RichText',
			'textArea' => 'TextArea',
		);
		
		// Create custom fields
		$fields = $I->createCustomFields($fields);
		$fieldIds = array();
		foreach ($fields as $field) {
			$fieldIds[] = $field->id;
		}
		
		// Save fields
		$type->setFieldIds($fieldIds);

		$I->assertTrue($type->save());
		
		// Add one more field after save
		$fields = array(
			'rich2' => 'RichText',
		);
		
		$fields = $I->createCustomFields($fields);
		foreach ($fields as $field) {
			$fieldIds[] = $field->id;
		}
		
		$type->setFieldIds($fieldIds);
		
		sleep(1); // Without sleep, DbCacheDependency will have exactly same last_updated time as the time interval is too little.
		$I->assertTrue($type->save());
		
		// Assert if getFields return latest fields
		$returnFieldIds = array();
		foreach ($type->getFields() as $field) {
			$returnFieldIds[] = $field->field_id;
		}
		
		$I->assertEquals(count($fieldIds), count($returnFieldIds));
		
		for ($i = 0; $i < count($fieldIds); $i++) {
			$I->assertEquals($fieldIds[$i], $returnFieldIds[$i]);
		}
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