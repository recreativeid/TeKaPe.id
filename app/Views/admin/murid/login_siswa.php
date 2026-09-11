<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button -->
  <div class="page-header-nav">
    <a href="<?= base_url('admin/murid') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Kelola Login Siswa</h1>
      <p class="page-subtitle">Bantu reset password atau ubah username murid yang lupa akun</p>
    </div>
  </div>

  <!-- Security Notice -->
  <div class="alert alert-warning" style="font-size: 12px;">
    <span>🔒 <strong>Catatan Keamanan:</strong> Seluruh perubahan username dan reset password dicatat pada log sistem. Password yang tersimpan tidak akan ditampilkan dalam teks polos.</span>
  </div>

  <!-- Search Field -->
  <div class="card" style="padding: 14px;">
    <form action="<?= base_url('admin/murid/loginSiswa') ?>" method="get" style="display: flex; gap: 6px;">
      <input type="text" name="q" class="form-control" placeholder="Cari nama atau username murid..." value="<?= esc($search ?? '') ?>" style="height: 40px; font-size: 13px;">
      <button type="submit" class="btn btn-primary btn-sm" style="height: 40px; width: 68px;">Cari</button>
    </form>
  </div>

  <!-- Student List -->
  <div style="display: flex; flex-direction: column; gap: 10px;">
    <?php if (!empty($students)): ?>
      <?php foreach ($students as $st): ?>
        <div class="card" style="padding: 16px; gap: 12px;">
          
          <div style="display: flex; align-items: flex-start; justify-content: space-between;">
            <div>
              <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
                <?= esc($st['name']) ?>
              </h3>
              <span style="font-size: 12px; color: var(--text-muted);">
                Username saat ini: <strong>@<?= esc($st['username']) ?></strong>
              </span>
            </div>

            <span class="badge badge-navy" style="font-size: 10px;">Aktif</span>
          </div>

          <!-- Action Buttons (Ubah Username & Reset Password) -->
          <div style="display: flex; gap: 8px; border-top: 1px solid var(--border-light); padding-top: 10px;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="openUbahUsernameModal(<?= $st['id'] ?>, '<?= esc($st['username']) ?>', '<?= esc($st['name']) ?>')" style="flex: 1; font-weight: 600;">
              Ubah Username
            </button>
            <button type="button" class="btn btn-primary btn-sm" onclick="openResetPasswordModal(<?= $st['id'] ?>, '<?= esc($st['name']) ?>')" style="flex: 1; font-weight: 600;">
              Reset Password
            </button>
          </div>

        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="font-size: 13px; color: var(--text-muted); text-align: center; padding: 24px;">
        Tidak ada data siswa ditemukan.
      </p>
    <?php endif; ?>
  </div>

  <!-- Modal Ubah Username -->
  <div id="modal-ubah-username" class="modal-backdrop">
    <div class="modal-dialog">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
          Ubah Username
        </h3>
        <button type="button" data-modal-close style="background: none; border: none; font-size: 18px; cursor: pointer;">&times;</button>
      </div>

      <form id="form-ubah-username" action="" method="post" style="display: flex; flex-direction: column; gap: 12px;">
        <?= csrf_field() ?>
        <div>
          <span style="font-size: 12px; color: var(--text-muted);">Nama Siswa:</span>
          <strong id="u-student-name" style="display: block; font-size: 14px; color: var(--dark-navy);"></strong>
        </div>

        <div class="form-group">
          <label class="form-label">Username Saat Ini</label>
          <input type="text" id="u-current-username" class="form-control" disabled style="background: #F8FAFC;">
        </div>

        <div class="form-group">
          <label class="form-label">Username Baru</label>
          <input type="text" name="new_username" id="u-new-username" class="form-control" placeholder="Ketik username baru" required>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 4px;">
          Konfirmasi Perubahan
        </button>
      </form>
    </div>
  </div>

  <!-- Modal Reset Password -->
  <div id="modal-reset-password" class="modal-backdrop">
    <div class="modal-dialog">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
          Reset Password Siswa
        </h3>
        <button type="button" data-modal-close style="background: none; border: none; font-size: 18px; cursor: pointer;">&times;</button>
      </div>

      <form id="form-reset-password" action="" method="post" style="display: flex; flex-direction: column; gap: 12px;">
        <?= csrf_field() ?>
        <div>
          <span style="font-size: 12px; color: var(--text-muted);">Nama Siswa:</span>
          <strong id="p-student-name" style="display: block; font-size: 14px; color: var(--dark-navy);"></strong>
        </div>

        <div class="form-group">
          <label class="form-label">Password Baru Sementara</label>
          <div class="input-password-wrapper">
            <input type="password" name="new_password" id="p-new-password" class="form-control" placeholder="Minimal 6 karakter" required>
            <button type="button" class="password-toggle-btn" title="Lihat">
              <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </button>
          </div>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 4px;">
          Simpan Password Baru
        </button>
      </form>
    </div>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function openUbahUsernameModal(id, currentUsername, studentName) {
  document.getElementById('form-ubah-username').action = `<?= base_url('admin/murid/updateStudentUsername/') ?>/${id}`;
  document.getElementById('u-student-name').innerText = studentName;
  document.getElementById('u-current-username').value = currentUsername;
  document.getElementById('u-new-username').value = '';
  document.getElementById('modal-ubah-username').classList.add('open');
}

function openResetPasswordModal(id, studentName) {
  document.getElementById('form-reset-password').action = `<?= base_url('admin/murid/resetStudentPassword/') ?>/${id}`;
  document.getElementById('p-student-name').innerText = studentName;
  document.getElementById('p-new-password').value = '';
  document.getElementById('modal-reset-password').classList.add('open');
}
</script>
<?= $this->endSection() ?>
