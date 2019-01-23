<?php
namespace common\modules\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m161224_211833_create_behavior_class extends Migration
{
	public function up()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

        $this->createTable('{{%behavior_class}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'class_name' => $this->string(255)->notNull(),
        ], $tableOptions);

        /*$this->createIndex(
            'behaviors_class_id_idx', 
            'behaviors_class', 
            'id');*/

        $this->insert('{{%behavior_class}}', [
            'id' => 1,
            'class_name' => 'User',
        ]);

       $this->insert('{{%behavior_class}}', [
            'id' => 2,
            'class_name' => 'Email',
        ]);

       
	}




	public function down()
	{
		$this->dropTable('{{%behavior_class}}');
	}

}