<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'path',
        'size',
        'format',
        'user_id'
    ];
    protected $hidden = [
        'path',
    ];
    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

}
