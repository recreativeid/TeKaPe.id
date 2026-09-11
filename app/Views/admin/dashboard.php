<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Page Header & Welcome -->
  <div class="page-header">
    <h1 class="page-title">Dashboard Admin</h1>
    <p class="page-subtitle">Kelola pembelajaran, soal, murid, dan jadwal TeKaPe.id.</p>
  </div>

  <!-- Four Compact Statistics (Grid 2x2) -->
  <div class="stats-grid-4">
    <div class="stat-box">
      <span class="stat-label">Total Murid</span>
      <span class="stat-value"><?= esc($totalMurid) ?></span>
    </div>
    <div class="stat-box">
      <span class="stat-label">Murid Premium</span>
      <span class="stat-value"><?= esc($premiumMurid) ?></span>
    </div>
    <div class="stat-box">
      <span class="stat-label">Paket Soal</span>
      <span class="stat-value"><?= esc($totalPaket) ?></span>
    </div>
    <div class="stat-box">
      <span class="stat-label">Kehadiran Hari Ini</span>
      <span class="stat-value" style="color: var(--soft-sage-green);"><?= esc($kehadiranHariIni) ?></span>
    </div>
  </div>

  <!-- Dashboard Main Grid: Bimbingan Hari Ini & Aktivitas Terbaru -->
  <div class="dashboard-split">
    <!-- Prominent Dark Navy Card: Bimbingan Hari Ini -->
    <div>
      <?php if ($todayClass): ?>
        <div class="card card-navy" style="border-radius: var(--radius-lg); padding: 20px;">
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--warm-amber);">
              Bimbingan Hari Ini
            </span>
            <span class="badge" style="background: rgba(255,255,255,0.12); color: #FFFFFF; font-size: 11px;">
              <?= esc($todayClass['platform']) ?>
            </span>
          </div>

          <div>
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 22px; font-weight: 700; color: #FFFFFF; margin: 4px 0 2px 0;">
              <?= esc($todayClass['subject']) ?>
            </h2>
            <p class="text-muted" style="font-size: 13px;">
              <?= esc($todayClass['start_time']) ?> – <?= esc($todayClass['end_time']) ?> WIB • <?= esc($todayClass['day']) ?>
            </p>
          </div>

          <a href="<?= base_url('admin/jadwal') ?>" class="btn" style="background-color: #FFFFFF; color: var(--dark-navy); font-weight: 700; height: 42px; font-size: 13px; margin-top: 4px;">
            Lihat Jadwal &rarr;
          </a>
        </div>
      <?php else: ?>
        <div class="card" style="border-radius: var(--radius-lg); padding: 24px; text-align: center;">
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">Tidak Ada Bimbingan Hari Ini</h3>
          <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Jadwal bimbingan berikutnya dapat dilihat di menu Jadwal.</p>
          <a href="<?= base_url('admin/jadwal') ?>" class="btn btn-secondary" style="margin-top: 12px;">Lihat Jadwal Lengkap</a>
        </div>
      <?php endif; ?>
    </div>

    <!-- Aktivitas Terbaru (Chronological List) -->
    <div style="display: flex; flex-direction: column; gap: 10px;">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
          Aktivitas Terbaru
        </h3>
        <a href="<?= base_url('admin/murid/nilai') ?>" style="font-size: 12px; font-weight: 600; color: var(--warm-amber); text-decoration: none;">
          Lihat Semua
        </a>
      </div>

      <div class="card" style="padding: 12px 16px; gap: 8px;">
        <?php if (!empty($recentActivities)): ?>
          <?php foreach ($recentActivities as $idx => $act): ?>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0; <?= $idx > 0 ? 'border-top: 1px solid var(--border-light);' : '' ?>">
              <div style="display: flex; flex-direction: column; gap: 2px;">
                <span style="font-size: 13px; font-weight: 700; color: var(--dark-navy);">
                  <?= esc($act['student_name']) ?>
                </span>
                <span style="font-size: 11px; color: var(--text-muted);">
                  Menyelesaikan <?= esc($act['package_title']) ?>
                </span>
              </div>

              <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 2px;">
                <span style="font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 700; color: var(--dark-navy);">
                  <?= number_format($act['final_score'], 1) ?>
                </span>
                <span style="font-size: 10px; color: var(--text-muted);">
                  <?= date('d M H:i', strtotime($act['created_at'])) ?>
                </span>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="font-size: 12px; color: var(--text-muted); text-align: center; padding: 12px 0;">
            Belum ada aktivitas try out terbaru hari ini.
          </p>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>
<?= $this->endSection() ?>
