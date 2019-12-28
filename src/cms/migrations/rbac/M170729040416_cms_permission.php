<?php

namespace ant\cms\migrations\rbac;

use yii\db\Schema;
use ant\rbac\Migration;
use ant\rbac\Role;

class M170729040416_cms_permission extends \ant\rbac\Migration
{
	protected $permissions;
	
	public function init()
    {
		$this->permissions = [
			\ant\cms\controllers\ArticleController::className() => [
				'view' => ['View CMS content', [Role::ROLE_GUEST]],
			],
			\ant\cms\controllers\FormController::className() => [
				'register' => ['Contact Register Form', [Role::ROLE_GUEST]],
			],
			// Backend
			\ant\cms\backend\controllers\DefaultController::className() => [
				'index' => ['CMS', [Role::ROLE_ADMIN]],
			],
			\ant\cms\backend\controllers\EntryController::className() => [
				'index' => ['CMS', [Role::ROLE_ADMIN]],
				'create' => ['CMS', [Role::ROLE_ADMIN]],
				'update' => ['CMS', [Role::ROLE_ADMIN]],
				'image-upload' => ['CMS', [Role::ROLE_ADMIN]],
				'file-upload' => ['CMS', [Role::ROLE_ADMIN]],
				'file-delete' => ['CMS', [Role::ROLE_ADMIN]],
			],
			/*\ant\cms\backend\controllers\CollectionController::className() => [
				'index' => ['CMS', [Role::ROLE_ADMIN]],
				'create' => ['CMS', [Role::ROLE_ADMIN]],
				'update' => ['CMS', [Role::ROLE_ADMIN]],
			],
			\ant\cms\backend\controllers\ItemController::className() => [
				'index' => ['CMS', [Role::ROLE_ADMIN]],
				'create' => ['CMS', [Role::ROLE_ADMIN]],
				'update' => ['CMS', [Role::ROLE_ADMIN]],
			],*/
			\ant\cms\backend\controllers\FormController::className() => [
				'index' => ['View Contact Registered', [Role::ROLE_ADMIN]],
				'delete' => ['Delete Contact Registered', [Role::ROLE_ADMIN]],
			],
			\ant\cms\backend\controllers\ArticleController::className() => [
				'index' => ['View Articles', [Role::ROLE_ADMIN]],
				'create' => ['Create Articles', [Role::ROLE_ADMIN]],
				'update' => ['Update Articles', [Role::ROLE_ADMIN]],
				'delete' => ['Delete Articles', [Role::ROLE_ADMIN]],
				'upload' => ['Upload Articles Image', [Role::ROLE_ADMIN]],
				'upload-delete' => ['Delete Uploaded Articles Image', [Role::ROLE_ADMIN]],
			],
			\ant\cms\backend\controllers\ArticleCategoryController::className() => [
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
