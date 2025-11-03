<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable {
    use HasApiTokens,HasRoles;
    protected $table = 'admin';
    protected $guard_name = 'admin';
    protected $guarded=['id'];
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
