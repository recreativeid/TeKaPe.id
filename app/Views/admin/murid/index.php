<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Page Header -->
  <div class="page-header">
    <h1 class="page-title">Data Murid</h1>
    <p class="page-subtitle">Pusat data siswa, rekapan try out, absensi, dan manajemen akun kredensial.</p>
  </div>

  <!-- Asymmetrical Data Metrics Banner (Clearly Informative & Non-Button) -->
  <div class="student-stats-banner">
    <!-- Asymmetric Top Hero: Total Student Metrics -->
    <div class="student-stats-main">
      <div style="display: flex; flex-direction: column; gap: 2px;">
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted);">
          Total Siswa Terdaftar
        </span>
        <div style="display: flex; align-items: baseline; gap: 6px;">
          <span style="font-family: 'Outfit', sans-serif; font-size: 30px; font-weight: 800; color: var(--dark-navy); line-height: 1;">
            <?= esc($totalMurid) ?>
          </span>
          <span style="font-size: 13px; font-weight: 600; color: var(--text-muted);">Murid Bimbel</span>
        </div>
      </div>

      <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
        <span class="badge badge-navy" style="font-size: 10px; padding: 4px 8px;">
          Database TeKaPe.id
        </span>
        <span style="font-size: 11px; color: var(--text-muted);">Data tersinkronisasi</span>
      </div>
    </div>

    <!-- Asymmetric Subgrid: Segmented Breakdown (Non-Clickable Data Items) -->
    <div class="student-stats-subgrid">
      <div class="student-stat-item" style="border-left: 3px solid #64748B;">
        <span style="font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Akun Free</span>
        <span style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 700; color: var(--dark-navy);"><?= esc($freeMurid) ?></span>
      </div>
      <div class="student-stat-item" style="border-left: 3px solid var(--warm-amber); background: #FFFDF5;">
        <span style="font-size: 10px; font-weight: 700; color: #B45309; text-transform: uppercase;">⭐ Premium</span>
        <span style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 700; color: var(--warm-amber);"><?= esc($premiumMurid) ?></span>
      </div>
      <div class="student-stat-item" style="border-left: 3px solid var(--soft-sage-green); background: #F0FDF4;">
        <span style="font-size: 10px; font-weight: 700; color: #047857; text-transform: uppercase;">🟢 Aktif Belajar</span>
        <span style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 700; color: var(--soft-sage-green);"><?= esc($aktifMurid) ?></span>
      </div>
    </div>
  </div>

  <!-- Section Title for Actions -->
  <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 4px;">
    <h2 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy); margin: 0;">
      Menu & Layanan Siswa
    </h2>
    <span style="font-size: 11px; color: var(--text-muted);">Pilih menu aksi di bawah</span>
  </div>

  <!-- Four Primary Action Cards (Distinct Clickable Buttons) -->
  <div class="action-cards-grid" style="display: flex; flex-direction: column; gap: 12px;">

    <!-- CARD 1: Database Murid -->
    <a href="<?= base_url('admin/murid/database') ?>" class="action-card" style="border-left: 4px solid var(--dark-navy);">
      <div class="action-card-body">
        <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
          <span style="font-size: 16px;">👥</span>
          <h2 class="action-card-title">Database Murid</h2>
        </div>
        <p class="action-card-desc">Lihat seluruh data profil, performa, dan masa berlaku akun murid.</p>
      </div>
      <span class="action-card-arrow">&rarr;</span>
    </a>

    <!-- CARD 2: Nilai Try Out -->
    <a href="<?= base_url('admin/murid/nilai') ?>" class="action-card" style="border-left: 4px solid var(--warm-amber);">
      <div class="action-card-body">
        <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
          <span style="font-size: 16px;">📊</span>
          <h2 class="action-card-title">Nilai Try Out</h2>
        </div>
        <p class="action-card-desc">Pantau skor TWK, TIU, TKP, ranking passing grade, dan penyesuaian nilai manual.</p>
      </div>
      <span class="action-card-arrow">&rarr;</span>
    </a>

    <!-- CARD 3: Absensi -->
    <a href="<?= base_url('admin/murid/absensi') ?>" class="action-card" style="border-left: 4px solid var(--soft-sage-green);">
      <div class="action-card-body">
        <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
          <span style="font-size: 16px;">📅</span>
          <h2 class="action-card-title">Absensi</h2>
        </div>
        <p class="action-card-desc">Lihat kehadiran dan keaktifan murid di kelas bimbingan kedinasan.</p>
      </div>
      <span class="action-card-arrow">&rarr;</span>
    </a>

    <!-- CARD 4: Kelola Akun Siswa (Renamed from Kelola Login Siswa) -->
    <a href="<?= base_url('admin/murid/akunSiswa') ?>" class="action-card" style="border-left: 4px solid #4F46E5; background: linear-gradient(180deg, #FFFFFF 0%, #EEF2FF 100%);">
      <div class="action-card-body">
        <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
          <span style="font-size: 16px;">🔑</span>
          <h2 class="action-card-title" style="color: #3730A3;">Kelola Akun Siswa</h2>
          <span class="badge badge-navy" style="font-size: 10px; background: #4F46E5;">Baru & Lengkap</span>
        </div>
        <p class="action-card-desc">Buat akun siswa baru, lihat username, bantu murid mengubah username atau reset password yang lupa.</p>
      </div>
      <span class="action-card-arrow" style="color: #4F46E5;">&rarr;</span>
    </a>

  </div>

</div>
<?= $this->endSection() ?>
