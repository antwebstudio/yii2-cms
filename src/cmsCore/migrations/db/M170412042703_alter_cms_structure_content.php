<?php

namespace ant\cmsCore\migrations\db;

use ant\db\Migration;

class M170412042703_alter_cms_structure_content extends Migration
{
    public function up()
    {
		$this->alterZeroDateTimeColumn('{{%cms_structure_content}}', ['created_date']);
		
		$this->renameColumn('{{%cms_structure_content}}','content_id','content_uid');
		$this->alterColumn('{{%cms_structure_content}}', 'root', $this->integer());
    }

    public function down()
    {
		$this->renameColumn('{{%cms_structure_content}}','content_uid','content_id');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
