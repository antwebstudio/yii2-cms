<?php
namespace ant\cms\components;

class Directus extends \yii\base\Component {
	public function init() {
		/*$config = [
			'database' => [
				'hostname' => 'localhost',
				'username' => 'root',
				'password' => 'root',
				'database' => 'directus',
				// Optional
				// 'port' => 3306,
				// 'charset' => 'utf8'
			],
			'filesystem' => [
				'root' => '/path/to/directus/storage/uploads'
			]
		];

		$client = \Directus\SDK\ClientFactory::create($config);*/
	}
	
	public function getItems($collectionName, $limit = null, $offset = null, $status = null) {
		$api = new DirectusApi;
		
		return $api->getItems($collectionName, ['status' => $status, 'limit' => $limit, 'offset' => $offset]);
	}
}