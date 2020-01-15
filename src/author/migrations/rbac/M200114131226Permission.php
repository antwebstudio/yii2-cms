<?php

namespace ant\author\migrations\rbac;

use yii\db\Schema;
use ant\rbac\Migration;
use ant\rbac\Role;

class M200114131226Permission extends Migration
{
	protected $permissions;
	
	public function init() {
		$this->permissions = [
			// backend
			\ant\author\backend\controllers\AuthorController::className() => [
				'index' => ['Manage Author', [Role::ROLE_ADMIN]],
				'create' => ['Create Author', [Role::ROLE_ADMIN]],
				'update' => ['Update Author', [Role::ROLE_ADMIN]],
				'delete' => ['Delete Author', [Role::ROLE_ADMIN]],
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
