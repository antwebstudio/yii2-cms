<?php

namespace tests\fixtures;

use yii\test\ActiveFixture;

/**
 * User fixture
 */
class CategoryFixture extends ActiveFixture
{
    public $modelClass = 'common\modules\category\models\Category';
	public $depends = [
        'tests\fixtures\ContentDataFixture',
    ];
}
