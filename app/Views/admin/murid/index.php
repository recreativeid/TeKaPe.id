<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Page Header -->
  <div class="page-header">
    <h1 class="page-title">Data Murid</h1>
    <p class="page-subtitle">Pusat data siswa, rekapan try out, absensi, dan kredensial akun.</p>
  </div>

  <!-- Four Small Summary Blocks (Grid 2x2) -->
  <div class="stats-grid-4">
    <div class="stat-box">
      <span class="stat-label">Total Murid</span>
      <span class="stat-value"><?= esc($totalMurid) ?></span>
    </div>
    <div class="stat-box">
      <span class="stat-label">Akun Free</span>
      <span class="stat-value"><?= esc($freeMurid) ?></span>
    </div>
    <div class="stat-box">
      <span class="stat-label">Akun Premium</span>
      <span class="stat-value" style="color: var(--warm-amber);"><?= esc($premiumMurid) ?></span>
    </div>
    <div class="stat-box">
      <span class="stat-label">Siswa Aktif</span>
      <span class="stat-value" style="color: var(--soft-sage-green);"><?= esc($aktifMurid) ?></span>
    </div>
  </div>

  <!-- Four Primary Action Cards -->
  <div class="action-cards-grid" style="display: flex; flex-direction: column; gap: 12px;">

    <!-- CARD 1: Database Murid -->
    <a href="<?= base_url('admin/murid/database') ?>" class="action-card">
      <div class="action-card-body">
        <h2 class="action-card-title">Database Murid</h2>
        <p class="action-card-desc">Lihat seluruh data profil, performa, dan masa berlaku akun murid.</p>
      </div>
      <span class="action-card-arrow">&rarr;</span>
    </a>

    <!-- CARD 2: Nilai Try Out -->
    <a href="<?= base_url('admin/murid/nilai') ?>" class="action-card">
      <div class="action-card-body">
        <h2 class="action-card-title">Nilai Try Out</h2>
        <p class="action-card-desc">Pantau skor TWK, TIU, TKP, dan penyesuaian nilai manual.</p>
      </div>
      <span class="action-card-arrow">&rarr;</span>
    </a>

    <!-- CARD 3: Absensi -->
    <a href="<?= base_url('admin/murid/absensi') ?>" class="action-card">
      <div class="action-card-body">
        <h2 class="action-card-title">Absensi</h2>
        <p class="action-card-desc">Lihat kehadiran dan keaktifan murid di kelas bimbingan.</p>
      </div>
      <span class="action-card-arrow">&rarr;</span>
    </a>

    <!-- CARD 4: Kelola Login Siswa -->
    <a href="<?= base_url('admin/murid/loginSiswa') ?>" class="action-card">
      <div class="action-card-body">
        <h2 class="action-card-title">Kelola Login Siswa</h2>
        <p class="action-card-desc">Bantu murid mengubah username atau reset password yang lupa.</p>
      </div>
      <span class="action-card-arrow">&rarr;</span>
    </a>

  </div>

</div>
<?= $this->endSection() ?>
