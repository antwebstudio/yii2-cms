<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/4/14
 * Time: 2:31 PM
 */

namespace ant\article\models\query;

use ant\models\Article;
use yii\db\ActiveQuery;
class ArticleQuery extends ActiveQuery
{
    public function behaviors() {
		return [
			[
				'class' => 'ant\category\behaviors\CategorizableQueryBehavior',
			],
		];
	}

	public function category($categoryId) {
		if (YII_DEBUG) throw new \Exception('DEPRECATED');
		
		$this->andWhere(['category_id' => $categoryId]);
		return $this;
	}
	
    public function published()
    {
        $this->andWhere(['status' => Article::STATUS_PUBLISHED]);
        $this->andWhere(['<', '{{%article}}.published_at', time()]);
        return $this;
    }
}
