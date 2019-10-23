<?php

namespace ant\article\migrations\db;

use ant\components\Migration;
class m180508_080512_alter_article extends Migration
{
	public $tableName = '{{%article}}';
	
    public function safeUp()
    {
		$this->addColumn($this->tableName, 'access_type', $this->string(512));
        $this->alterColumn($this->tableName, 'slug', $this->string(1024)->defaultValue(NULL));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'access_type');
        $this->alterColumn($this->tableName, 'slug', $this->string(1024)->notNull());
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
