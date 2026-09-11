<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <div class="page-header-nav">
    <a href="<?= base_url('admin/pengaturan') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Keamanan Akun</h1>
      <p class="page-subtitle">Kredensial login dan password verifikasi kedua modul soal</p>
    </div>
  </div>

  <div class="card" style="padding: 20px;">
    <form action="<?= base_url('admin/pengaturan/updateSecurity') ?>" method="post" style="display: flex; flex-direction: column; gap: 14px;">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label">Username Admin</label>
        <input type="text" name="username" class="form-control" value="<?= esc($user['username'] ?? 'admin') ?>" required>
      </div>

      <div style="border-top: 1px solid var(--border-light); padding-top: 10px; display: flex; flex-direction: column; gap: 10px;">
        <span style="font-size: 12px; font-weight: 700; color: var(--dark-navy);">Ganti Password Login (Opsional)</span>
        
        <div class="form-group">
          <label class="form-label" style="font-size: 11px;">Password Lama</label>
          <div class="input-password-wrapper">
            <input type="password" name="old_password" class="form-control" placeholder="Password lama">
            <button type="button" class="password-toggle-btn">👁</button>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" style="font-size: 11px;">Password Baru</label>
          <div class="input-password-wrapper">
            <input type="password" name="new_password" class="form-control" placeholder="Password baru">
            <button type="button" class="password-toggle-btn">👁</button>
          </div>
        </div>
      </div>

      <div style="border-top: 1px solid var(--border-light); padding-top: 10px; display: flex; flex-direction: column; gap: 10px;">
        <span style="font-size: 12px; font-weight: 700; color: var(--dark-navy);">Password Verifikasi Kedua (Kelola Soal)</span>
        <div class="form-group">
          <label class="form-label" style="font-size: 11px;">Password Kedua Baru (Kosongkan bila tidak ingin diganti)</label>
          <div class="input-password-wrapper">
            <input type="password" name="secondary_password" class="form-control" placeholder="Password kedua baru">
            <button type="button" class="password-toggle-btn">👁</button>
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="margin-top: 6px;">
        Simpan Keamanan
      </button>
    </form>
  </div>

</div>
<?= $this->endSection() ?>
