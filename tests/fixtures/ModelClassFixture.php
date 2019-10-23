<?php

namespace tests\fixtures;

use yii\test\ActiveFixture;

/**
 * User fixture
 */
class ModelClassFixture extends ActiveFixture
{
    public $modelClass = 'ant\models\ModelClass';
    public $dataFile = '@tests/fixtures/data/model_class.php';
}
