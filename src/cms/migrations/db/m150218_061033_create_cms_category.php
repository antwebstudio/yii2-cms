<?php
namespace ant\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m150218_061033_create_cms_category extends Migration
{
	public function up()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

        $this->createTable('{{%cms_category}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'content_id' => $this->integer(11)->notNull(),
            'created_date' => $this->timestamp(),
            'last_updated' => 'timestamp NOT NULL DEFAULT current_timestamp ON UPDATE current_timestamp'
        ], $tableOptions);

		
		
		$this->addForeignKey(
			'cms_category_content_id_fk',
		 	'{{%cms_category}}',
		 	'content_id',
		 	'{{%app_content}}', 
		 	'id', 
		 	'CASCADE', 
		 	'RESTRICT');
	}

	public function down()
	{
		$this->dropForeignKey(
			'cms_category_content_id_fk',
			'{{%cms_category}}');
		
		$this->dropTable('{{%cms_category}}');
	}
}