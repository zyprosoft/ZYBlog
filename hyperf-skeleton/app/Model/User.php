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

use App\Constants\Constants;
use Qbhy\HyperfAuth\Authenticatable;

/**
 * @property int $user_id 
 * @property string $nickname 
 * @property string $username 
 * @property string $password 
 * @property string $email
 * @property string $site
 * @property string $avatar 
 * @property int $role_id 
 * @property string $introduce 
 * @property string $deleted_at 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class User extends Model implements Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email','nickname','site'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['user_id' => 'integer', 'role_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    protected $hidden = ['password'];

    public function getId() {
        return $this->user_id;
    }

    public function isAdmin()
    {
        return $this->role_id == Constants::USER_ROLE_ADMIN;
    }

    public static function retrieveById($key): ?Authenticatable
    {
        return User::find($key);
    }

    public function about()
    {
        return $this->hasOne(About::class, 'user_id', 'user_id');
    }
}