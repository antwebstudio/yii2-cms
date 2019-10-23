<?php
namespace ant\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m150219_104602_create_app_relation extends Migration
{
	public function up()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

        $this->createTable('{{%app_relation}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'field_id' => $this->integer(11)->notNull(),
            'source_id' => $this->integer(11)->notNull(),
            'target_id' => $this->integer(11)->notNull(),
            'sequence' => 'tinyint(4) DEFAULT 0',
            'created_date' => $this->timestamp(),
            'last_updated' => 'timestamp NOT NULL DEFAULT current_timestamp ON UPDATE current_timestamp'
        ], $tableOptions);
		
		$this->addForeignKey(
			'app_relation_source_id_fk',
			'{{%app_relation}}', 
			'source_id', 
			'{{%app_content}}', 
			'id', 
			'CASCADE', 
			'RESTRICT');
		
		$this->createIndex(
			'app_relation_source_id_field_id_fk',
			'{{%app_relation}}',
			'source_id, field_id');
		
	}

	public function down()
	{
		$this->dropTable('{{%app_relation}}');
	}

}