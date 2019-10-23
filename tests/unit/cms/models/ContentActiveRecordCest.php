<?php
//namespace tests\codeception\common\cms\models;

//use tests\codeception\common\UnitTester;
use ant\cms\models\EntryType;
use ant\cms\models\Field;
use ant\cms\models\Entry;
use ant\cms\models\Category;

class ContentActiveRecordCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }
	
	public function testToCreateAndLoadEntry(UnitTester $I)
    {
		// Prepare for test
		$entryType = new EntryType;
		$entryType->name = 'Test Entry Type';
		$entryType->handle = 'testEntryType';
		$entryType->content_type = Entry::className();
		if (!$entryType->save()) throw new \Exception(Html::errorSummary($entryType));
		
		// Test create entry
		$entry = new $entryType->content_type;
		$entry->setEntryType($entryType->handle);
		$entry->name = 'new entry name';
		if (!$entry->save()) throw new \Exception(Html::errorSummary($entry));
		
		// Test load entry
		$entry = Entry::findOne($entry->id);
		$I->assertEquals($entryType->handle, $entry->entryType->handle);
    }
	
	public function testToCreateAndLoadEntryWithFields(UnitTester $I) {
		// Prepare for test
		$value = 'Test value for field';
		
		$field = new Field;
		$field->name = 'Test Field';
		$field->handle = 'testField';
		$field->type = 'PlainText';
		$field->save();
		
		$entryType = new EntryType;
		$entryType->name = 'Test Entry Type 2';
		$entryType->handle = 'testEntryType2';
		$entryType->content_type = Entry::className();
		$entryType->setFieldIds(array($field->id));
		if (!$entryType->save()) throw new \Exception(Html::errorSummary($entryType));
		
		// Test create entry
		$entry = new $entryType->content_type;
		$entry->setEntryType($entryType->handle);
		$entry->name = 'test entry name';
		$entry->{$field->handle} = $value;
		if (!$entry->save()) throw new \Exception(Html::errorSummary($entry));
		
		// Test load entry
		$entry = Entry::findOne($entry->id);
		$I->assertEquals($entryType->handle, $entry->entryType->handle);
		$I->assertEquals($value, $entry->{$field->handle});
	}
	
	public function testGetRelatedToContent(UnitTester $I) {
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
		
		$searchedContentData = $entry->getContentData()->one()->getRelatedToContentData()->one();
		$I->assertTrue(isset($searchedContentData));
		$I->assertEquals($category->content_uid, $searchedContentData->id);
		
		$searched = $entry->getContentData()->one()->getRelatedToContentData()->one()->getEntry()->one();
		$I->assertEquals($category->id, $searched->id);
		$I->assertTrue($searched instanceof Category);
		/*
		$searched = $entry->getRelatedToContentData()->one()->getEntry()->one();
		$I->assertEquals($category->id, $searched->id);
		$I->assertTrue($searched instanceof Category);*/
		
		$searched = $entry->getRelatedTo(Category::className())->one();
		$I->assertEquals($category->id, $searched->id);
		$I->assertTrue($searched instanceof Category);
	}
	
	public function testGetRelatedFromContent(UnitTester $I) {
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
		
		$searchedContentData = $category->getContentData()->one()->getRelatedFromContentData()->one();
		$I->assertTrue(isset($searchedContentData));
		$I->assertEquals($entry->content_uid, $searchedContentData->id);
		
		$searched = $category->getContentData()->one()->getRelatedFromContentData()->one()->getEntry()->one();
		$I->assertEquals($entry->id, $searched->id);
		$I->assertTrue($searched instanceof Entry);
		
		$searched = $category->getRelatedFrom(Entry::className())->one();
		$I->assertEquals($entry->id, $searched->id);
		$I->assertTrue($searched instanceof Entry);
	}
	
	public function testGetRelatedFromContentWithSpecifiedEntryType(UnitTester $I) {
		
		// Prepare for test
		$field = $I->grabFixture('field')->getModel('category');
		$category = $I->grabFixture('category')->getModel(0);
		
		$entryType = new EntryType([
			'name' => 'test entry type',
			'handle' => 'testEntryType',
			'content_type' => Entry::className(),
		]);
		$entryType->setFieldIds([$field->id]);
		
		if (!$entryType->save()) throw new \Exception(Html::errorSummary($entryType));
		
		$entryType2 = new EntryType([
			'name' => 'test entry type 2',
			'handle' => 'testEntryType2',
			'content_type' => Entry::className(),
		]);
		$entryType2->setFieldIds([$field->id]);
		
		if (!$entryType2->save()) throw new \Exception(Html::errorSummary($entryType2));
		
		$entry = new Entry([
			'entryType' => $entryType->handle,
		]);
		$entry->name = 'test entry';
		$entry->{$field->handle} = [$category->id];
		
		if (!$entry->save()) throw new \Exception(Html::errorSummary($entry));
		
		$entry2 = new Entry([
			'entryType' => $entryType2->handle,
		]);
		$entry2->name = 'test entry 2';
		$entry2->{$field->handle} = [$category->id];
		
		if (!$entry2->save()) throw new \Exception(Html::errorSummary($entry2));
		
		// Performing test
		$searched = $category->getRelatedFrom($entryType2)->all();
		$I->assertEquals(1, count($searched));
		$I->assertEquals($entry2->id, $searched[0]->id);
		$I->assertTrue($searched[0] instanceof Entry);
	}
	
	public function testGetRelatedToContentWithSpecifiedEntryType(UnitTester $I) {
		$field = $I->grabFixture('field')->getModel('category');
		$category = $I->grabFixture('category')->getModel(0);
		
		$categoryEntryType = new EntryType([
			'name' => 'test category type',
			'handle' => 'testCategoryEntryType',
			'content_type' => Category::className(),
		]); 
		if (!$categoryEntryType->save()) throw new \Exception(Html::errorSummary($categoryEntryType));
		
		$category = new Category([
			'entryType' => $categoryEntryType->handle,
		]);
		$category->name = 'test category';
		
		if (!$category->save()) throw new \Exception(Html::errorSummary($category));
		
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
		
		$searched = $entry->getRelatedTo($categoryEntryType->handle)->one();
		$I->assertTrue(isset($searched)); // Refer: https://github.com/yiisoft/yii2/issues/5341
		$I->assertEquals($category->id, $searched->id);
		$I->assertTrue($searched instanceof Category);
	}
	
	public function testGetRelatedFromContentWithSpecifiedField(UnitTester $I) {
		
	}
	
	public function _fixtures()
    {
        return [
			'structure' => [
                'class' => \tests\fixtures\StructureFixture::className(),
                'dataFile' => '@tests/fixtures/data/structure.php',
            ],
			'entry' => [
                'class' => \tests\fixtures\EntryFixture::className(),
                'dataFile' => '@tests/fixtures/data/entry.php',
            ],
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
        ];
    }
}
