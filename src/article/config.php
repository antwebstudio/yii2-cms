<?php
return [
    'id' => 'article',
    'class' => \ant\article\Module::className(),
    'isCoreModule' => false,
	'depends' => ['category'],
];