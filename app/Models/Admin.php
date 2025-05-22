<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Pipeline\Pipeline;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;

    use SoftDeletes;
    protected $guard_name = 'admin';

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'bod',
        'password',
    ];

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
        'deleted_at' => 'datetime',
    ];

    public function adminPassword()
    {
        return $this->hasMany(AdminPassword::class, 'admin_id');
    }

    function password(): Attribute
    {
        return Attribute::make(
            set: fn($value) => $value ? Hash::make($value): null
        );
    }

    public static function paginateWithFilters($limit)
    {
        return app(Pipeline::class)
            ->send(Admin::query())
            ->through([
                \App\QueryFilters\SortBy::class,
                \App\QueryFilters\SearchName::class,
                \App\QueryFilters\Trash::class,
            ])
            ->thenReturn()
            ->paginate($limit);
    }
}
