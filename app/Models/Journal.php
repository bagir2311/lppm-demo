<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $table = 'journals';

    protected $fillable = [
        'nama_portal',
        'domain',
        'url',
        'deskripsi',
    ];

    /**
     * Compatibility accessors for title, link, description
     */
    public function getTitleAttribute()
    {
        return $this->nama_portal;
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['nama_portal'] = $value;
    }

    public function getLinkAttribute()
    {
        return $this->url;
    }

    public function setLinkAttribute($value)
    {
        $this->attributes['url'] = $value;
    }

    public function getDescriptionAttribute()
    {
        return $this->deskripsi;
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['deskripsi'] = $value;
    }

    public function getDomainAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        $host = parse_url($this->url, PHP_URL_HOST);
        $path = parse_url($this->url, PHP_URL_PATH);
        
        return $host ? ($host . ($path ? rtrim($path, '/') : '')) : ($this->url ?? 'portal.mahardika.ac.id');
    }
}
