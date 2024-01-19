<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user extends Model implements Authenticatable
{
    use HasFactory;
    protected $guarded = ['id'];

    public function messages(){
        return $this->hasMany(message::class);
    }

    public function servers(){
        return $this->belongsToMany(server::class, 'user_server');
    }

    public function getAuthIdentifierName()
    {
        return "id";
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberTokenName()
    {
        return "remember_token";
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
