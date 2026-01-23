<?php

namespace App\Models\DBModels;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'customer';
    protected $fillable = [
        'id',
        'name',
        'phone_number',
        'email',
    ];

    public function getFillable() {return $this->fillable;}
    public function getName() {return $this->table;}

}
