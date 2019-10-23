<?php

namespace tests\fixtures;

use yii\test\ActiveFixture;

/**
 * User fixture
 */
class CmsCategoryFixture extends ActiveFixture
{
    public $modelClass = 'ant\cms\models\Category';
	public $depends = [
        'tests\fixtures\ContentDataFixture',
    ];
}
