<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id 
 * @property string $nickname 
 * @property string $username 
 * @property string $email 
 * @property string $qq 
 * @property string $wx 
 * @property string $wb 
 * @property string $introduce 
 * @property string $github 
 * @property string $birthday 
 * @property string $mobile 
 * @property string $facebook 
 * @property string $twitter 
 * @property string $avatar 
 * @property string $blog_name 
 * @property int $sex 
 * @property string $constellation 
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
    protected $casts = ['id' => 'integer', 'sex' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}