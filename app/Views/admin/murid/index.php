<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 24px;">

  <!-- Page Header -->
  <div class="page-header">
    <h1 class="page-title">Data Murid</h1>
    <p class="page-subtitle">Pusat data siswa, rekapan try out, absensi, dan manajemen akun kredensial.</p>
  </div>

  <!-- ============================================================== -->
  <!-- 1. TAMPILAN DATA STATISTIK: INTEGRATED HORIZONTAL METRIC BAR    -->
  <!-- Bentuk berupa dashboard readout tanpa kotak-kotak kartu tombol   -->
  <!-- ============================================================== -->
  <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 14px; padding: 20px 24px; box-shadow: 0 1px 4px rgba(30, 34, 56, 0.04);">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; align-items: center;">
      
      <!-- Metric 1: Total Siswa -->
      <div style="display: flex; flex-direction: column; gap: 4px; padding: 4px 12px; border-right: 1px solid #F1F5F9;">
        <div style="display: flex; align-items: center; gap: 6px;">
          <span style="display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: var(--dark-navy);"></span>
          <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: var(--text-muted);">
            Total Terdaftar
          </span>
        </div>
        <div style="display: flex; align-items: baseline; gap: 6px;">
          <span style="font-family: 'Outfit', sans-serif; font-size: 32px; font-weight: 800; color: var(--dark-navy); line-height: 1;">
            <?= esc($totalMurid) ?>
          </span>
          <span style="font-size: 12px; font-weight: 600; color: var(--text-muted);">Siswa</span>
        </div>
        <span style="font-size: 11px; color: var(--text-muted);">Database TeKaPe.id</span>
      </div>

      <!-- Metric 2: Akun Free -->
      <div style="display: flex; flex-direction: column; gap: 4px; padding: 4px 12px; border-right: 1px solid #F1F5F9;">
        <div style="display: flex; align-items: center; gap: 6px;">
          <span style="display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: #94A3B8;"></span>
          <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: var(--text-muted);">
            Akun Free
          </span>
        </div>
        <div style="display: flex; align-items: baseline; gap: 6px;">
          <span style="font-family: 'Outfit', sans-serif; font-size: 32px; font-weight: 800; color: #475569; line-height: 1;">
            <?= esc($freeMurid) ?>
          </span>
          <span style="font-size: 12px; font-weight: 600; color: #64748B;">Siswa</span>
        </div>
        <span style="font-size: 11px; color: var(--text-muted);">Akses Try Out Terbatas</span>
      </div>

      <!-- Metric 3: Akun Premium -->
      <div style="display: flex; flex-direction: column; gap: 4px; padding: 4px 12px; border-right: 1px solid #F1F5F9;">
        <div style="display: flex; align-items: center; gap: 6px;">
          <span style="color: var(--warm-amber); font-size: 12px;">★</span>
          <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: var(--warm-amber);">
            Member Premium
          </span>
        </div>
        <div style="display: flex; align-items: baseline; gap: 6px;">
          <span style="font-family: 'Outfit', sans-serif; font-size: 32px; font-weight: 800; color: var(--warm-amber); line-height: 1;">
            <?= esc($premiumMurid) ?>
          </span>
          <span style="font-size: 12px; font-weight: 600; color: var(--warm-amber);">Siswa</span>
        </div>
        <span style="font-size: 11px; color: var(--text-muted);">Akses Penuh Semua Paket</span>
      </div>

      <!-- Metric 4: Aktif Belajar -->
      <div style="display: flex; flex-direction: column; gap: 4px; padding: 4px 12px;">
        <div style="display: flex; align-items: center; gap: 6px;">
          <span style="display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: var(--soft-sage-green);"></span>
          <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: var(--soft-sage-green);">
            Aktif Belajar
          </span>
        </div>
        <div style="display: flex; align-items: baseline; gap: 6px;">
          <span style="font-family: 'Outfit', sans-serif; font-size: 32px; font-weight: 800; color: var(--soft-sage-green); line-height: 1;">
            <?= esc($aktifMurid) ?>
          </span>
          <span style="font-size: 12px; font-weight: 600; color: var(--soft-sage-green);">Siswa</span>
        </div>
        <span style="font-size: 11px; color: var(--text-muted);">Keaktifan Kelas & Try Out</span>
      </div>

    </div>
  </div>

  <!-- Section Title for Actions -->
  <div style="display: flex; align-items: center; justify-content: space-between;">
    <h2 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy); margin: 0;">
      Menu & Layanan Siswa
    </h2>
    <span style="font-size: 12px; color: var(--text-muted);">Pilih menu layanan di bawah</span>
  </div>

  <!-- ============================================================== -->
  <!-- 2. TOMBOL & MENU LAYANAN SISWA: CLEAN INTERACTIVE BUTTON CARDS -->
  <!-- Tanpa garis tebal di samping, murni palet asli, bentuk kontras -->
  <!-- ============================================================== -->
  <div class="action-cards-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">

    <!-- CARD 1: Database Murid -->
    <a href="<?= base_url('admin/murid/database') ?>" class="student-action-btn">
      <div style="display: flex; align-items: center; gap: 14px;">
        <div class="action-btn-icon-wrapper" style="background: #F1F4F9; color: var(--dark-navy);">
          👥
        </div>
        <div style="display: flex; flex-direction: column; gap: 2px;">
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy); margin: 0;">
            Database Murid
          </h3>
          <p style="font-size: 12px; color: var(--text-muted); margin: 0; line-height: 1.35;">
            Data lengkap profil, try out, dan masa aktif.
          </p>
        </div>
      </div>
      <div class="action-btn-arrow">
        &rarr;
      </div>
    </a>

    <!-- CARD 2: Nilai Try Out -->
    <a href="<?= base_url('admin/murid/nilai') ?>" class="student-action-btn">
      <div style="display: flex; align-items: center; gap: 14px;">
        <div class="action-btn-icon-wrapper" style="background: #FFF7ED; color: var(--warm-amber);">
          📊
        </div>
        <div style="display: flex; flex-direction: column; gap: 2px;">
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy); margin: 0;">
            Nilai Try Out
          </h3>
          <p style="font-size: 12px; color: var(--text-muted); margin: 0; line-height: 1.35;">
            Pantau skor TWK, TIU, TKP & passing grade.
          </p>
        </div>
      </div>
      <div class="action-btn-arrow">
        &rarr;
      </div>
    </a>

    <!-- CARD 3: Absensi -->
    <a href="<?= base_url('admin/murid/absensi') ?>" class="student-action-btn">
      <div style="display: flex; align-items: center; gap: 14px;">
        <div class="action-btn-icon-wrapper" style="background: #F0FDF4; color: var(--soft-sage-green);">
          📅
        </div>
        <div style="display: flex; flex-direction: column; gap: 2px;">
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy); margin: 0;">
            Absensi
          </h3>
          <p style="font-size: 12px; color: var(--text-muted); margin: 0; line-height: 1.35;">
            Kehadiran & keaktifan kelas bimbingan.
          </p>
        </div>
      </div>
      <div class="action-btn-arrow">
        &rarr;
      </div>
    </a>

    <!-- CARD 4: Kelola Akun Siswa -->
    <a href="<?= base_url('admin/murid/akunSiswa') ?>" class="student-action-btn">
      <div style="display: flex; align-items: center; gap: 14px;">
        <div class="action-btn-icon-wrapper" style="background: #F8FAFC; color: var(--dark-navy); border: 1px solid #E2E8F0;">
          🔑
        </div>
        <div style="display: flex; flex-direction: column; gap: 2px;">
          <div style="display: flex; align-items: center; gap: 6px;">
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy); margin: 0;">
              Kelola Akun Siswa
            </h3>
            <span class="badge badge-amber" style="font-size: 10px; padding: 2px 6px;">Baru</span>
          </div>
          <p style="font-size: 12px; color: var(--text-muted); margin: 0; line-height: 1.35;">
            Buat akun, ubah username, reset password.
          </p>
        </div>
      </div>
      <div class="action-btn-arrow">
        &rarr;
      </div>
    </a>

  </div>

</div>
<?= $this->endSection() ?>
