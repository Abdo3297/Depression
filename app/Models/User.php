<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $columnListing = DB::getSchemaBuilder()->getColumnListing($this->getTable());
        $columnsToRemove = ['id', 'created_at', 'updated_at'];
        $this->fillable = array_diff($columnListing, $columnsToRemove);
    }
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed'
    ];
}
