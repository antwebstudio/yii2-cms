<?php

namespace ant\comment;

/**
 * comment module definition class
 */
class Module extends \yii\base\Module
{
	public function behaviors() {
		return [
			'configurable' => [
				'class' => 'ant\behaviors\ConfigurableModuleBehavior',
				'formModels' => [
					'comment' => [
						'class' => 'ant\comment\models\Comment',
					],
				],
			],
		];
	}

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
