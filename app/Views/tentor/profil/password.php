<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px; padding-top: 4px;">

  <!-- Page Header -->
  <div class="page-header-nav">
    <a href="<?= base_url('tentor/profil') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Ubah Password Tentor</h1>
      <p class="page-subtitle">Perbarui kata sandi akun login dan verifikasi Anda</p>
    </div>
  </div>

  <form action="<?= base_url('tentor/profil/password') ?>" method="POST" class="card" style="padding: 20px; gap: 16px;">
    <?= csrf_field() ?>

    <div class="form-group">
      <label class="form-label">Password Lama</label>
      <div style="position: relative;">
        <input type="password" name="old_password" id="old_password" class="form-input" required placeholder="Masukkan password saat ini" style="padding-right: 44px;">
        <button type="button" class="pw-toggle-btn" onclick="togglePasswordVisibility('old_password', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted);">
          <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
        </button>
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">Password Baru</label>
      <div style="position: relative;">
        <input type="password" name="new_password" id="new_password" class="form-input" required placeholder="Minimal 6 karakter" style="padding-right: 44px;">
        <button type="button" class="pw-toggle-btn" onclick="togglePasswordVisibility('new_password', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted);">
          <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
        </button>
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">Konfirmasi Password Baru</label>
      <div style="position: relative;">
        <input type="password" name="confirm_password" id="confirm_password" class="form-input" required placeholder="Ulangi password baru" style="padding-right: 44px;">
        <button type="button" class="pw-toggle-btn" onclick="togglePasswordVisibility('confirm_password', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted);">
          <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
        </button>
      </div>
    </div>

    <div style="background: var(--warm-cream); border-radius: var(--radius-sm); padding: 12px; font-size: 12px; color: var(--text-muted); line-height: 1.5;">
      💡 Password baru ini akan digunakan untuk login ke TeKaPe.id serta untuk membuka proteksi verifikasi kedua pada menu <strong>Kelola Soal</strong>.
    </div>

    <button type="submit" class="btn btn-primary" style="height: 48px; margin-top: 6px;">
      Simpan Password Baru
    </button>
  </form>

</div>
<?= $this->endSection() ?>
