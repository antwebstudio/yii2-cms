<?php

use common\modules\cms\fieldtypes\PlainTextFieldType;
use common\modules\cms\fieldtypes\CategoryFieldType;
use common\modules\cms\fieldtypes\ImageFieldType;

return [
	'plainText' => [
		'name' => 'field 1',
		'handle' => 'field1',
		'type' => PlainTextFieldType::className(), // matter
		
	],
	'category' => [
		'name' => 'field 2',
		'handle' => 'field2',
		'type' => CategoryFieldType::className(), // matter
	],
	'image' => [
		'name' => 'field 3',
		'handle' => 'field3',
		'type' => ImageFieldType::className(), // matter
	]
];