<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\Events\Creating;
use Hyperf\DbConnection\Model\Model;
use Qbhy\HyperfAuth\Authenticatable;
use App\Constants\Constants;
/**
 * @property int $user_id 
 * @property string $nickname 
 * @property string $username 
 * @property string $password 
 * @property string $email 
 * @property string $avatar 
 * @property int $role_id 
 * @property string $introduce 
 * @property string $deleted_at 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \App\Model\Verify $role
 */
class User extends Model implements Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';
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
    protected $casts = ['user_id' => 'integer', 'role_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
    protected $hidden = ['password'];
    public function getId()
    {
        return $this->user_id;
    }
    public static function retrieveById($key) : ?Authenticatable
    {
        $user = User::query()->select(["user_id", "role_id", "nickname", "username", "mobile", "avatar", "approved", "deleted_at"])->where("userId", $key)->first();
        if ($user instanceof Authenticatable) {
            return $user;
        }
        return null;
    }
    public function role()
    {
        return $this->belongsTo(Verify::class);
    }
    public function isAdmin()
    {
        return $this->role_id == Constants::USER_ROLE_ADMIN;
    }
}