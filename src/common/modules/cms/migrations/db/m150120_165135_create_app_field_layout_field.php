<?php
namespace common\modules\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m150120_165135_create_app_field_layout_field extends Migration
{
	public function safeUp()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }


        $this->createTable('{{%app_field_layout_field}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'layout_id' => $this->integer(11)->notNull(),
            'tab_id' => $this->integer(11)->notNull(),
            'field_id' => $this->integer(11)->notNull(),
            'required' => $this->boolean()->unsigned()->notNull()->defaultValue(0),
            'sequence' => 'tinyint(4) DEFAULT 0',
            'created_date' => $this->timestamp(),
            'last_updated' => 'timestamp NOT NULL DEFAULT current_timestamp ON UPDATE current_timestamp'
        ], $tableOptions);

        $this->createIndex(
        	'app_field_layout_field_layoutId_fieldId_unq_idx', 
        	'{{%app_field_layout_field}}', 
        	'layout_id, field_id', 
        	true);

		$this->createIndex(
			'app_field_layout_field_sequence_idx', 
			'{{%app_field_layout_field}}', 
			'sequence');
		
		$this->addForeignKey(
			'app_field_layout_field_field_id_fk', 
			'{{%app_field_layout_field}}', 
			'field_id', 
			'{{%app_field}}', 
			'id', 
			'CASCADE', 'RESTRICT');

		$this->addForeignKey(
			'app_field_layout_field_layout_id_fk', 
			'{{%app_field_layout_field}}', 
			'layout_id', 
			'{{%app_field_layout}}', 
			'id');

		$this->addForeignKey(
			'app_field_layout_field_tab_id_fk', 
			'{{%app_field_layout_field}}', 
			'tab_id', 
			'{{%app_field_layout_tab}}', 
			'id');
	}

	public function safeDown()
	{
		$this->dropForeignKey(
			'app_field_layout_field_field_id_fk', 
			'{{%app_field_layout_field}}');

		$this->dropForeignKey(
			'app_field_layout_field_layout_id_fk', 
			'{{%app_field_layout_field}}');

		$this->dropForeignKey(
			'app_field_layout_field_tab_id_fk', 
			'{{%app_field_layout_field}}');

		$this->dropTable('{{%app_field_layout_field}}');
	}

	
}