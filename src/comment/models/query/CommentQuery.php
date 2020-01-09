<?php
namespace ant\comment\models\query;

use ant\models\ModelClass;

class CommentQuery extends \yii\db\ActiveQuery
{
	
	public function latestFirst() {
		return $this->orderBy('created_at DESC');
	}

	public function belongTo(\yii\base\Model $model)
	{
		
		return $this->andWhere([
			'model_class_id' => ModelClass::getClassId($model),
			'model_id' => $model->id,
		]);
	}

}
