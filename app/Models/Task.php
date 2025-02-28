<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description', 
        'status',  // Tambahkan ini
        'due_date',
        'project_management_id'
    ];

    public function project()
    {
        return $this->belongsTo(ProjectManagement::class, 'project_management_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}