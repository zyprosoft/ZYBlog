<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id 
 * @property int $user_id 
 * @property string $qq 
 * @property string $wx 
 * @property string $wb 
 * @property string $introduce 
 * @property string $github 
 * @property string $birthday 
 * @property string $mobile 
 * @property string $facebook 
 * @property string $twitter 
 * @property string $blog_name 
 * @property string $job 
 * @property string $hobby 
 * @property string $work_history 
 * @property string $company 
 * @property string $music 
 * @property int $sex 
 * @property string $constellation 
 * @property string $icp 
 * @property string $qq_code 
 * @property string $wx_code 
 * @property int $article_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class About extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'about';
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
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'sex' => 'integer', 'article_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * 关于我的文章
     * @return \Hyperf\Database\Model\Relations\HasOne
     */
    public function article()
    {
        return $this->hasOne(Article::class, 'article_id', 'article_id');
    }
}