<?php

return [
    'id' => 'article',
    'class' => \backend\modules\article\Module::className(),
    'isCoreModule' => false,
	'depends' => [], // Payment module should not depends on any other module
];
?>