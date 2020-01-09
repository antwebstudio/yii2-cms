<?php

namespace ant\comment\migrations\rbac;

use yii\db\Schema;
use ant\rbac\Migration;
use ant\rbac\Role;
use ant\rbac\rules\IsOwnModelRule;

class M180125065628_comment_permission extends Migration
{
	protected $permissions;
	
	
	public function init() {
		$this->permissions = [
			\ant\comment\controllers\CommentController::className() => [
				'delete' => ['Delete comment', [Role::ROLE_USER]],
				'create' => ['Create comment', [Role::ROLE_USER]],
				'update' => ['Edit comment', [Role::ROLE_USER]],
			],
			\ant\comment\models\Comment::className() => [
				'update' => ['Update own comment', [Role::ROLE_USER], 'ruleName' => IsOwnModelRule::className()],
				'delete' => ['Delete own comment', [Role::ROLE_USER], 'ruleName' => IsOwnModelRule::className()],
			],
			// backend
			\ant\comment\backend\controllers\CommentController::className() => [
				'delete' => ['Delete comment', [Role::ROLE_ADMIN]],
				'index' => ['Manage comment', [Role::ROLE_ADMIN]],
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
