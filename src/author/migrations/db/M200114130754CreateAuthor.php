<?php

namespace ant\author\migrations\db;

use ant\db\Migration;

/**
 * Class M200114130754CreateAuthor
 */
class M200114130754CreateAuthor extends Migration
{
	protected $tableName = '{{%author}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned(),
			'name' => $this->string()->null(),
			'short_description' => $this->string()->null(),
            'created_at' => $this->timestamp()->defaultValue(null),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ],  $this->getTableOptions());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200114130754CreateAuthor cannot be reverted.\n";

        return false;
    }
    */
}
