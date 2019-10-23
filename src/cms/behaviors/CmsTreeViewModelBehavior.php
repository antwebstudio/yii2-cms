<?php
namespace ant\cms\behaviors;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\db\ActiveRecord;
use ant\cms\models\Structure;
use ant\cms\models\StructureContent;
use kartik\tree\TreeView;

class CmsTreeViewModelBehavior extends \yii\base\Behavior {
	public $nodeActivationErrors = [];
	public $nodeRemovalErrors = [];
	
	protected $_structureContent;
	
	public static $boolAttribs = [
        'active',
        'selected',
        'disabled',
        'readonly',
        'visible',
        'collapsed',
        'movable_u',
        'movable_d',
        'movable_r',
        'movable_l',
        'removable',
        'removable_all',
    ];

    /**
     * @var array the default list of boolean attributes with initial value = `false`
     */
    public static $falseAttribs = [
        'selected',
        'disabled',
        'readonly',
        'collapsed',
        'removable_all',
    ];
	
	public function events() {
		return [
			ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
		];
	}
	
	public function beforeInsert() {
		// For new node, must have either makeRoot or appendTo (other node) operation, if no operation, call a default operation.
		if (!$this->treeNode->hasOperation() && !$this->treeNode->attachedToTree()) {
			$this->treeNode->makeRoot();
		}
		
		if (!isset($this->treeNode->content_uid)) {
			$this->treeNode->content_uid = $this->owner->content_uid;
			if (!$this->treeNode->save()) throw new \Exception(Html::errorSummary($this->treeNode));
		}
	}
	
	protected function getStructureId() {
		if (!isset($this->owner->entryType->structure_id)) {
			$structure = new Structure;
			if (!$structure->save()) throw new \Exception(Html::errorSummary($structure));
			
			$this->owner->entryType->structure_id = $structure->id;
			if (!$this->owner->entryType->save()) throw new \Exception(Html::errorSummary($this->entryType));
		}
		return $this->owner->entryType->structure_id;
	}
	
	public function isVisible() {
		return true;
	}
	
	public function isActive() {
		return true;
	}
	
	public function isReadonly() {
		return false;
	}
	
	public function isMovable($dir)
    {
        $attr = "movable_{$dir}";
        return $this->parse($attr);
    }
	
	public function isRemovable() {
		return true;
	}
	
	public function isRemovableAll() {
		return true;
	}
	
	public function isCollapsed() {
		return true;
	}
	
	public function isDisabled() {
		return false;
	}
	
	public function getCollapsed() {
		
	}
	
	public function setCollapsed() {
		
	}
	
	public function getDisabled() {
		
	}
	
	public function setDisabled() {
		
	}
	
	public function setReadonly() {
		
	}
	
	public function getVisible() {
		
	}
	
	public function setVisible() {
		
	}
	
	public function getReadonly() {
		
	}
	
	public function setMovable_U() {
		
	}
	
	public function getMovable_U() {
		
	}
	
	public function setMovable_D() {
		
	}
	
	public function getMovable_D() {
		
	}
	
	public function setMovable_L() {
		
	}
	
	public function getMovable_L() {
		
	}
	
	public function setMovable_R() {
		
	}
	
	public function getMovable_R() {
		
	}
	
	public function getSelected()
    {
		return false;
        return $this->parse('selected', false);
    }
	
	public function setSelected() {
		
	}
	
	public function setRemovable() {
		
	}
	
	public function getRemovable() {
		
	}
	
	public function setRemovable_All() {
		
	}
	
	public function getRemovable_All() {
		
	}
	
	public function isTreeNodeAttribute($name) {
		$treeNodeAttributes = [
			$this->treeNode->leftAttribute,
			$this->treeNode->rightAttribute,
		];
		
		if ($this->treeNode->treeAttribute !== false) {
			$treeNodeAttributes[] = $this->treeNode->treeAttribute;
		}
		return in_array($name, $treeNodeAttributes);
	}
	
	public function setTreeNodeAttribute($name, $value) {
		throw new \Exception('test');
		return $this->treeNode->setAttribute($name, $value);
	}
	
	public function getTreeNodeAttribute($name) {
        return $this->treeNode->getAttribute($name);
	}
	/*
	public function getLvl() {
		return $this->treeNode->level; //return $this->structure->;
	}
	
	public function getLft() {
		return $this->treeNode->left;
	}
	
	public function getRight() {
		return $this->treeNode->right + 2;
	}*/
	
	public function getIcon() {
		return null;
	}
	
	public function getIcon_Type() {
		return null;
	}
	
	public function setIcon_Type($value) {
		return null;
	}
	
	public function getActiveOrig() {
		
	}
	
	public function setActiveOrig() {
		
	}
	
	public function getActive() {
		return true;
	}
	
	public function setActive() {
		
	}
	
	public function getTreeNode() {
		if (isset($this->owner->structureContent)) {
			$this->_structureContent = $this->owner->getStructureContent()->one();
		} else if (isset($this->_structureContent)) {
			// Do nothing
		} else {
			$this->_structureContent = new StructureContent;
			$this->_structureContent->structure_id = $this->getStructureId();
		}
		return $this->_structureContent;
	}
	
	public function getStructureContent() {
		return $this->owner->hasOne(StructureContent::className(), ['content_uid' => 'content_uid']);
	}
	
	/**
	 * Proxy methods
	 */
	 
	 public function setName($value) {
		 $this->owner->name = $value;
	 }
	
	public function getName() {
		return $this->owner->name;
	}
	
	public function getIsNewRecord() {
		return $this->owner->isNewRecord;
	}
	
	public function parents($depth = null) {
		return $this->treeNode->parents($depth);
	}
	
	public function children($depth = null) {
		return $this->treeNode->children($depth);
	}
	
	public function prependTo($node, $runValidation = true, $attributes = null) {
		return $this->treeNode->prependTo($node, $runValidation, $attributes);
	}
	
	public function appendTo($node, $runValidation = true, $attributes = null) {
		return $this->treeNode->appendTo($node->treeNode, $runValidation, $attributes);
	}
	
	public function insertBefore($node, $runValidation = true, $attributes = null) {
		return $this->treeNode->insertBefore($node, $runValidation, $attributes);
	}
	
	public function insertAfter($node, $runValidation = true, $attributes = null) {
		return $this->treeNode->insertAfter($node, $runValidation, $attributes);
	}
	
	public function deleteWithChildren() {
		return $this->treeNode->deleteWithChildren();
	}
	
	public function leaves() {
		return $this->treeNode->leaves();
	}
	
	public function prev() {
		return $this->treeNode->prev();
	}
	
	public function next() {
		return $this->treeNode->next();
	}
	
	public function makeRoot($runValidation = true, $attributes = null) {
		return $this->treeNode->makeRoot($runValidation, $attributes);
	}
	
	public function isRoot() {
		return $this->treeNode->isRoot();
	}
	
	public function isChildOf($node) {
		return $this->treeNode->isChildOf($node);
	}
	
	public function isLeaf() {
		return $this->treeNode->isLeaf();
	}
	
    /**
     * Initialize default values
     */
    public function initDefaults()
    {
        /**
         * @var Tree $this
         */
        $module = TreeView::module();
        $iconTypeAttribute = null;
        extract($module->dataStructure);
        $this->setDefault($iconTypeAttribute, TreeView::ICON_CSS);
        foreach (static::$boolAttribs as $attr) {
            $val = in_array($attr, static::$falseAttribs) ? false : true;
            $this->setDefault($attr, $val);
        }
		$this->owner->setEntryType('productCategory');
    }
	
	/**
     * Activates a node (for undoing a soft deletion scenario)
     *
     * @param boolean $currNode whether to update the current node value also
     *
     * @return boolean status of activation
     */
    public function activateNode($currNode = true)
    {
        /**
         * @var Tree $this
         */
        $this->nodeActivationErrors = [];
        $module = TreeView::module();
        extract($module->treeStructure);
        if ($this->isRemovableAll()) {
            $children = $this->children()->all();
            foreach ($children as $child) {
                /**
                 * @var Tree $child
                 */
                $child->active = true;
                if (!$child->save()) {
                    /** @noinspection PhpUndefinedFieldInspection */
                    /** @noinspection PhpUndefinedVariableInspection */
                    $this->nodeActivationErrors[] = [
                        'id' => $child->$idAttribute,
                        'name' => $child->$nameAttribute,
                        'error' => $child->getFirstErrors(),
                    ];
                }
            }
        }
        if ($currNode) {
            $this->active = true;
            if (!$this->save()) {
                /** @noinspection PhpUndefinedFieldInspection */
                /** @noinspection PhpUndefinedVariableInspection */
                $this->nodeActivationErrors[] = [
                    'id' => $this->$idAttribute,
                    'name' => $this->$nameAttribute,
                    'error' => $this->getFirstErrors(),
                ];
                return false;
            }
        }
        return true;
    }

    /**
     * Removes a node
     *
     * @param boolean $softDelete whether to soft delete or hard delete
     * @param boolean $currNode   whether to update the current node value also
     *
     * @return boolean status of activation/inactivation
     */
    public function removeNode($softDelete = false, $currNode = true)
    {
        /**
         * @var Tree $this
         * @var Tree $child
         */
        if ($softDelete) {
            $this->nodeRemovalErrors = [];
            $module = TreeView::module();
            extract($module->treeStructure);
            if ($this->isRemovableAll()) {
                $children = $this->children()->all();
                foreach ($children as $child) {
                    $child->active = false;
                    if (!$child->save()) {
                        /** @noinspection PhpUndefinedFieldInspection */
                        /** @noinspection PhpUndefinedVariableInspection */
                        $this->nodeRemovalErrors[] = [
                            'id' => $child->$keyAttribute,
                            'name' => $child->$nameAttribute,
                            'error' => $child->getFirstErrors(),
                        ];
                    }
                }
            }
            if ($currNode) {
                $this->active = false;
                if (!$this->owner->save()) {
                    /** @noinspection PhpUndefinedFieldInspection */
                    /** @noinspection PhpUndefinedVariableInspection */
                    $this->nodeRemovalErrors[] = [
                        'id' => $this->$keyAttribute,
                        'name' => $this->$nameAttribute,
                        'error' => $this->getFirstErrors(),
                    ];
                    return false;
                }
            }
            return true;
        } else {
            return $this->removable_all || $this->isRoot() && $this->children()->count() == 0 ?
                $this->deleteWithChildren() : $this->owner->delete();
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $module = TreeView::module();
        $keyAttribute = $nameAttribute = $leftAttribute = $rightAttribute = $depthAttribute = null;
        $treeAttribute = $iconAttribute = $iconTypeAttribute = null;
        extract($module->treeStructure + $module->dataStructure);
        $labels = [
            $keyAttribute => Yii::t('kvtree', 'ID'),
            $nameAttribute => Yii::t('kvtree', 'Name'),
            $leftAttribute => Yii::t('kvtree', 'Left'),
            $rightAttribute => Yii::t('kvtree', 'Right'),
            $depthAttribute => Yii::t('kvtree', 'Depth'),
            $iconAttribute => Yii::t('kvtree', 'Icon'),
            $iconTypeAttribute => Yii::t('kvtree', 'Icon Type'),
            'active' => Yii::t('kvtree', 'Active'),
            'selected' => Yii::t('kvtree', 'Selected'),
            'disabled' => Yii::t('kvtree', 'Disabled'),
            'readonly' => Yii::t('kvtree', 'Read Only'),
            'visible' => Yii::t('kvtree', 'Visible'),
            'collapsed' => Yii::t('kvtree', 'Collapsed'),
            'movable_u' => Yii::t('kvtree', 'Movable Up'),
            'movable_d' => Yii::t('kvtree', 'Movable Down'),
            'movable_l' => Yii::t('kvtree', 'Movable Left'),
            'movable_r' => Yii::t('kvtree', 'Movable Right'),
            'removable' => Yii::t('kvtree', 'Removable'),
            'removable_all' => Yii::t('kvtree', 'Removable (with children)'),
        ];
        if (!$treeAttribute) {
            $labels[$treeAttribute] = Yii::t('kvtree', 'Root');
        }
        return $labels;
    }

    /**
     * Generate and return the breadcrumbs for the node.
     *
     * @param integer $depth   the breadcrumbs parent depth
     * @param string  $glue    the pattern to separate the breadcrumbs
     * @param string  $currCss the CSS class to be set for current node
     * @param string  $new     the name to be displayed for a new node
     *
     * @return string the parsed breadcrumbs
     */
    public function getBreadcrumbs($depth = 1, $glue = ' &raquo; ', $currCss = 'kv-crumb-active', $new = 'Untitled')
    {
        /**
         * @var Tree $this
         */
        if ($this->isNewRecord || empty($this)) {
            return $currCss ? Html::tag('span', $new, ['class' => $currCss]) : $new;
        }
        $depth = empty($depth) ? null : intval($depth);
        $module = TreeView::module();
        $nameAttribute = ArrayHelper::getValue($module->dataStructure, 'nameAttribute', 'name');
        $crumbNodes = $depth === null ? $this->parents()->all() : $this->parents($depth - 1)->all();
        $crumbNodes[] = $this;
        $i = 1;
        $len = count($crumbNodes);
        $crumbs = [];
        foreach ($crumbNodes as $node) {
            $name = $node->$nameAttribute;
            if ($i === $len && $currCss) {
                $name = Html::tag('span', $name, ['class' => $currCss]);
            }
            $crumbs[] = $name;
            $i++;
        }
        return implode($glue, $crumbs);
    }

    /**
     * Sets default value of a model attribute
     *
     * @param string $attr the attribute name
     * @param mixed  $val  the default value
     */
    protected function setDefault($attr, $val)
    {
        if (empty($this->$attr)) {
            $this->$attr = $val;
        }
    }

    /**
     * Parses an attribute value if set - else returns the default
     *
     * @param string $attr    the attribute name
     * @param mixed  $default the attribute default value
     *
     * @return mixed
     */
    protected function parse($attr, $default = true)
    {
        return isset($this->$attr) ? $this->$attr : $default;
    }
}