<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Doctor;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;
    protected $fillable = [];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $columnListing = DB::getSchemaBuilder()->getColumnListing($this->getTable());
        $columnsToRemove = ['id', 'created_at', 'updated_at'];
        $this->fillable = array_diff($columnListing, $columnsToRemove);
    }
    
    protected $perPage = 5;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('post_image');
    }
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class,'doctor_id','id');
    }
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d-m-Y H:i'),
        );
    }
}
