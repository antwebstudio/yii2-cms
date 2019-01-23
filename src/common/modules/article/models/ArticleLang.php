<?php

namespace common\modules\article\models;

use Yii;

/**
 * This is the model class for table "em_article_lang".
 *
 * @property integer $id
 * @property integer $master_id
 * @property string $language
 * @property string $slug
 * @property string $title
 * @property string $body
 *
 * @property Article $master
 */
class ArticleLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_lang}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['master_id'], 'integer'],
            [['language'], 'required'],
            [['body'], 'string'],
            [['language'], 'string', 'max' => 6],
            [['slug'], 'string', 'max' => 1024],
            [['title'], 'string', 'max' => 512],
            [['subtitle'], 'string', 'max' => 512],
            [['master_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['master_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'master_id' => 'Master ID',
            'language' => 'Language',
            'slug' => 'Slug',
            'title' => 'Title',
            'body' => 'Body',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaster()
    {
        return $this->hasOne(Article::className(), ['id' => 'master_id']);
    }
}
