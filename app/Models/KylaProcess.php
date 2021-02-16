<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KylaProcess extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getAudioPathAttribute($value)
    {
        if ($value) {

//            $path = sprintf("https://drive.google.com/file/d/%s/preview", $value);
            $path = sprintf("https://drive.google.com/uc?id=%s", $value);
            return $this->value = $path;
        }
    }

    public function getVideoPathAttribute($value)
    {
        if ($value) {
            $path = sprintf("%s/storage/%s", env("APP_URL"), $value);
            return $this->value = asset($path);
        }

    }

}
