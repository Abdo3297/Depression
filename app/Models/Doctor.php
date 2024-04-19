<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\Availability;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Doctor extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable,InteractsWithMedia,HasRoles;
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
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('doctor_profile');
    }
    protected function birth(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn ($value) => Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d')
        );
    }
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class,'doctor_id','id');
    }
    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class,'doctor_id','id');
    }


    public static function clientID()
    {
        return 'Ta8XK2ctSm6yzNNsRpUFVA';
    }

    public static function clientSecret()
    {
        return 'GM316pp4BDJoQLLWiGaNLoyg7bf8Wxd7';
    }

    public static function accountID()
    {
        return '8n4zKQfTRCqOqspY9HbSZA';
    }
}
