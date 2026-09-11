<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="auth-card-container" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh; gap: 24px; padding: 20px 0;">

  <!-- Brand Logo & Subtitle -->
  <div style="text-align: center; display: flex; flex-direction: column; align-items: center; gap: 6px;">
    <a href="<?= base_url('/') ?>" class="brand-logo" style="font-size: 28px;">
      <span>TeKaPe<span class="brand-dot" style="width: 9px; height: 9px;"></span>id</span>
    </a>
    <p class="page-subtitle" style="font-size: 13px; font-weight: 500;">
      Tempa Karakteristik & Pengetahuan
    </p>
  </div>

  <!-- Role Selection Tabs -->
  <div style="width: 100%;">
    <div class="role-tabs">
      <button type="button" class="role-tab <?= $selectedRole === 'admin' ? 'active' : '' ?>" onclick="switchRole('admin')">
        Admin
      </button>
      <button type="button" class="role-tab <?= $selectedRole === 'tentor' ? 'active' : '' ?>" onclick="switchRole('tentor')">
        Guru / Tentor
      </button>
      <button type="button" class="role-tab <?= $selectedRole === 'murid' ? 'active' : '' ?>" onclick="switchRole('murid')">
        Murid
      </button>
    </div>
  </div>

  <!-- Main Login Card -->
  <div class="card" style="width: 100%; border-radius: var(--radius-lg); padding: 24px;">
    <div style="margin-bottom: 6px;">
      <h1 class="page-title" style="font-size: 20px;">Masuk ke TeKaPe.id</h1>
      <p class="page-subtitle" id="role-subtitle">
        <?php if ($selectedRole === 'admin'): ?>
          Masuk ke portal administrasi & kelola bimbingan.
        <?php elseif ($selectedRole === 'tentor'): ?>
          Masuk ke workspace tentor untuk kelola soal.
        <?php else: ?>
          Masuk ke akun belajar untuk mengerjakan try out.
        <?php endif; ?>
      </p>
    </div>

    <form action="<?= base_url('auth/login') ?>" method="post" style="display: flex; flex-direction: column; gap: 16px;">
      <?= csrf_field() ?>
      <input type="hidden" name="role" id="role-input" value="<?= esc($selectedRole) ?>">

      <div class="form-group">
        <label class="form-label" for="username">Username</label>
        <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" value="<?= old('username') ?>" required autofocus>
      </div>

      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <div class="input-password-wrapper">
          <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
          <button type="button" class="password-toggle-btn" title="Lihat Password">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="margin-top: 6px;">
        Masuk
      </button>
    </form>

    <!-- For Murid: WhatsApp & Register Links -->
    <div id="murid-helpers" style="display: <?= $selectedRole === 'murid' ? 'flex' : 'none' ?>; flex-direction: column; gap: 14px; margin-top: 14px; padding-top: 16px; border-top: 1px solid var(--border-light); text-align: center;">
      <div style="font-size: 13px; color: var(--text-muted);">
        Lupa username atau password? <br>
        <a href="https://wa.me/<?= esc($adminWa) ?>?text=Halo%20Admin%20TeKaPe.id,%20saya%20lupa%20username/password%20akun%20saya" target="_blank" style="color: var(--dark-navy); font-weight: 700; text-decoration: underline;">
          Hubungi Admin via WhatsApp
        </a>
      </div>

      <div style="font-size: 13px; color: var(--text-muted);">
        Belum punya akun? <br>
        <a href="<?= base_url('auth/register') ?>" style="display: inline-block; margin-top: 6px; font-weight: 700; color: var(--warm-amber); text-decoration: none;">
          Daftar sebagai Murid &rarr;
        </a>
      </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function switchRole(role) {
  document.getElementById('role-input').value = role;
  document.querySelectorAll('.role-tab').forEach(tab => tab.classList.remove('active'));
  event.target.classList.add('active');

  const helpers = document.getElementById('murid-helpers');
  const sub = document.getElementById('role-subtitle');
  if (role === 'murid') {
    helpers.style.display = 'flex';
    sub.innerText = 'Masuk ke akun belajar untuk mengerjakan try out.';
  } else if (role === 'tentor') {
    helpers.style.display = 'none';
    sub.innerText = 'Masuk ke workspace tentor untuk kelola soal.';
  } else {
    helpers.style.display = 'none';
    sub.innerText = 'Masuk ke portal administrasi & kelola bimbingan.';
  }
}
</script>
<?= $this->endSection() ?>
