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
 * @property int $category_id 
 * @property string $name 
 * @property string $avatar 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'category';

    protected $primaryKey = 'category_id';

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
    protected $casts = ['category_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function articles()
    {
        return $this->hasMany(Article::class,'category_id','category_id');
    }
}