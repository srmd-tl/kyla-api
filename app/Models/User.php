<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getPhotoAttribute($value)
    {
        $url = sprintf(request()->getHttpHost() . "%s" . $value, "/storage/");
        return $url;
    }
    public function contacts()
    {
        return $this->hasMany(Contact::class,'user_id');
    }
    public function audioFiles()
    {
        return $this->hasMany(UserFile::class,'user_id')->where('type',UserFile::AUDIO);
    }
    public function videoFiles()
    {
        return $this->hasMany(UserFile::class,'user_id')->where('type',UserFile::VIDEO);
    }
    public function officer()
    {
        return $this->hasOne(Officer::class,'user_id');
    }
}
