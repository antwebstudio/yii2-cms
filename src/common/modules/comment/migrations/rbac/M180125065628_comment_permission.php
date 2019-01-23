<?php

namespace common\modules\comment\migrations\rbac;

use yii\db\Schema;
use common\rbac\Migration;
use common\rbac\Role;
use common\rbac\rule\IsOwnModelRule;

class M180125065628_comment_permission extends Migration
{
	protected $permissions;
	
	
	public function init() {
		$this->permissions = [
			\frontend\modules\comment\controllers\CommentController::className() => [
				'delete' => ['Delete comment', [Role::ROLE_USER]],
			],
			\common\modules\comment\models\Comment::className() => [
				'update' => ['Update own comment', [Role::ROLE_USER], 'ruleName' => IsOwnModelRule::className()],
				'delete' => ['Delete own comment', [Role::ROLE_USER], 'ruleName' => IsOwnModelRule::className()],
			],
		];
		
		parent::init();
	}
	
	public function up()
    {
		$this->addAllPermissions($this->permissions);
    }

    public function down()
    {
		$this->removeAllPermissions($this->permissions);
    }
}
