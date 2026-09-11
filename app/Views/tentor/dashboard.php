<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px; padding-top: 4px;">

  <!-- Welcome Header -->
  <div class="page-header">
    <div style="display: flex; align-items: center; justify-content: space-between;">
      <div>
        <h1 class="page-title" style="font-size: 22px;">Halo, <?= esc($tentor['full_name'] ?? 'Tentor') ?>!</h1>
        <p class="page-subtitle">Workspace Akademik Guru / Tentor TeKaPe.id</p>
      </div>
      <div style="width: 44px; height: 44px; border-radius: 14px; background: var(--warm-cream); display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-light);">
        <span style="font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--dark-navy); font-size: 16px;">
          <?= strtoupper(substr($tentor['full_name'] ?? 'T', 0, 1)) ?>
        </span>
      </div>
    </div>
  </div>

  <!-- Role Notification & Access Restriction Banner (Prompt 37 requirement) -->
  <div class="card" style="padding: 16px; background: #FFFBEB; border-color: #FDE68A; box-shadow: none;">
    <div style="display: flex; gap: 12px; align-items: flex-start;">
      <div style="color: var(--warm-amber); flex-shrink: 0; margin-top: 2px;">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
      </div>
      <div style="display: flex; flex-direction: column; gap: 4px;">
        <span style="font-size: 13px; font-weight: 700; color: #92400E;">Wewenang Khusus Tentor</span>
        <p style="font-size: 12px; color: #78350F; line-height: 1.5; margin: 0;">
          Sebagai Tentor, Anda memiliki hak penuh untuk <strong>Mengelola Paket Soal</strong> (Free & Premium), menyusun soal pilihan ganda & isian, mengunggah gambar, dan mengatur formula penilaian. Menu Data Murid, Jadwal Bimbel, dan Pengaturan Sistem dikelola khusus oleh Admin.
        </p>
      </div>
    </div>
  </div>

  <!-- Quick Stats -->
  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
    <div class="card" style="padding: 16px;">
      <span style="font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Total Paket</span>
      <span style="font-family: 'Outfit', sans-serif; font-size: 26px; font-weight: 700; color: var(--dark-navy); margin-top: 4px;">
        <?= $totalPackages ?? 0 ?>
      </span>
      <span style="font-size: 11px; color: var(--text-muted);">Free & Premium</span>
    </div>

    <div class="card" style="padding: 16px;">
      <span style="font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Butir Soal</span>
      <span style="font-family: 'Outfit', sans-serif; font-size: 26px; font-weight: 700; color: var(--dark-navy); margin-top: 4px;">
        <?= $totalQuestions ?? 0 ?>
      </span>
      <span style="font-size: 11px; color: var(--text-muted);">TWK, TIU, TKP</span>
    </div>
  </div>

  <!-- Main Action Cards -->
  <div style="display: flex; flex-direction: column; gap: 14px;">
    <h2 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
      Akses Utama
    </h2>

    <!-- CARD 1: Kelola Soal -->
    <div class="large-select-card" style="border-left: 4px solid var(--dark-navy);">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <span class="badge badge-navy" style="font-size: 10px;">Akses Penuh</span>
        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--text-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
      </div>

      <div>
        <h3 class="large-select-title" style="font-size: 18px;">Kelola Paket Soal</h3>
        <p class="large-select-desc" style="margin-top: 4px;">
          Buat dan edit paket soal Free & Premium, kelola butir soal, gambar pembahasan, pilihan ganda/isian, serta sistem penilaian kategori TWK, TIU, dan TKP.
        </p>
      </div>

      <a href="<?= base_url('tentor/soal') ?>" class="btn btn-primary" style="margin-top: 4px;">
        Buka Kelola Soal &rarr;
      </a>
      <span style="font-size: 11px; color: var(--text-muted); display: flex; align-items: center; gap: 4px;">
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        Dilindungi verifikasi password sekunder
      </span>
    </div>

    <!-- CARD 2: Profil & Akun -->
    <div class="card" style="padding: 18px;">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 12px;">
          <div style="width: 40px; height: 40px; border-radius: 12px; background: var(--warm-cream); display: flex; align-items: center; justify-content: center; color: var(--dark-navy);">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
          </div>
          <div>
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
              Profil & Akun
            </h3>
            <p style="font-size: 12px; color: var(--text-muted);">
              Perbarui profil dan kata sandi tentor
            </p>
          </div>
        </div>
      </div>

      <div style="display: flex; gap: 8px; margin-top: 14px;">
        <a href="<?= base_url('tentor/profil') ?>" class="btn btn-secondary" style="flex: 1; height: 40px; font-size: 12px;">
          Lihat Profil
        </a>
        <a href="<?= base_url('tentor/profil/password') ?>" class="btn btn-secondary" style="flex: 1; height: 40px; font-size: 12px;">
          Ubah Password
        </a>
      </div>
    </div>
  </div>

  <!-- Logout button -->
  <div style="margin-top: 10px; margin-bottom: 20px;">
    <a href="<?= base_url('auth/logout') ?>" class="btn btn-peach w-100" style="height: 46px; font-size: 13px;">
      Keluar dari Akun Tentor
    </a>
  </div>

</div>
<?= $this->endSection() ?>
