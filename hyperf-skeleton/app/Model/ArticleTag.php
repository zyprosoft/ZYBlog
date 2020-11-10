<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id 
 * @property int $article_id 
 * @property int $tag_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class ArticleTag extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'article_tag';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'article_id' => 'integer', 'tag_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}