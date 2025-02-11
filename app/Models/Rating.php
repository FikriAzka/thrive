<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'name',
        'email',
        'phone',
        'position',
        'project_or_product',
        'pic',
        'pertanyaan1',
        'pertanyaan2',
        'pertanyaan3',
        'pertanyaan4',
        'pertanyaan5',
        'pertanyaan6',
        'pertanyaan7',
        'pertanyaan8',
        'suggestions',
        'suggestions2',
        'token',
        'expires_at'
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($rating) {
            $rating->token = Str::random(32);
            $rating->expires_at = now()->addDay(); // Token berlaku 24 jam
        });
    }
}