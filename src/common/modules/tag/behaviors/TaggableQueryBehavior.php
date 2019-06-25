<?php 
namespace common\modules\tag\behaviors;
/*
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\modules\category\models\Category;
use common\modules\category\models\CategoryMap;
use common\modules\category\models\CategoryType;
*/
class TaggableQueryBehavior extends \yii\base\Behavior 
{
    public function filterByTagId($id){
        if ($id) {
			$alias = 'tagMap';
			$query = $this->owner->joinWith('tagMap '.$alias);
			
            return $query->andFilterWhere([$alias.'.tag_id' => $id]); 
        } else {
            return $this->owner;
        }
    }
}
