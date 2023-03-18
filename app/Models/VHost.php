<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VHost extends Model
{
    protected $fillable = [
        'name',
        'enabled',
        'php_version',
        'document_root',
        'environment',
    ];

    protected $appends = [
        'filename',
        'server_name'
    ];

    public function getFilenameAttribute(): string
    {
        return Str::of($this->name)
            ->slug()
            ->append('.')
            ->append($this->environment)
            ->append('.conf')
            ->__toString();
    }

    public function getServerNameAttribute(): string
    {
        return Str::of("https://")
            ->append($this->name)
            ->append('.')
            ->append($this->environment)
            ->append('.local')
            ->__toString();
    }
}
