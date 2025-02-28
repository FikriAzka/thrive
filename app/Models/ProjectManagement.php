<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectManagement extends Model
{
    use HasFactory;

    protected $table = 'project_managements'; // Pastikan ini sesuai dengan database

    protected $fillable = [
        'nama_proyek',
        'description',
        'status',
        'deadline',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_management_user');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_management_id');
    }

    public function peserta()
{
    return $this->belongsToMany(User::class, 'project_users', 'project_id', 'user_id');
}


}
