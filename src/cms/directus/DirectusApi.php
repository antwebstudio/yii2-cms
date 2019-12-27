<?php
namespace ant\cms\components;

use GuzzleHttp\Client;

class DirectusApi {
	public function getItems($collectionName, $params = [], $fields = '*.*') {
		$status = isset($params['status']) ? $params['status'] : 'published';
		
		$content = $this->get('items/'.$collectionName.'?fields='.$fields.'&status='.$status)->getBody();
		return json_decode($content, true)['data'];
	}
	
	public function get($path) {
		$host = 'http://localhost/ant/directus/public';
		$project = 'ant-web';
		$fullPath = $host.'/'.$project.'/'.$path;
		
		$client = new Client();
		return $client->request('GET', $fullPath, [
		]);
	}
}