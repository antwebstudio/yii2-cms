<?php

namespace ant\article\migrations\db;

use ant\db\Migration;
class m180523_080512_alter_article_attachment extends Migration
{
	public $tableName = '{{%article_attachment}}';
	
    public function safeUp()
    {
        $this->alterColumn($this->tableName, 'path', $this->string(1024)->defaultValue(NULL));
    }

    public function safeDown()
    {
        // if column got null value, it will error
        $this->alterColumn($this->tableName, 'path', $this->string(1024)->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170731_080512_alter_article_category cannot be reverted.\n";

        return false;
    }
    */
}
