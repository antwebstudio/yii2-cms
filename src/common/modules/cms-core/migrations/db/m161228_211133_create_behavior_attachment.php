<?php
namespace common\modules\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m161228_211133_create_behavior_attachment extends Migration
{
	public function up()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

        $this->createTable('{{%behavior_attachment}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'class_id' => $this->integer(11)->notNull(),
            'behavior_id' => $this->integer(11)->notNull(),
            'behavior_setting' => $this->string(255)->notNull()
        ], $tableOptions);




        $this->addForeignKey(
            'behaviors_id_fk', 
            '{{%behavior_attachment}}', 
            'behavior_id', 
            '{{%behaviors}}', 
            'id', 
            'CASCADE', 'RESTRICT');

        $this->addForeignKey(
            'behavior_class_id_fk', 
            '{{%behavior_attachment}}', 
            'class_id', 
            '{{%behavior_class}}', 
            'id', 
            'CASCADE', 'RESTRICT');

	}




	public function down()
	{
		$this->dropTable('{{behavior_attachment}}');
	}

}