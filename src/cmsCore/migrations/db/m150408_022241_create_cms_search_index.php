<?php
namespace ant\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m150408_022241_create_cms_search_index extends Migration
{
	public function up()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

        $this->createTable('{{cms_search_index}}', [
            'model_id' => $this->integer(11)->notNull(),
            'attribute' => $this->string(25)->notNull(),
            'weight' => $this->smallInteger(4)->notNull()->defaultValue(1),
            'keywords' => $this->text()->Null()
        ], $tableOptions);

		$this->createIndex(
			'cms_search_index_model_id_idx', 
			'{{cms_search_index}}', 
			'model_id');
		
		$this->addPrimaryKey(
			'cms_search_index_pk',
		 	'{{cms_search_index}}',
		  	'model_id, attribute');

		

		$indexName = 'cms_search_index_keywords_idx';
		$tableName = '{{cms_search_index}}';
		$column = 'keywords';
		
		$this->execute('CREATE FULLTEXT INDEX '.$indexName.' ON '.$tableName.' ('.$column.')');
	}

	public function down()
	{
		$this->dropTable('{{cms_search_index}}');
	}
}