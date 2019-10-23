<?php
namespace ant\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m150120_165131_create_app_field_layout_tab extends Migration
{
	public function safeUp()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }


        $this->createTable('{{%app_field_layout_tab}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'layout_id' => $this->integer(11)->notNull(),
            'name' => $this->string(255)->notNull(),
            'sequence' => 'tinyint(4) DEFAULT 0',
            'created_date' => $this->timestamp(),
            'last_updated' => 'timestamp NOT NULL DEFAULT current_timestamp ON UPDATE current_timestamp'
        ], $tableOptions);

        $this->createIndex(
            'app_field_layout_tab_sequence_idx', 
            '{{%app_field_layout_tab}}', 
            'sequence');
        
        $this->addForeignKey(
            'app_field_layout_tab_layout_id_fk', 
            '{{%app_field_layout_tab}}', 
            'layout_id', 
            '{{%app_field_layout}}', 
            'id');
	}

	public function safeDown()
	{
		$this->dropForeignKey(
            'app_field_layout_tab_layout_id_fk',
            'app_field_layout_tab'
        );

		$this->dropTable('{{%app_field_layout_tab}}');
	}

}