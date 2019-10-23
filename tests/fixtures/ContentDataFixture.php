<?php

namespace tests\fixtures;

use yii\test\ActiveFixture;

/**
 * User fixture
 */
class ContentDataFixture extends ActiveFixture
{
    public $modelClass = 'ant\cms\models\ContentData';
	public $depends = [
        'tests\fixtures\EntryTypeFixture',
	];
}
