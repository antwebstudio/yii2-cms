<?php

namespace common\rbac;

use Yii;
use yii\base\Component;
use yii\db\MigrationInterface;

use common\rbac\Permission;

/**
 * @author Eugene Terentev <eugene@terentev.net>
 */
class Migration extends Component implements MigrationInterface
{

    /**
     * @var string|\yii\rbac\BaseManager
     */
    public $auth = 'authManager';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->auth = \Yii::$app->get('authManager');
    }

    /**
     * This method contains the logic to be executed when applying this migration.
     * Child classes may override this method to provide actual migration logic.
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function up(){}

    /**
     * This method contains the logic to be executed when removing this migration.
     * The default implementation throws an exception indicating the migration cannot be removed.
     * Child classes may override this method if the corresponding migrations can be removed.
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function down(){}

    public function createRole($roleName)
    {
        $this->auth->createRole($roleName);
    }

    /*public function buildPermissionName($params = [])
    {
        $className = isset($params['class']) ? $params['class'] : '';
        $type = isset($params['type']) ? $params['type'] : '';
        $action = isset($params['action']) ? $params['action'] : '';

        return Permission::buildName([
           Permission::KEY_PERMISSION_CLASSNAME => $className,
           Permission::KEY_PERMISSION_TYPE      => $type,
           Permission::KEY_PERMISSION_ACTION    => $action,
       ]);
    }*/

	public function addAllPermissions($permissions)
    {
		foreach ($permissions as $controller => $permission)
        {
			foreach ($permission as $action => $p)
            {
                list($description, $roles) = $p;

				foreach ($roles as $role)
                {
					$role = $this->auth->getRole($role);

					$options = [Permission::of($action, $controller), $description];

					if (isset($p['ruleName'])) {
						$options['ruleName'] = $p['ruleName'];
					}

					$this->addPermissionToRole($role, [$options]);
				}
			}
		}
	}

    public function removeAllPermissions($permissions)
    {
		foreach ($permissions as $className => $permission)
        {
			foreach ($permission as $action => $p)
            {
                $permission = Permission::of($action, $className)->name;
                $this->removePermission($permission);
			}
		}
	}

    public function addChildrenFor($children, $parent = '')
	{
		$auth = $this->auth;

		foreach ($children as $key => $child)
		{
			if (is_array($child)) {
				$this->addChildrenFor($child, $key);
			} else {
				$this->addPermission($parent);
				$this->addPermission($child);

				$auth->addChild($auth->getPermission($parent), $auth->getPermission($child));
			}
		}
	}

    public function removeChildrenFor($children, $parent = '')
	{
		foreach ($children as $key => $child)
		{
			if (is_array($child)) {
				$this->removeChildrenFor($child, $key);
			} else {
				$this->removePermission($parent);
				$this->removePermission($child);
			}
		}
	}

	public function addPermission($permission, $description = null, $ruleName = null)
    {
        if ($permission instanceof \yii\rbac\Permission) {
			$name = $permission->name;
            $description = ''; // TODO: [mlaxwong] permission description
		} else if(is_array($permission)) {
            list($name, $description) = $permission;
		} else if(is_string($permission)) {
            $name = $permission;
            $description = '';
        }

        $permission = $this->auth->createPermission($name);
        $permission->description = $description;
		if (isset($ruleName)) $permission->ruleName = $ruleName;

		if ($this->auth->getPermission($name) == null)
        {
			$this->auth->add($permission);
            return $permission;
		} else {
            return $this->auth->getPermission($name);
        }
	}

    public function removePermission($permission)
    {
        if ($permission instanceof \yii\rbac\Permission) {
			$name = $permission->name;
            $description = ''; // TODO: [mlaxwong] permission description
		} else if(is_array($permission)) {
            list($name, $description) = $permission;
		} else if(is_string($permission)) {
            $name = $permission;
            $description = '';
        }

        $permissionItem = $this->auth->getPermission($name);

        if($permissionItem) $this->auth->remove($permissionItem);
    }

	public function addPermissionToRole($role, $permissions = []) {

		if (!is_array($permissions)) $permissions = [$permissions];

		foreach ($permissions as $p) {
			if (is_array($p)) {
				$permission = $p[0];
				$description = isset($p[1]) ? $p[1] : null;
			} else {
				$permission = $p;
				$description = null;
			}

			/*if (is_array($p['ruleName'])) {
				unset($p['ruleName']);
				//\Yii::configure($permission->rule
			}*/

            $permission = $this->addPermission($permission->name, $description, isset($p['ruleName']) ? $p['ruleName'] : null);

			array_shift($p);
			array_shift($p);

			\Yii::configure($permission, $p);

			$this->auth->addChild($role, $permission);
		}
	}
}
