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
 * @property int $comment_id 
 * @property string $content 
 * @property int $parent_comment_id 
 * @property int $article_id 
 * @property int $user_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Comment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comment';

    protected $primaryKey = 'comment_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['article_id'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['comment_id' => 'integer', 'parent_comment_id' => 'integer', 'article_id' => 'integer', 'user_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function article()
    {
        return $this->belongsTo(Article::class,'article_id','article_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class,'user_id','user_id');
    }

    public function parent_comment()
    {
        return $this->hasOne(Comment::class,'comment_id','parent_comment_id');
    }

    public function reply_list()
    {
        return $this->belongsTo(Comment::class,'comment_id','parent_comment_id');
    }
}