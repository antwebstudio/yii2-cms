<?php
namespace ant\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m160113_120400_create_business_hour extends Migration
{
	public function up()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

		$this->createTable('{{%business_hour}}', [
            'id' => $this->primaryKey(10)->unsigned(),
            'model_id' => $this->integer(11)->notNull(),
            'day' => $this->string(25)->notNull(),
            'start_time' => $this->string(25)->notNull(),
            'end_time' => $this->string(25)->notNull(),
            'remark' => $this->string(255)->Null()
        ], $tableOptions);
		
		$this->addForeignKey(
			'business_hour_model_id_fk', 
			'{{%business_hour}}', 
			'model_id', 
			'{{%app_content}}', 
			'id', 
			'CASCADE', 'RESTRICT');
	}

	public function down()
	{	
		$this->dropForeignKey(
		'business_hour_model_id_fk', 
		'{{%business_hour}}');

	    $this->dropTable('{{%business_hour}}');
	}
}