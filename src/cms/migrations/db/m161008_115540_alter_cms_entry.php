<?php
namespace ant\cms\migrations\db;

use yii\db\Schema;
use ant\components\Migration;

class m161008_115540_alter_cms_entry extends Migration
{

	public function up()
	{
		$this->alterZeroDateTimeColumn('{{%cms_entry}}', ['published_date', 'created_date']);
		
		$this->dropForeignKey(
			'cms_entry_content_id_fk',
			'{{%cms_entry}}');

		$this->renameColumn('{{%cms_entry}}','content_id','content_uid');

		$this->addForeignKey(
			'cms_entry_content_uid_fk', 
			'{{%cms_entry}}', 
			'content_uid', 
			'{{%cms_content_data}}', 
			'id', 
			'CASCADE', 'RESTRICT');
	}

	public function down()
	{
		$this->dropForeignKey(
			'cms_entry_content_uid_fk',
			'{{%cms_entry}}');

		$this->renameColumn('{{%cms_entry}}','content_uid','content_id');
	}


}

