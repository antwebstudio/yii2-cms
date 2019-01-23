<?php
return [
    'id' => 'article',
    'class' => \common\modules\article\Module::className(),
    'isCoreModule' => false,
	'depends' => ['category'],
];