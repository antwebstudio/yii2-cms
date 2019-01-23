<?php
namespace common\modules\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m160403_035444_create_cms_matrix_content extends Migration
{
	public function up()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

        $this->createTable('{{%cms_matrix_content}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'model_id' => $this->integer(11)->notNull(),
            'field_id' => $this->integer(11)->notNull(),
            'sequence' => 'tinyint(4) DEFAULT 0',
            'created_date' => $this->timestamp(),
            'last_updated' => 'timestamp NOT NULL DEFAULT current_timestamp ON UPDATE current_timestamp'
        ], $tableOptions);

		$this->addForeignKey(
			'cms_matrix_content_model_id_fk', 
			'{{%cms_matrix_content}}', 
			'model_id', 
			'{{%app_content}}', 
			'id', 
			'CASCADE', 'RESTRICT');
		
	}

	public function down()
	{
		$this->dropTable('{{%cms_matrix_content}}');
	}

}