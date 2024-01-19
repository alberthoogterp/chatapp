<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class server extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function messages(){
        return $this->hasMany(message::class);
    }

    public function users(){
        return $this->belongsToMany(user::class, 'user_server');
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
