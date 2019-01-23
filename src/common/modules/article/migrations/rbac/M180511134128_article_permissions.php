<?php

namespace common\modules\article\migrations\rbac;

use yii\db\Schema;
use common\rbac\Migration;
use common\rbac\Permission;
use common\rbac\Role;
use common\rbac\rule\IsOwnModelRule;

class M180511134128_article_permissions extends Migration
{
	protected $permissions;

	public function init() {
		$this->permissions = [
			\backend\modules\article\controllers\ArticleController::className() => [
				'view' => ['View article', [Role::ROLE_ADMIN]],
				'create' => ['create article', [Role::ROLE_ADMIN]],
				'update' => ['udate article', [Role::ROLE_ADMIN]],
				'index' => ['index article', [Role::ROLE_ADMIN]],
				'view' => ['View article', [Role::ROLE_ADMIN]],
				'delete' => ['delete article', [Role::ROLE_ADMIN]],
				'avatar-upload' => ['upload attachment', [Role::ROLE_ADMIN]],
				'avatar-delete' => ['delete attachment', [Role::ROLE_ADMIN]],
			],
			\backend\modules\category\controllers\DefaultController::className() => [
				'view' => ['View article', [Role::ROLE_ADMIN]],
				'create' => ['create article', [Role::ROLE_ADMIN]],
				'update' => ['udate article', [Role::ROLE_ADMIN]],
				'index' => ['index article', [Role::ROLE_ADMIN]],
				'view' => ['View article', [Role::ROLE_ADMIN]],
				'delete' => ['delete article', [Role::ROLE_ADMIN]],
			],
			\frontend\modules\article\controllers\DefaultController::className() => [
				'index' => ['View article module landing page', [Role::ROLE_GUEST]],
			],
			\frontend\modules\article\controllers\ArticleController::className() => [
				'index' => ['View article', [Role::ROLE_USER]],
				'company-index' => ['index company article', [Role::ROLE_USER]],
				'view' => ['View article', [Role::ROLE_USER]],
				'create' => ['create article', [Role::ROLE_USER]],
				'update' => ['udate article', [Role::ROLE_USER]],
				'delete' => ['delete article', [Role::ROLE_USER]],
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
