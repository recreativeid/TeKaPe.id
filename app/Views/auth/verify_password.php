<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="auth-card-container" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 70vh; gap: 20px;">

  <div style="width: 56px; height: 56px; border-radius: 50%; background-color: var(--card-white); border: 1.5px solid var(--border-color); display: flex; align-items: center; justify-content: center; color: var(--dark-navy); box-shadow: var(--shadow-sm);">
    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
  </div>

  <div style="text-align: center; display: flex; flex-direction: column; gap: 6px;">
    <h1 class="page-title" style="font-size: 20px;">Konfirmasi Password</h1>
    <p class="page-subtitle" style="max-width: 320px; line-height: 1.5;">
      Masukkan password akun Anda (<strong><?= esc(session()->get('user_name') ?? session()->get('username')) ?></strong>) untuk membuka akses pengelolaan soal.
    </p>
  </div>

  <div class="card" style="width: 100%; border-radius: var(--radius-lg); padding: 22px;">
    <form action="<?= base_url('auth/verify-password') ?>" method="post" style="display: flex; flex-direction: column; gap: 16px;">
      <?= csrf_field() ?>
      <input type="hidden" name="redirect_target" value="<?= esc($redirectTarget) ?>">

      <div class="form-group">
        <label class="form-label" for="verify-password">Password Akun Anda</label>
        <div class="input-password-wrapper">
          <input type="password" name="password" id="verify-password" class="form-control" placeholder="Masukkan password login Anda" required autofocus>
          <button type="button" class="password-toggle-btn" title="Lihat Password">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="margin-top: 4px;">
        Lanjutkan ke Kelola Soal &rarr;
      </button>

      <a href="<?= session()->get('user_role') === 'tentor' ? base_url('tentor/dashboard') : base_url('admin/dashboard') ?>" class="btn btn-secondary" style="height: 42px; font-size: 13px;">
        Batal
      </a>
    </form>
  </div>

</div>
<?= $this->endSection() ?>
