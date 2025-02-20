<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectManagement extends Model
{
    use HasFactory;

    protected $fillable = ['nama_proyek', 'description', 'status', 'deadline'];

    public function users()
    {
        // Pastikan relasi dengan tabel pivot menggunakan nama kolom yang sesuai
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
    }
}
