<?php

namespace common\modules\article\models;

use common\models\query\ArticleQuery;
use trntv\filekit\behaviors\UploadBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\modules\user\models\User;
use common\modules\category\models\Category;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $slug
 * @property string $title
 * @property string $body
 * @property string $view
 * @property string $thumbnail_base_url
 * @property string $thumbnail_path
 * @property array $attachments
 * @property integer $author_id
 * @property integer $updater_id
 * @property integer $status
 * @property integer $published_at
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $author
 * @property User $updater
 * @property ArticleCategory $category
 * @property ArticleAttachment[] $articleAttachments
 */
class Article extends ActiveRecord
{
    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFT = 0;
    const ACCESS_TYPE_PUBLIC = 'public';
    const ACCESS_TYPE_PRIVATE = 'private';
    const SCENARIO_CASEY = 'casey';

    /**
     * @var array
     */
    public $attachments;
    public $categories;

    /**
     * @var array
     */
    public $thumbnail;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /** 
     * @return ArticleQuery
     */
	public static function find() {
		return new \common\modules\article\models\query\ArticleQuery(get_called_class());
	}

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'author_id',
                'updatedByAttribute' => 'updater_id',

            ],
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'immutable' => true
            ],
            // [
            //     'class' => UploadBehavior::className(),
            //     'attribute' => 'thumbnail',
            //     'pathAttribute' => 'thumbnail_path',
            //     'baseUrlAttribute' => 'thumbnail_base_url'
            // ],
            // [
            //     'class' => \common\modules\file\behaviors\AttachmentBehavior::className(),
            //     'modelType' => Article::className(),
            //     'attribute' => 'attachments',
            //     'multiple' => true
            // ],
			'translateable' => [
                'class' => \creocoder\translateable\TranslateableBehavior::className(),
                'translationAttributes' => [
					'slug', 'title', 'body', 'subtitle',
				],
                // translationRelation => 'translations',
                // translationLanguageAttribute => 'language',
            ],
            'thumbnail' => [
                'class' => UploadBehavior::className(),
                'attribute' => 'thumbnail',
                'pathAttribute' => 'thumbnail_path',
                'baseUrlAttribute' => 'thumbnail_url'
            ],
            'category' => 
            [
                'class'=> \common\modules\category\behaviors\CategorizableBehavior::className(),
                'attribute' => 'categories',
                'type' => 'article',
                //'categoryType' => 'article',
            ],
        ];
    }
	
	public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE,
        ];
    }
	
	public function getTranslations()
    {
        return $this->hasMany(ArticleLang::className(), ['master_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'body'], 'required', 'on' => 'default'],
            [['title', 'body', 'access_type'], 'required', 'on' => self::SCENARIO_CASEY],
            [['slug'], 'unique'],
            [['body'], 'string'],
            [['published_at'], 'default', 'value' => function () {
                return date(DATE_ISO8601);
            }],
            [['published_at'], 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            [['author_id', 'updater_id', 'status'], 'integer'],
            [['slug', 'thumbnail_base_url', 'thumbnail_path'], 'string', 'max' => 1024],
            [['title', 'subtitle'], 'string', 'max' => 512],
            [['view'], 'string', 'max' => 255],
            [['subtitle', 'attachments', 'thumbnail'], 'safe'],
            [['categories'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'slug' => Yii::t('common', 'Slug'),
            'title' => Yii::t('common', 'Title'),
            'body' => Yii::t('common', 'Body'),
            'view' => Yii::t('common', 'Article View'),
            'thumbnail' => Yii::t('common', 'Thumbnail'),
            'author_id' => Yii::t('common', 'Author'),
            'updater_id' => Yii::t('common', 'Updater'),
            'status' => Yii::t('common', 'Published'),
            'published_at' => Yii::t('common', 'Published At'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At')
        ];
    }
	
	public function getUrl() {
		return \yii\helpers\Url::to(['/cms/article/view', 'slug' => $this->slug, 'id' => $this->id]);
	}

	public function getSummary($length = 100) {
		return substr(strip_tags($this->body), 0, $length);
	}
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(User::className(), ['id' => 'updater_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleAttachments()
    {
        return $this->hasMany(ArticleAttachment::className(), ['article_id' => 'id']);
    }
}
