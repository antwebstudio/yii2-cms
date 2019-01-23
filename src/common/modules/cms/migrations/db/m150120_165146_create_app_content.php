<?php
namespace common\modules\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m150120_165146_create_app_content extends Migration
{
	public function up()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

        $this->createTable('{{%app_content}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'app_id' => 'tinyint(4) NOT NULL DEFAULT 1',
            'type_id' => $this->integer(11)->notNull(),
            'last_updated_by' => $this->integer(11)->notNull()->defaultValue(0),
            'data' => $this->text(),
            'name' => $this->string(255)->notNull(),
            'sequence' => 'tinyint(4) DEFAULT 0',
            'created_date' => $this->timestamp(),
            'last_updated' => 'timestamp NOT NULL DEFAULT current_timestamp ON UPDATE current_timestamp',
            'created_by' => $this->integer(11)->unsigned(),
            'slug' => $this->string(255)
        ], $tableOptions);

        $this->createIndex(
            'app_content_slug_idk', 
            '{{%app_content}}', 
            'slug');

        $this->createIndex(
            'app_content_app_id_idk', 
            '{{%app_content}}', 
            'app_id');

        $this->createIndex(
            'app_content_app_id_type_id_idk', 
            '{{%app_content}}', 
            'app_id, type_id');

	   $this->addForeignKey(
        'app_content_type_id_fk', 
        '{{%app_content}}', 
        'type_id', 
        '{{%app_content_type}}', 
        'id', 
        'CASCADE', 'RESTRICT');	
	}

	public function down()
	{
        $this->dropForeignKey(
            'app_content_type_id_fk', 
            '{{%app_content}}');

		$this->dropTable('{{%app_content}}');
	}

}