<?php
namespace common\modules\comment\models\query;

class CommentQuery extends \yii\db\ActiveQuery
{

	public function belongTo(\yii\base\Model $model)
	{
		/*if ($model->isNewRecord) {
			throw new InvalidParamException('Commenting is not possible on unsaved models');
		}
		if (count($model->primaryKey) === 0) {
			throw new InvalidParamException('The model needs a valid primary key');
		}*/
		
		$this->joinWith('commentGroup commentGroup');
		
		$this->andWhere(['commentGroup.model_class' => $model::className()]);
		$this->andWhere(['commentGroup.foreign_pk' => \common\helpers\ActiveRecordHelper::encodePrimaryKey($model)]);

		return $this;
	}

}
