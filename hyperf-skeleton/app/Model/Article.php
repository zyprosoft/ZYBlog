<?php
/**
 * This file is part of ZYProSoft/ZYBlog.
 *
 * @link     http://zyprosoft.lulinggushi.com
 * @document http://zyprosoft.lulinggushi.com
 * @contact  1003081775@qq.com
 * @Company  ZYProSoft
 * @license  MIT
 */

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $article_id 
 * @property string $title 
 * @property string $content 
 * @property int $user_id 
 * @property int $category_id 
 * @property int $read_count 
 * @property int $comment_count 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class Article extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'article';

    protected $primaryKey = 'article_id';

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
    protected $casts = ['article_id' => 'integer', 'user_id' => 'integer', 'category_id' => 'integer', 'read_count' => 'integer', 'comment_count' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'article_tag','article_id','tag_id')->withTimestamps();
    }

    public function author()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'category_id', 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'article_id', 'article_id')->limit(10)->latest();
    }
}