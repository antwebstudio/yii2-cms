<?php
namespace ant\cmsCore\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m150331_042412_create_app_structure_content extends Migration
{
	public function up()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

		$this->createTable('{{%app_structure_content}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'structure_id' => $this->integer(11)->notNull(),
            'content_id' => $this->integer(11),
            'root' => $this->integer(11)->null(),
            'left' => $this->integer(11)->notNull(),
            'right' => $this->integer(11)->notNull(),
            'level' => $this->smallInteger(6)->notNull(),
            'created_date' => $this->timestamp(),
            'last_updated' => 'timestamp NOT NULL DEFAULT current_timestamp ON UPDATE current_timestamp'
        ], $tableOptions);

		
		$this->addForeignKey(
			'app_structure_content_content_id_fk',
		 	'{{%app_structure_content}}', 
		 	'content_id', 
		 	'{{%app_content}}', 
		 	'id', 
		 	'CASCADE', 'RESTRICT');

		$this->addForeignKey(
			'app_structure_content_structure_id_fk', 
			'{{%app_structure_content}}', 
			'structure_id', 
			'{{%app_structure}}', 
			'id', 
			'CASCADE', 'RESTRICT');
	}

	public function down()
	{
		$this->dropTable('{{%app_structure_content}}');
	}
}