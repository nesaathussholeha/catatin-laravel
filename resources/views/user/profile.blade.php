@extends('layouts.user')

@section('page-title', 'Profil Saya')

@push('styles')
    @vite('resources/css/user-profile.css')
@endpush

@section('content')
    <div class="profil-page">

        {{-- Notifikasi --}}
        @if (session('success') || session('error'))
            <div class="toast-notification {{ session('success') ? 'toast-success' : 'toast-error' }}" id="toastNotification">
                <iconify-icon icon="{{ session('success') ? 'lucide:check-circle' : 'lucide:alert-circle' }}"></iconify-icon>
                <span>{{ session('success') ?? session('error') }}</span>
                <button type="button" class="toast-close" id="btnTutupToast">&times;</button>
            </div>
        @endif

        {{-- Statistik --}}
        <div class="profile-stats">

            <div class="stat-card">
                <div class="stat-icon purple">
                    <iconify-icon icon="lucide:book-open"></iconify-icon>
                </div>
                <div>
                    <span class="stat-label">Catatan Dibagikan</span>
                    <h3>{{ $stats['total_catatan'] ?? 0 }}</h3>
                    <small>Total catatan yang kamu unggah</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange">
                    <iconify-icon icon="lucide:thumbs-up"></iconify-icon>
                </div>
                <div>
                    <span class="stat-label">Total Upvote</span>
                    <h3>{{ $stats['total_upvote'] ?? 0 }}</h3>
                    <small>Apresiasi dari pengguna lain</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">
                    <iconify-icon icon="lucide:award"></iconify-icon>
                </div>
                <div>
                    <span class="stat-label">Status Akun</span>
                    <h3 class="status-account">
                        {{ ucfirst($user->status ?? 'active') }}
                    </h3>
                    <small>Status akun CatatIn kamu</small>
                </div>
            </div>

        </div>

        {{-- Konten profil --}}
        <div class="profile-content">

            {{-- Informasi akun --}}
            <section class="profile-panel">

                <div class="panel-heading">
                    <div class="panel-heading-icon">
                        <iconify-icon icon="lucide:user-round"></iconify-icon>
                    </div>
                    <div>
                        <h2>Informasi Akun</h2>
                        <p>Perbarui informasi pribadi kamu.</p>
                    </div>
                </div>

                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data"
                    id="profileForm">

                    @csrf
                    @method('PUT')

                    {{-- Foto profil --}}
                    <div class="photo-section">

                        <div class="photo-preview">
                            @if ($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto Profil"
                                    id="photoPreview">
                            @else
                                <div class="photo-placeholder" id="photoPlaceholder">
                                    <iconify-icon icon="lucide:user-round"></iconify-icon>
                                </div>

                                <img src="" alt="Preview Foto Profil" id="photoPreview" style="display: none;">
                            @endif
                        </div>

                        <div class="photo-info">
                            <h3>Foto Profil</h3>
                            <p>Gunakan foto JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.</p>

                            <div class="photo-actions">

                                {{-- Pilih foto --}}
                                <label for="profile_photo" class="upload-photo-btn">
                                    <iconify-icon icon="lucide:upload"></iconify-icon>
                                    Pilih Foto
                                </label>

                                <input type="file" name="profile_photo" id="profile_photo" accept=".jpg,.jpeg,.png,.webp"
                                    hidden>

                                {{-- Tombol buka modal hapus foto --}}
                                @if ($user->profile_photo)
                                    <button type="button" class="delete-photo-btn" id="openDeletePhotoModal">
                                        <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                        Hapus Foto
                                    </button>
                                @endif

                            </div>

                            @error('profile_photo')
                                <small class="field-error">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    <div class="form-divider"></div>

                    {{-- Nama lengkap --}}
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <div class="input-wrapper">
                            <iconify-icon icon="lucide:user"></iconify-icon>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                placeholder="Masukkan nama lengkap">
                        </div>
                        @error('name')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-wrapper">
                            <iconify-icon icon="lucide:at-sign"></iconify-icon>
                            <input type="text" name="username" id="username"
                                value="{{ old('username', $user->username) }}" placeholder="Masukkan username">
                        </div>
                        @error('username')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <iconify-icon icon="lucide:mail"></iconify-icon>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                placeholder="Masukkan email">
                        </div>
                        @error('email')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- NIM --}}
                    <div class="form-group">
                        <label for="student_number">Nomor Induk Mahasiswa (NIM)</label>
                        <div class="input-wrapper">
                            <iconify-icon icon="lucide:id-card"></iconify-icon>
                            <input type="text" name="student_number" id="student_number"
                                value="{{ old('student_number', $user->student?->student_number) }}"
                                placeholder="Masukkan NIM">
                        </div>
                        @error('student_number')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Jurusan --}}
                    <div class="form-group">
                        <label for="department_id">Jurusan</label>
                        <div class="input-wrapper">
                            <iconify-icon icon="lucide:graduation-cap"></iconify-icon>
                            <select name="department_id" id="department_id">
                                <option value="">Pilih jurusan</option>

                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" @selected(old('department_id', $user->student?->department_id) == $department->id)>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('department_id')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Tahun masuk --}}
                    <div class="form-group">
                        <label for="enrollment_year">Tahun Masuk</label>
                        <div class="input-wrapper">
                            <iconify-icon icon="lucide:calendar"></iconify-icon>
                            <input type="number" name="enrollment_year" id="enrollment_year"
                                value="{{ old('enrollment_year', $user->student?->enrollment_year) }}"
                                placeholder="Contoh: 2024" min="2000" max="{{ date('Y') }}">
                        </div>
                        @error('enrollment_year')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Jenis kelamin --}}
                    <div class="form-group">
                        <label for="gender">Jenis Kelamin</label>
                        <div class="input-wrapper">
                            <iconify-icon icon="lucide:users"></iconify-icon>
                            <select name="gender" id="gender">
                                <option value="">Pilih jenis kelamin</option>
                                <option value="male" @selected(old('gender', $user->student?->gender) == 'male')>
                                    Laki-laki
                                </option>
                                <option value="female" @selected(old('gender', $user->student?->gender) == 'female')>
                                    Perempuan
                                </option>
                            </select>
                        </div>
                        @error('gender')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="primary-btn">
                            <iconify-icon icon="lucide:save"></iconify-icon>
                            Simpan Perubahan
                        </button>
                    </div>

                </form>

                {{-- Form hapus foto terpisah --}}
                <form action="{{ route('user.profile.photo.delete') }}" method="POST" id="deletePhotoForm"
                    style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>

            </section>

            {{-- Keamanan akun --}}
            <div class="profile-side">

                <section class="profile-panel">

                    <div class="panel-heading">
                        <div class="panel-heading-icon security">
                            <iconify-icon icon="lucide:shield-check"></iconify-icon>
                        </div>
                        <div>
                            <h2>Keamanan Akun</h2>
                            <p>Perbarui password untuk menjaga keamanan akun.</p>
                        </div>
                    </div>

                    <form action="{{ route('user.profile.password') }}" method="POST" id="passwordForm">
                        @csrf
                        @method('PUT')

                        {{-- Password lama --}}
                        <div class="form-group">
                            <label for="current_password">Password Saat Ini</label>
                            <div class="input-wrapper password-wrapper">
                                <iconify-icon icon="lucide:lock"></iconify-icon>
                                <input type="password" name="current_password" id="current_password"
                                    placeholder="Masukkan password saat ini">
                                <button type="button" class="toggle-password" data-target="current_password">
                                    <iconify-icon icon="lucide:eye"></iconify-icon>
                                </button>
                            </div>
                            @error('current_password')
                                <small class="field-error">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Password baru --}}
                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <div class="input-wrapper password-wrapper">
                                <iconify-icon icon="lucide:lock-keyhole"></iconify-icon>
                                <input type="password" name="password" id="password" placeholder="Minimal 8 karakter">
                                <button type="button" class="toggle-password" data-target="password">
                                    <iconify-icon icon="lucide:eye"></iconify-icon>
                                </button>
                            </div>
                            @error('password')
                                <small class="field-error">{{ $message }}</small>
                            @enderror

                            <div class="password-strength">
                                <div class="strength-track">
                                    <div id="strengthBar"></div>
                                </div>
                                <small id="strengthText">Kekuatan password</small>
                            </div>
                        </div>

                        {{-- Konfirmasi password --}}
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <div class="input-wrapper password-wrapper">
                                <iconify-icon icon="lucide:lock-keyhole"></iconify-icon>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    placeholder="Ulangi password baru">
                                <button type="button" class="toggle-password" data-target="password_confirmation">
                                    <iconify-icon icon="lucide:eye"></iconify-icon>
                                </button>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="primary-btn">
                                <iconify-icon icon="lucide:key-round"></iconify-icon>
                                Ubah Password
                            </button>
                        </div>
                    </form>

                </section>

                {{-- Nonaktifkan akun --}}
                <section class="profile-panel danger-panel">

                    <div class="panel-heading">
                        <div class="panel-heading-icon danger">
                            <iconify-icon icon="lucide:alert-triangle"></iconify-icon>
                        </div>
                        <div>
                            <h2>Nonaktifkan Akun</h2>
                            <p>Berhenti menggunakan akun CatatIn.</p>
                        </div>
                    </div>

                    <div class="deactivate-info">
                        <p>
                            Jika akun dinonaktifkan, kamu akan keluar dari akun
                            dan tidak dapat login kembali selama akun belum
                            diaktifkan oleh admin.
                        </p>
                        <p>
                            Data akun dan catatanmu tidak dihapus secara permanen.
                        </p>
                    </div>

                    <button type="button" class="danger-btn" id="openDeactivateModal">
                        <iconify-icon icon="lucide:user-x"></iconify-icon>
                        Nonaktifkan Akun
                    </button>

                </section>

            </div>
        </div>

    </div>

    {{-- Modal konfirmasi nonaktifkan akun --}}
    <div class="profile-modal" id="deactivateModal">
        <div class="profile-modal-content">

            <div class="modal-icon danger">
                <iconify-icon icon="lucide:alert-triangle"></iconify-icon>
            </div>

            <h3>Nonaktifkan Akun?</h3>

            <p>
                Apakah kamu yakin ingin menonaktifkan akun CatatIn?
                Masukkan password untuk mengonfirmasi tindakan ini.
            </p>

            <form action="{{ route('user.profile.destroy') }}" method="POST" id="deactivateForm">
                @csrf
                @method('DELETE')

                <div class="form-group">
                    <label for="deactivate_password">Password Saat Ini</label>
                    <div class="input-wrapper">
                        <iconify-icon icon="lucide:lock"></iconify-icon>
                        <input type="password" name="password" id="deactivate_password" placeholder="Masukkan password">
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="cancel-btn" id="closeDeactivateModal">
                        Batal
                    </button>

                    <button type="submit" class="danger-btn">
                        Ya, Nonaktifkan
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- Modal konfirmasi hapus foto profil --}}
    <div class="profile-modal" id="deletePhotoModal">
        <div class="profile-modal-content">

            <div class="modal-icon danger">
                <iconify-icon icon="lucide:trash-2"></iconify-icon>
            </div>

            <h3>Hapus Foto Profil?</h3>

            <p>
                Apakah kamu yakin ingin menghapus foto profil?
                Foto yang dihapus tidak dapat dikembalikan.
            </p>

            <form action="{{ route('user.profile.photo.delete') }}" method="POST" id="deletePhotoForm">
                @csrf
                @method('DELETE')

                <div class="modal-actions">
                    <button type="button" class="cancel-btn" id="closeDeletePhotoModal">
                        Batal
                    </button>

                    <button type="submit" class="danger-btn">
                        <iconify-icon icon="lucide:trash-2"></iconify-icon>
                        Ya, Hapus Foto
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- Modal konfirmasi ganti password --}}
    <div class="profile-modal" id="changePasswordModal">
        <div class="profile-modal-content">

            <div class="modal-icon danger">
                <iconify-icon icon="lucide:lock-keyhole"></iconify-icon>
            </div>

            <h3>Ganti Password?</h3>

            <p>
                Apakah kamu yakin ingin mengganti password akun CatatIn?
                Pastikan password baru yang dimasukkan sudah benar.
            </p>

            <div class="modal-actions">
                <button type="button" class="cancel-btn" id="closePasswordModal">
                    Batal
                </button>

                <button type="button" class="danger-btn" id="confirmPasswordChange">
                    <iconify-icon icon="lucide:lock-keyhole"></iconify-icon>
                    Ya, Ganti Password
                </button>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    @vite('resources/js/user-profile.js')
@endpush
