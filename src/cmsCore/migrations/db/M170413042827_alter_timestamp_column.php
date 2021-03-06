<?php

namespace ant\cmsCore\migrations\db;

use ant\db\Migration;

class M170413042827_alter_timestamp_column extends Migration
{
    public function up()
    {
		$this->alterZeroDateTimeColumn('{{%cms_relation}}', ['created_date']);
		$this->alterZeroDateTimeColumn('{{%cms_structure}}', ['created_date']);
    }

    public function down()
    {
		
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
