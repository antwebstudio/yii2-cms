<?php
//namespace tests\codeception\common\article;
//use tests\codeception\common\UnitTester;
use common\modules\article\models\Article;
use common\modules\article\models\ArticleSearch;

class ArticleSearchCest
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
            'category' => [
                'class' => \tests\fixtures\CategoryFixture::className(),
                'dataFile' => '@tests/fixtures/data/category.php'
            ],
            'category_map' => [
                'class' => \tests\fixtures\CategoryMapFixture::className(),
                'dataFile' => '@tests/fixtures/data/category_map.php'
            ],
            'article' => [
                'class' => \tests\fixtures\ArticleFixture::className(),
                'dataFile' => '@tests/fixtures/data/article.php'
            ],
        ];
    }

    public function testSearch(UnitTester $I)
    {
        $expectedCount = Article::find()->count();

        $article = new ArticleSearch;
        $dataProvider = $article->search([]);
		
		//throw new \Exception(print_r((new \yii\db\Query)->select('*')->from('category')->all(),1));
		//throw new \Exception(print_r((new \yii\db\Query)->select('*')->from('category_map')->all(),1));
		//throw new \Exception(print_r(Article::find()->joinWith('categories category')->asArray()->one(),1));
        $I->assertEquals($expectedCount, $dataProvider->totalCount);
    }

    public function testSearchByTitle(UnitTester $I)
    {
        $article = new ArticleSearch;
        $dataProvider = $article->search([
            (new ArticleSearch)->formName() => [
                'title' => 'first',
            ],
        ]);

        $I->assertEquals('first', $article->title);
        $I->assertEquals(1, $dataProvider->totalCount);
    }

    public function testSearchByBody(UnitTester $I)
    {
        $article = new ArticleSearch;
        $dataProvider = $article->search([
            (new ArticleSearch)->formName() => [
                'body' => 'first',
            ],
        ]);

        $I->assertEquals('first', $article->body);
        $I->assertEquals(1, $dataProvider->totalCount);
    }

    public function testSearchBySearchString(UnitTester $I)
    {
        $article = new ArticleSearch;
        $dataProvider = $article->search([
            (new ArticleSearch)->formName() => [
                'searchStringArticleIndex' => 'first',
            ],
        ]);

        $I->assertEquals('first', $article->searchStringArticleIndex);
        $I->assertEquals(1, $dataProvider->totalCount);
    }

    public function testSearchBySearchStringWithCategoryType(UnitTester $I)
    {
        $article = new ArticleSearch;
        $article->categoryType = 'article';
        $dataProvider = $article->search([
            (new ArticleSearch)->formName() => [
                'searchStringArticleIndex' => 'first',
            ],
        ]);

        $I->assertEquals('first', $article->searchStringArticleIndex);
        $I->assertEquals(1, $dataProvider->totalCount);
    }

    // Result: 0

    public function testSearchNoneByTitle(UnitTester $I)
    {
        $article = new ArticleSearch;
        $dataProvider = $article->search([
            (new ArticleSearch)->formName() => [
                'title' => 'kajslkdfjlkasdf',
            ],
        ]);

        $I->assertEquals('kajslkdfjlkasdf', $article->title);
        $I->assertEquals(0, $dataProvider->totalCount);
    }

    public function testSearchNoneByBody(UnitTester $I)
    {
        $article = new ArticleSearch;
        $dataProvider = $article->search([
            (new ArticleSearch)->formName() => [
                'body' => 'kajslkdfjlkasdf',
            ],
        ]);

        $I->assertEquals('kajslkdfjlkasdf', $article->body);
        $I->assertEquals(0, $dataProvider->totalCount);
    }

    public function testSearchNoneBySearchString(UnitTester $I)
    {
        $article = new ArticleSearch;
        $dataProvider = $article->search([
            (new ArticleSearch)->formName() => [
                'searchStringArticleIndex' => 'kajslkdfjlkasdf',
            ],
        ]);

        $I->assertEquals('kajslkdfjlkasdf', $article->searchStringArticleIndex);
        $I->assertEquals(0, $dataProvider->totalCount);
    }

    public function testSearchNoneBySearchStringWithCategoryType(UnitTester $I)
    {
        $article = new ArticleSearch;
        $article->categoryType = 'article';
        $dataProvider = $article->search([
            (new ArticleSearch)->formName() => [
                'searchStringArticleIndex' => 'kajslkdfjlkasdf',
            ],
        ]);

        $I->assertEquals('kajslkdfjlkasdf', $article->searchStringArticleIndex);
        $I->assertEquals(0, $dataProvider->totalCount);
    }
}
