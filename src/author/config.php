<?php

return [
    'id' => 'author',
    'class' => \ant\author\Module::className(),
    'isCoreModule' => false,
	'modules' => [
		//'v1' => \ant\author\api\v1\Module::class,
		'backend' => \ant\author\backend\Module::class,
	],
	'depends' => [],
];