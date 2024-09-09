<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable  implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    use HasRoles, SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */

    protected $primaryKey = 'id';
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = True;
/**
     * The name of the "created at" column.
     *
     * @var string
     */

    const CREATED_AT = 'created_on';
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */

    const UPDATED_AT = 'updated_on';
/**
 * The attributes that are not mass assignable.
 *
 * The guarded property protects specific attributes from mass assignment.
 * Any attribute listed in this array will not be mass assignable.
 *
 * In this case, the 'password' attribute is guarded, meaning it cannot be
 * assigned via mass assignment methods like `create()` or `fill()`.
 *
 * @var array
 */
    protected $guarded = ['password'];

/**
     * Accessor for retrieving the created_on date.
     *
     * @param string $value
     * @return string
     */
    public function getCreatedOnAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }
/**
     * Accessor for retrieving the updated_on date.
     *
     * @param string $value
     * @return string
     */
    public function getUpdatedOnAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
      // علاقة المستخدم مع المهام (User has many tasks)
    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
