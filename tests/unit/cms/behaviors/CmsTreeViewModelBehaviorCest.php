<?php
//namespace tests\codeception\common\cms\behaviors;
use yii\helpers\Html;
//use tests\codeception\common\UnitTester;
use ant\cms\models\Category;

class CmsTreeViewModelBehaviorCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }
	
	public function _fixtures()
    {
        return [
			'structure' => [
                'class' => \tests\fixtures\StructureFixture::className(),
                'dataFile' => '@tests/fixtures/data/structure.php',
            ],
			'structure_content' => [
                'class' => \tests\fixtures\StructureContentFixture::className(),
                'dataFile' => '@tests/fixtures/data/structure_content.php',
            ],
            'entry_type' => [
                'class' => \tests\fixtures\EntryTypeFixture::className(),
                'dataFile' => '@tests/fixtures/data/entry_type.php',
            ],
            'field' => [
                'class' => \tests\fixtures\FieldFixture::className(),
                'dataFile' => '@tests/fixtures/data/field.php',
            ],
		];
	}

    // tests
    public function testMakeRoot(UnitTester $I)
    {
		$categoryEntryType = $I->grabFixture('entry_type')->getModel('category');
		$category = new Category;
		$category->setEntryType($categoryEntryType);
		$category->name = 'test category name';
		
		if (!$category->makeRoot()) throw new \Exception(Html::ErrorSummary($category->treeNode));
		
		if (!$category->save()) throw new \Exception(Html::errorSummary($category));
		
		$contentUid = $category->content_uid;
				
		$I->assertGreaterThan(0, $contentUid);
		$I->assertEquals($contentUid, $category->treeNode->content_uid);
		$I->assertEquals($category->treeNode->id, $category->treeNode->root);
		$I->assertEquals(1, $category->treeNode->left);
		$I->assertEquals(2, $category->treeNode->right);
		$I->assertEquals(0, $category->treeNode->level);
		
		$category = Category::findOne($category->id);
		$I->assertEquals($contentUid, $category->structureContent->content_uid);
		$I->assertEquals($category->structureContent->id, $category->structureContent->root);
		$I->assertEquals(1, $category->structureContent->left);
		$I->assertEquals(2, $category->structureContent->right);
		$I->assertEquals(0, $category->structureContent->level);
    }
	
    public function testAppendTo(UnitTester $I)
    {
		$categoryEntryType = $I->grabFixture('entry_type')->getModel('category');
		$category = new Category;
		$category->setEntryType($categoryEntryType);
		$category->name = 'test category name';
		
		if (!$category->makeRoot()) throw new \Exception(Html::errorSummary($category->treeNode));
		
		if (!$category->save()) throw new \Exception(Html::errorSummary($category));
		
		$child = new Category;
		$child->setEntryType($categoryEntryType);
		$child->name = 'child category name';
		
		if (!$child->appendTo($category)) throw new \Exception(Html::errorSummary($child->treeNode));
		
		if (!$child->save()) throw new \Exception(Html::errorSummary($child));
		
		// Test child
		$I->assertEquals($child->content_uid, $child->treeNode->content_uid);
		$I->assertEquals($category->treeNode->id, $child->treeNode->root);
		$I->assertEquals(2, $child->treeNode->left);
		$I->assertEquals(3, $child->treeNode->right);
		$I->assertEquals(1, $child->treeNode->level);
		
		$child = Category::findOne($child->id);
		$I->assertEquals($child->content_uid, $child->treeNode->content_uid);
		$I->assertEquals($category->treeNode->id, $child->treeNode->root);
		$I->assertEquals(2, $child->treeNode->left);
		$I->assertEquals(3, $child->treeNode->right);
		$I->assertEquals(1, $child->treeNode->level);
		
		// Test parent (root)
		$category = Category::findOne($category->id);
		$I->assertEquals($category->treeNode->id, $category->treeNode->root);
		$I->assertEquals(1, $category->treeNode->left);
		$I->assertEquals(4, $category->treeNode->right);
		$I->assertEquals(0, $category->treeNode->level);
    }
	
	public function testAppendTo2Level(UnitTester $I)
    {
		$categoryEntryType = $I->grabFixture('entry_type')->getModel('category');
		$category = new Category;
		$category->setEntryType($categoryEntryType);
		$category->name = 'test category name';
		
		if (!$category->makeRoot()) throw new \Exception(Html::errorSummary($category->treeNode));
		
		if (!$category->save()) throw new \Exception(Html::errorSummary($category));
		
		$child = new Category;
		$child->setEntryType($categoryEntryType);
		$child->name = 'child category name';
		
		if (!$child->appendTo($category)) throw new \Exception(Html::errorSummary($child->treeNode));
		
		if (!$child->save()) throw new \Exception(Html::errorSummary($child));
		
		$child2 = new Category;
		$child2->setEntryType($categoryEntryType);
		$child2->name = 'child 2 category name';
		
		if (!$child2->appendTo($child)) throw new \Exception(Html::errorSummary($child2->treeNode));
		
		if (!$child2->save()) throw new \Exception(Html::errorSummary($child2));
		
		// Test child
		$I->assertEquals($category->treeNode->id, $child->treeNode->root);
		$I->assertEquals(2, $child->treeNode->left);
		$I->assertEquals(3, $child->treeNode->right);
		$I->assertEquals(1, $child->treeNode->level);
		
		$child = Category::findOne($child->id);
		$I->assertEquals($category->treeNode->id, $child->treeNode->root);
		$I->assertEquals(2, $child->treeNode->left);
		$I->assertEquals(5, $child->treeNode->right);
		$I->assertEquals(1, $child->treeNode->level);
		
		$child2 = Category::findOne($child2->id);
		$I->assertEquals($category->treeNode->id, $child2->treeNode->root);
		$I->assertEquals(3, $child2->treeNode->left);
		$I->assertEquals(4, $child2->treeNode->right);
		$I->assertEquals(2, $child2->treeNode->level);
		
		// Test parent (root)
		$category = Category::findOne($category->id);
		$I->assertEquals($category->treeNode->id, $category->treeNode->root);
		$I->assertEquals(1, $category->treeNode->left);
		$I->assertEquals(6, $category->treeNode->right);
		$I->assertEquals(0, $category->treeNode->level);
    }
}
