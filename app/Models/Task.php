<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dengan nama model (optional)
    protected $table = 'tasks';

    // Tentukan kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'project_id', 
        'title', 
        'description', 
        'status', 
        'due_date',
    ];

    // Relasi ke model Project jika ada hubungan antara Task dan Project
    public function project()
    {
        return $this->belongsTo(ProjectManagement::class, 'project_id');
    }
}
