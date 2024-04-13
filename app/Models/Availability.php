<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Availability extends Model
{
    use HasFactory;
    protected $fillable = [
        'doctor_id',
        'day',
        'from',
        'to',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class,'doctor_id','id');
    }

    protected function from(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('H:i'),
            set: fn ($value) => Carbon::createFromFormat('g:i A', $value)->format('H:i:s')
        );
    }
    
    protected function to(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('H:i'),
            set: fn ($value) => Carbon::createFromFormat('g:i A', $value)->format('H:i:s')
        );
    }
}
