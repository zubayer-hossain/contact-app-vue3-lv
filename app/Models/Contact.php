<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'email'];

    public function phoneNumber(){
        return $this->hasMany(PhoneNumber::class);
    }

    public function address(){
        return $this->hasMany(Address::class);
    }
}
