<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // use HasFactory;
    protected $fillable = ['user_id', 'title', 'team_id', 'content', 'completed'];

    protected $hidden = ['created_at', 'updated_at'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
