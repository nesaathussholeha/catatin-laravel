<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Catatan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'judul',
        'isi',
        'isi_teks', // Tambahan
        'tipe_isi',  // Tambahan
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

    public function upvoters(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'catatan_upvotes')
            ->withTimestamps();
    }

    public function getRingkasanAttribute(): string
    {
        return $this->deskripsi ?: Str::limit($this->isi_teks ?? '', 120);
    }

    public function getIsTeksAttribute(): bool
    {
        return $this->tipe_isi === 'teks';
    }


    public function getIsiHtmlAttribute(): string
    {
        $delta = json_decode($this->isi ?? '', true);

        if (!is_array($delta) || !isset($delta['ops']) || !is_array($delta['ops'])) {
            return '';
        }

        // Mengubah teks Quill menjadi HTML dengan tetap mengamankan isi teks.
        $formatTeks = function (string $teks, array $attributes): string {
            $html = e($teks);

            if (!empty($attributes['code'])) {
                $html = '<code>' . $html . '</code>';
            }

            if (!empty($attributes['bold'])) {
                $html = '<strong>' . $html . '</strong>';
            }

            if (!empty($attributes['italic'])) {
                $html = '<em>' . $html . '</em>';
            }

            if (!empty($attributes['underline'])) {
                $html = '<u>' . $html . '</u>';
            }

            if (!empty($attributes['strike'])) {
                $html = '<s>' . $html . '</s>';
            }

            if (!empty($attributes['link'])) {
                $url = $attributes['link'];
                $scheme = strtolower(parse_url($url, PHP_URL_SCHEME) ?? '');

                if (in_array($scheme, ['http', 'https', 'mailto'], true)) {
                    $html = '<a href="' . e($url) . '" target="_blank" rel="noopener noreferrer">'
                        . $html . '</a>';
                }
            }

            return $html;
        };

        // Memecah Delta menjadi baris-baris berdasarkan karakter newline.
        $baris = [];
        $htmlBaris = '';
        $teksBaris = '';

        foreach ($delta['ops'] as $op) {
            if (!isset($op['insert']) || !is_string($op['insert'])) {
                continue;
            }

            $teks = $op['insert'];
            $attributes = $op['attributes'] ?? [];

            $bagian = explode("\n", $teks);

            foreach ($bagian as $index => $potongan) {
                if ($potongan !== '') {
                    $htmlBaris .= $formatTeks($potongan, $attributes);
                    $teksBaris .= $potongan;
                }

                if ($index < count($bagian) - 1) {
                    $baris[] = [
                        'html' => $htmlBaris,
                        'text' => $teksBaris,
                        'attributes' => $attributes,
                    ];

                    $htmlBaris = '';
                    $teksBaris = '';
                }
            }
        }

        // Menyimpan baris terakhir jika masih memiliki isi.
        if ($htmlBaris !== '' || $teksBaris !== '') {
            $baris[] = [
                'html' => $htmlBaris,
                'text' => $teksBaris,
                'attributes' => [],
            ];
        }

        // Menghilangkan baris kosong terakhir akibat newline penutup Delta.
        if (!empty($baris)) {
            $terakhir = end($baris);

            if ($terakhir['html'] === '' && $terakhir['text'] === '') {
                array_pop($baris);
            }
        }

        $hasil = '';
        $jumlahBaris = count($baris);

        for ($i = 0; $i < $jumlahBaris;) {
            $atribut = $baris[$i]['attributes'];

            // Menggabungkan baris-baris code block yang berurutan.
            if (isset($atribut['code-block'])) {
                $isiKode = [];

                while (
                    $i < $jumlahBaris &&
                    isset($baris[$i]['attributes']['code-block'])
                ) {
                    $isiKode[] = e($baris[$i]['text']);
                    $i++;
                }

                $hasil .= '<pre><code>' . implode("\n", $isiKode) . '</code></pre>';
                continue;
            }

            // Menggabungkan daftar bullet atau numbering.
            if (isset($atribut['list'])) {
                $jenisDaftar = $atribut['list'];
                $tagDaftar = $jenisDaftar === 'ordered' ? 'ol' : 'ul';

                $hasil .= '<' . $tagDaftar . '>';

                while (
                    $i < $jumlahBaris &&
                    ($baris[$i]['attributes']['list'] ?? null) === $jenisDaftar
                ) {
                    $hasil .= '<li>' . $baris[$i]['html'] . '</li>';
                    $i++;
                }

                $hasil .= '</' . $tagDaftar . '>';
                continue;
            }

            // Heading.
            if (isset($atribut['header'])) {
                $level = (int) $atribut['header'];

                if ($level >= 1 && $level <= 6) {
                    $hasil .= '<h' . $level . '>'
                        . $baris[$i]['html']
                        . '</h' . $level . '>';
                } else {
                    $hasil .= '<p>' . $baris[$i]['html'] . '</p>';
                }

                $i++;
                continue;
            }

            // Blockquote.
            if (isset($atribut['blockquote'])) {
                $hasil .= '<blockquote>' . $baris[$i]['html'] . '</blockquote>';
                $i++;
                continue;
            }

            // Paragraf biasa atau baris kosong.
            $hasil .= $baris[$i]['html'] !== ''
                ? '<p>' . $baris[$i]['html'] . '</p>'
                : '<p><br></p>';

            $i++;
        }

        return $hasil;
    }
}
