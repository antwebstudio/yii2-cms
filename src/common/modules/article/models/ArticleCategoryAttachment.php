<?php

namespace common\modules\article\models;

use Yii;

/**
 * This is the model class for table "em_article_category_attachment".
 *
 * @property integer $id
 * @property integer $article_category_id
 * @property string $path
 * @property string $base_url
 * @property string $type
 * @property integer $size
 * @property string $name
 * @property integer $created_at
 *
 * @property ArticleCategory $articleCategory
 */
class ArticleCategoryAttachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_category_attachment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_category_id', 'path'], 'required'],
            [['article_category_id', 'size', 'created_at'], 'integer'],
            [['path', 'base_url', 'type', 'name'], 'string', 'max' => 255],
            [['article_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleCategory::className(), 'targetAttribute' => ['article_category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_category_id' => 'Article Category ID',
            'path' => 'Path',
            'base_url' => 'Base Url',
            'type' => 'Type',
            'size' => 'Size',
            'name' => 'Name',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleCategory()
    {
        return $this->hasOne(ArticleCategory::className(), ['id' => 'article_category_id']);
    }
}
