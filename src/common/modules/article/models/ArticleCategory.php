<?php

namespace common\modules\article\models;

use trntv\filekit\behaviors\UploadBehavior;
use common\models\query\ArticleCategoryQuery;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 * @property string $slug
 * @property string $title
 * @property integer $status
 *
 * @property Article[] $articles
 * @property ArticleCategory $parent
 */
class ArticleCategory extends ActiveRecord
{
	public $icon;
	public $thumbnail;
	public $attachments;
	
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_category}}';
    }

    /**
     * @return ArticleCategoryQuery
     */
    public static function find()
    {
        return new ArticleCategoryQuery(get_called_class());
    }

    public function behaviors()
    {
        return [
			'galleryBehavior' => [
				//'class' => \common\modules\file\behaviors\GalleryBehavior::className(),
				'class' => \sashsvamir\galleryManager\GalleryBehavior::className(),
				'type' => 'article-category',
				'extension' => 'jpg',
				'directory' => Yii::getAlias('@storage') . '/web/gallery',
				'url' => Yii::getAlias('@storageUrl') . '/gallery',
				'versions' => [
					'small' => function ($img) {
						ini_set('memory_limit','256M');

						/** @var \Imagine\Image\ImageInterface $img */
						return $img
							->copy()
							->thumbnail(new \Imagine\Image\Box(200, 200));
					},
					'medium' => function ($img) {
						ini_set('memory_limit','256M');
						
						/** @var Imagine\Image\ImageInterface $img */
						$dstSize = $img->getSize();
						$maxWidth = 900;
						if ($dstSize->getWidth() > $maxWidth) {
							$dstSize = $dstSize->widen($maxWidth);
						}
						return $img
							->copy()
							->resize($dstSize);
					},
				],
			],
            TimestampBehavior::className(),
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'immutable' => true
            ],
            [
                'class' => \trntv\filekit\behaviors\UploadBehavior::className(),
                'attribute' => 'icon',
                'pathAttribute' => 'icon_path',
                'baseUrlAttribute' => 'icon_base_url',
            ],
            [
                'class' => \trntv\filekit\behaviors\UploadBehavior::className(),
                'attribute' => 'thumbnail',
                'pathAttribute' => 'thumbnail_path',
                'baseUrlAttribute' => 'thumbnail_base_url'
            ],
            [
                'class' => UploadBehavior::className(),
                'attribute' => 'attachments',
                'multiple' => true,
                'uploadRelation' => 'articleCategoryAttachments',
                'pathAttribute' => 'path',
                'baseUrlAttribute' => 'base_url',
                'orderAttribute' => 'order',
                'typeAttribute' => 'type',
                'sizeAttribute' => 'size',
                'nameAttribute' => 'name',
            ],
			'translateable' => [
                'class' => \creocoder\translateable\TranslateableBehavior::className(),
                'translationAttributes' => [
					'slug', 'title', 'body', 'subtitle',
				],
                // translationRelation => 'translations',
                // translationLanguageAttribute => 'language',
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
        return $this->hasMany(ArticleCategoryLang::className(), ['master_id' => 'id']);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'subtitle'], 'string', 'max' => 512],
            [['slug'], 'unique'],
            [['slug'], 'string', 'max' => 1024],
            [['thumbnail_base_url', 'thumbnail_path'], 'string', 'max' => 1024],
            [['icon_base_url', 'icon_path'], 'string', 'max' => 1024],
            [['body', 'attachments', 'icon', 'thumbnail'], 'safe'],
            ['status', 'integer'],
            ['parent_id', 'exist', 'targetClass' => ArticleCategory::className(), 'targetAttribute' => 'id']
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
            'parent_id' => Yii::t('common', 'Parent Category'),
            'status' => Yii::t('common', 'Active')
        ];
    }
	
	public function getUrl() {
		return \yii\helpers\Url::to(['/cms/article-category/view', 'slug' => $this->slug, 'id' => $this->id]);
	}
	
	/*
	public function getTranslations() {
		
		throw new \Exception($this->langClassName.' [ '.$this->langForeignKey.' => '.$this->ownerPrimaryKey.']');
		return $this->hasMany(\common\models\ArticleCategoryLang::className(), ['master_id' => 'id']);
	}*/

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['category_id' => 'id']);
    }
	
	public function getSubCategories() {
		return $this->hasMany(ArticleCategory::className(), ['parent_id' => 'id']);
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(ArticleCategory::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleCategoryAttachments()
    {
        return $this->hasMany(ArticleCategoryAttachment::className(), ['article_category_id' => 'id']);
    }
}
