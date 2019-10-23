<?php
namespace ant\comment\behaviors;

use Yii;
use yii\db\ActiveRecord;
use ant\comment\models\Comment;
use ant\comment\models\CommentGroup;

class CommentableBehavior extends \yii\base\Behavior
{
	
	public $attribute;
	
	public function events() {
		return [
			ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attach($owner)
	{
		if (!($owner instanceof \yii\db\ActiveRecord)) {
			throw new \yii\base\InvalidParamException(Yii::t('app', 'Behavior must be attached to an ActiveRecord. '));
		}
		parent::attach($owner);
	}
	
	public function init() {
		if (!isset($this->attribute)) {
			throw new \yii\base\InvalidParamException(Yii::t('app', 'Property "attribute" of behavior must be set. '));
		}
	}
	
	public function getCommentGroup() {
		$model = CommentGroup::find()->belongTo($this->owner)->one();
		$owner = $this->owner;
		
		if (!isset($model)) {
			$model = new CommentGroup([
				'model_class' => $owner::className(),
				'model_id' => $owner->primaryKey,
				'foreign_pk' => \ant\helpers\ActiveRecordHelper::encodePrimaryKey($owner),
			]);
			
			if (!$model->save()) throw new \Exception(\yii\helpers\Html::errorSummary($owner));
		}
		return $model;
	}
	
	public function getLastComment() {
		$query = $this->getComments()->orderBy('comment.created_at DESC')->limit(1);
		$query->multiple = false;
		return $query;
	}

	public function getComments()
	{
		$owner = $this->owner;
		return $this->owner->hasMany(Comment::className(), ['group_id' => 'id'])->viaTable(CommentGroup::tableName(), ['model_id' => 'id'], function($q) use($owner) {
			return $q->onCondition(['model_class' => $owner::className()]);
		});
		//return Comment::find()->belongTo($this->owner);
    }

	/**
	 * Returns if the subject has comments
	 *
	 * @return bool true if there are comments
	 */
	public function getHasComments()
	{
		return Comment::find()->belongTo($this->owner)->exists();
	}
	
	public function getCommentAttributeLabel($name) {
		$model = new Comment;
		return $model->getAttributeLabel($name);
	}
	
	public function beforeSave($event) {
		$model = $this->owner;
		
		if (isset($this->owner->{$this->attribute})) {		
			$comment = new Comment([
				'group_id' => $this->getCommentGroup()->id,
				'is_published' => true,
			]);
			$comment->attributes = $this->owner->{$this->attribute};
			
			if (!$comment->save()) {
				$event->isValid = false;
				$this->owner->addErrors($comment->errors);
			}
		}
	}

}
