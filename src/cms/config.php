<?php

return [
    'id' => 'cms',
    'class' => \ant\cms\Module::className(),
    'isCoreModule' => false,
	'modules' => [
		'v1' => \ant\cms\api\v1\Module::class,
		'backend' => \ant\cms\backend\Module::class,
	],
	'depends' => ['file', 'cmsCore'],
];