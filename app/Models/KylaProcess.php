<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KylaProcess extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getAudioPathAttribute($value)
    {
        $path = sprintf("https://docs.google.com/uc?export=download&id=%s", $value);
        return $this->value = $path;
    }

    public function getVideoPathAttribute($value)
    {
        $path = sprintf("https://docs.google.com/uc?export=download&id=%s", $value);
        return $this->value = $path;
    }

}
