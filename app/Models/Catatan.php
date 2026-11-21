<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Catatan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'judul',
        'isi',
        'matkul',
        'jurusan',
        'category_id',
        'deskripsi',
        'tautan',
        'visibilitas',
        'anonim',
    ];

    protected $casts = [
        'anonim' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Catatan yang boleh dilihat umum
    public function scopePublik($query)
    {
        return $query
            ->where('status', 'Terbit')
            ->where('visibilitas', 'publik');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getRingkasanAttribute(): string
    {
        return $this->deskripsi ?: Str::limit($this->isi_teks ?? '', 120);
    }
}
