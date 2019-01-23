<?php
namespace common\modules\cms\migrations\db;

use yii\db\Schema;
use common\components\Migration;

class m161008_115940_alter_cms_category extends Migration
{

	public function up()
	{
		
		$this->alterZeroDateTimeColumn('{{%cms_category}}', ['created_date']);
		
		$this->dropForeignKey(
			'cms_category_content_id_fk',
			'{{%cms_category}}');

		$this->renameColumn('{{%cms_category}}','content_id','content_uid');

		$this->addForeignKey(
			'cms_category_content_uid_fk',
		 	'{{%cms_category}}',
		 	'content_uid',
		 	'{{%cms_content_data}}', 
		 	'id', 
		 	'CASCADE', 
		 	'RESTRICT');

		
	}

	public function down()
	{
		$this->dropForeignKey(
			'cms_category_content_uid_fk',
			'{{%cms_category}}');

		$this->renameColumn('{{%cms_category}}','content_uid','content_id');
	}


}

