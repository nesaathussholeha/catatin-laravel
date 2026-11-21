<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteReport extends Model
{
    protected $fillable = [
        'catatan_id',
        'user_id',
        'judul',
        'jenis',
        'deskripsi',
        'status',
    ];

    public function catatan()
    {
        return $this->belongsTo(Catatan::class, 'catatan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}