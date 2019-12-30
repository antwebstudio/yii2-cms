<?php
return [
    'id' => 'comment',
    'class' => \ant\comment\Module::className(),
    'isCoreModule' => false,
	'modules' => [
		//'v1' => \ant\category\api\v1\Module::class,
		'backend' => \ant\comment\backend\Module::class,
	],
	'depends' => [],
];