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
 * @property int $tag_id 
 * @property string $name 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Tag extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tag';

    protected $primaryKey = 'tag_id';

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
    protected $casts = ['tag_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function articles()
    {
        return $this->belongsToMany(Article::class)->withTimestamps();
    }
}