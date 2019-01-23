<?php

namespace common\modules\article\migrations\db;

use common\components\Migration;
class m180604_080512_alter_article extends Migration
{
	public $tableName = '{{%article_attachment}}';
	
    public function safeUp()
    {
    	$this->dropForeignKey('fk_article_category', '{{%article}}');
    	$this->addForeignKey('fk_article_category', '{{%article}}', 'category_id', '{{%category}}', 'id', 'cascade', 'cascade');
    }

    public function safeDown()
    {
        // if column got null value, it will error
    	$this->dropForeignKey('fk_article_category', '{{%article}}');
    	$this->addForeignKey('fk_article_category', '{{%article}}', 'category_id', '{{%article_category}}', 'id', 'cascade', 'cascade');
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
