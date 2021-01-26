<?php

declare (strict_types=1);
namespace App\Model;

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
 */
class User extends Model
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
    protected $fillable = ['email','nickname','site'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['user_id' => 'integer', 'role_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}