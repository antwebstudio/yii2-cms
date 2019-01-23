<?php

namespace common\modules\comment\migrations\db;

use yii\db\Expression;
use common\components\Migration;

class M180124080343_create_comment extends Migration
{
	protected $tableName = '{{%comment}}';
	
    public function safeUp()
    {
		$this->createTable($this->tableName, [
			'id' => $this->primaryKey(),
			'group_id' => $this->integer()->notNull(),
			'title' => $this->string(),
			'body' => $this->text()->notNull(),
			'is_published' => $this->smallInteger(1)->notNull(),
			'created_at' => $this->timestamp()->null()->defaultValue(null),
			'created_by' => $this->integer()->unsigned()->null()->defaultValue(null),
			'updated_at' => $this->timestamp()->null()->defaultValue(null),
			'updated_by' => $this->integer()->unsigned()->null()->defaultValue(null),
		], $this->getTableOptions());
		
		$this->createIndex('comment_idx', $this->tableName, [
			new Expression('`group_id` ASC'),
			new Expression('`created_at` DESC'),
		]);
		
		$this->addForeignKeyTo('{{%comment_group}}', 'group_id', self::FK_TYPE_CASCADE, self::FK_TYPE_CASCADE);
		
		$this->addForeignKeyTo('{{%user}}', 'created_by', self::FK_TYPE_SET_NULL, self::FK_TYPE_CASCADE);
		$this->addForeignKeyTo('{{%user}}', 'updated_by', self::FK_TYPE_SET_NULL, self::FK_TYPE_CASCADE);
    }

    public function safeDown()
    {
		//$this->dropTable($this->tableName);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M180124040343_create_comment cannot be reverted.\n";

        return false;
    }
    */
}
