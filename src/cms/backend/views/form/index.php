<?php
use yii\grid\GridView;

$this->title = 'Contact Registration';

?>

<div class="event-default-index">
    <?= \kartik\grid\GridView::widget([
		'autoXlFormat' => true,
		/*'export'=>[
			'fontAwesome' => true,
			'showConfirmAlert' => false,
			'target' => \kartik\grid\GridView::TARGET_BLANK
		],*/
		'panel'=>[
			'type' => 'primary',
		],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => [
            'class' => 'grid-view table-responsive'
        ],
        'columns' => [
            'id',
            'name',
            'title',
			[
				'attribute' => 'age',
				'value' => function ($data) {
					$age = \Yii::$app->params['form_options']['age'];
					return isset($age[$data->age]) ? $age[$data->age] : $data->age;
				}
			],
			'mobile',
			'email',
			'address',
			'address2',
			'address3',
			'state',
			'country',
			[
				'attribute' => 'options',
				'value' => function($data) {
					return implode(', ', (array) $data->options);
				},
			],
			[
				'attribute' => 'data',
				'label' => 'How Did You Get To Know Us', 
				'value' => function($data) {
					return isset($data->data['hearUs']) ? implode(', ', (array) $data->data['hearUs']) : '';
				},
			],
			'created_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}'
            ],
        ]
    ]); ?>
</div>
