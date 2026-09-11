<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="auth-card-container" style="display: flex; flex-direction: column; gap: 20px; padding: 10px 0;">

  <div class="page-header-nav">
    <a href="<?= base_url('auth/login?role=murid') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title" style="font-size: 20px;">Daftar sebagai Murid</h1>
      <p class="page-subtitle">Buat akun TeKaPe.id untuk memulai latihan</p>
    </div>
  </div>

  <div class="card" style="border-radius: var(--radius-lg); padding: 22px;">
    <form action="<?= base_url('auth/register') ?>" method="post" style="display: flex; flex-direction: column; gap: 14px;">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label" for="name">Nama Lengkap</label>
        <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Simon Petrus" value="<?= old('name') ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="username">Username</label>
        <input type="text" name="username" id="username" class="form-control" placeholder="Buat username unik" value="<?= old('username') ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="phone_whatsapp">No. WhatsApp</label>
        <input type="text" name="phone_whatsapp" id="phone_whatsapp" class="form-control" placeholder="Contoh: 081234567890" value="<?= old('phone_whatsapp') ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="reg-password">Password</label>
        <div class="input-password-wrapper">
          <input type="password" name="password" id="reg-password" class="form-control" placeholder="Minimal 6 karakter" required>
          <button type="button" class="password-toggle-btn" title="Lihat Password">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
          </button>
        </div>
        <div id="password-strength-container" style="display: none;">
          <div class="password-strength">
            <div class="password-strength-bar"></div>
            <div class="password-strength-bar"></div>
            <div class="password-strength-bar"></div>
            <div class="password-strength-bar"></div>
            <div class="password-strength-bar"></div>
          </div>
          <span class="password-strength-text"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="confirm_password">Konfirmasi Password</label>
        <div class="input-password-wrapper">
          <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Ulangi password" required>
          <button type="button" class="password-toggle-btn" title="Lihat Password">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="margin-top: 8px;">
        Daftar Sekarang
      </button>
    </form>

    <div style="text-align: center; margin-top: 16px; font-size: 13px; color: var(--text-muted);">
      Sudah punya akun? <a href="<?= base_url('auth/login?role=murid') ?>" style="font-weight: 700; color: var(--dark-navy);">Masuk di sini</a>
    </div>
  </div>

</div>
<?= $this->endSection() ?>
