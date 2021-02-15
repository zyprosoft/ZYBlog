<?php
/**
 * This file is part of ZYProSoft/ZYBlog.
 *
 * @link     http://zyprosoft.lulinggushi.com
 * @document http://zyprosoft.lulinggushi.com
 * @contact  1003081775@qq.com
 * @Company  泽湾普罗信息技术有限公司(ZYProSoft)
 * @license  GPL
 */

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

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id', 'tag_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id', 'article_id');
    }
}