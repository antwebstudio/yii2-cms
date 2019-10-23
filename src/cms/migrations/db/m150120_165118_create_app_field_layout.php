<?php
namespace ant\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m150120_165118_create_app_field_layout extends Migration
{
	public function safeUp()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

        $this->createTable('{{%app_field_layout}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'type' => $this->string(150)->notNull(),
            'created_date' => $this->timestamp(),
            'last_updated' => 'timestamp NOT NULL DEFAULT current_timestamp ON UPDATE current_timestamp'
        ], $tableOptions);

        $this->createIndex(
            'app_field_layout_type_idx', 
            '{{%app_field_layout}}', 
            'type');
		
	}

	public function safeDown()
	{
		$this->dropTable('{{%app_field_layout}}');
	}

}