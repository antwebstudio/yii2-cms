<?php

namespace common\modules\cms\migrations\rbac;

use yii\db\Schema;
use common\rbac\Migration;
use common\rbac\Role;
use frontend\modules\cms\controllers\FormController;
use frontend\modules\cms\controllers\ArticleController;
use backend\modules\cms\controllers\DefaultController;

class M170729040416_cms_permission extends \common\rbac\Migration
{
	protected $permissions;
	
	public function init()
    {
		$this->permissions = [
			ArticleController::className() => [
				'view' => ['View CMS content', [Role::ROLE_GUEST]],
			],
			FormController::className() => [
				'register' => ['Contact Register Form', [Role::ROLE_GUEST]],
			],
			// Backend
			DefaultController::className() => [
				'index' => ['CMS', [Role::ROLE_ADMIN]],
			],
			\backend\modules\cms\controllers\FormController::className() => [
				'index' => ['View Contact Registered', [Role::ROLE_ADMIN]],
				'delete' => ['Delete Contact Registered', [Role::ROLE_ADMIN]],
			],
			\backend\modules\cms\controllers\ArticleController::className() => [
				'index' => ['View Articles', [Role::ROLE_ADMIN]],
				'create' => ['Create Articles', [Role::ROLE_ADMIN]],
				'update' => ['Update Articles', [Role::ROLE_ADMIN]],
				'delete' => ['Delete Articles', [Role::ROLE_ADMIN]],
				'upload' => ['Upload Articles Image', [Role::ROLE_ADMIN]],
				'upload-delete' => ['Delete Uploaded Articles Image', [Role::ROLE_ADMIN]],
			],
			\backend\modules\cms\controllers\ArticleCategoryController::className() => [
				'index' => ['View Article Categories', [Role::ROLE_ADMIN]],
				'create' => ['Create Article Categories', [Role::ROLE_ADMIN]],
				'update' => ['Update Article Categories', [Role::ROLE_ADMIN]],
				'delete' => ['Delete Article Categories', [Role::ROLE_ADMIN]],
				'upload' => ['Upload Article Categories Image', [Role::ROLE_ADMIN]],
				'upload-delete' => ['Delete Uploaded Article Categories Image', [Role::ROLE_ADMIN]],
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
