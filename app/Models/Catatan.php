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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Catatan yang boleh dilihat umum.
     */
    public function scopePublik($query)
    {
        return $query
            ->where('status', 'Terbit')
            ->where('visibilitas', 'publik');
    }

    /**
     * Membersihkan dan menampilkan isi catatan dari Quill.
     */
    public function getIsiHtmlAttribute(): string
    {
        $isi = $this->isi ?? '';

        if (trim($isi) === '') {
            return '';
        }

        $config = \HTMLPurifier_Config::createDefault();

        $config->set(
            'HTML.Allowed',
            'p,br,strong,b,em,i,u,s,ol,ul,li,blockquote,h1,h2,h3,h4,pre,code,a[href|title],span[style]'
        );

        $config->set(
            'CSS.AllowedProperties',
            [
                'text-align',
                'color',
                'background-color',
            ]
        );

        $purifier = new \HTMLPurifier($config);

        return $purifier->purify($isi);
    }

    /**
     * Ringkasan catatan.
     */
    public function getRingkasanAttribute(): string
    {
        return $this->deskripsi ?: Str::limit($this->isi_teks ?? '', 120);
    }
}
