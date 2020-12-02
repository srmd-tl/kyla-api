<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFile extends Model
{
    use HasFactory;

    public const AUDIO = 1;
    public const VIDEO = 2;

    public function getPathAttribute($value)
    {
        $path = sprintf("https://docs.google.com/uc?export=download&id=%s", $value);
        return $this->value = $path;
    }


}
