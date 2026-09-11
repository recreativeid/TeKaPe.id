<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Greeting Header -->
  <div style="display: flex; align-items: center; justify-content: space-between;">
    <div>
      <div style="display: flex; align-items: center; gap: 6px;">
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: var(--text-muted);">
          Dashboard Siswa
        </span>
        <span class="badge badge-sage" style="font-size: 10px;">Selamat belajar!</span>
      </div>
      <h1 class="page-title" style="font-size: 22px; margin-top: 2px;">
        Halo, <?= esc(explode(' ', $student['name'])[0]) ?> 👋
      </h1>
    </div>

    <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--dark-navy); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 16px;">
      <?= strtoupper(substr($student['name'], 0, 1)) ?>
    </div>
  </div>

  <!-- Row 1 on Desktop: Performa Belajar & Tren Nilai -->
  <div class="murid-dashboard-split">
    <!-- Performance Card -->
    <div class="card" style="padding: 18px; gap: 12px;">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 700; color: var(--dark-navy);">
          Performa Belajar
        </h2>
        <a href="<?= base_url('murid/soal') ?>" style="font-size: 12px; font-weight: 600; color: var(--warm-amber); text-decoration: none;">
          Mulai Latihan &rarr;
        </a>
      </div>

      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;">
        <div class="stat-box" style="padding: 10px 12px;">
          <span class="stat-label">Terakhir</span>
          <span class="stat-value" style="font-size: 18px;"><?= $latestScore ? number_format($latestScore, 1) : '-' ?></span>
        </div>
        <div class="stat-box" style="padding: 10px 12px;">
          <span class="stat-label">Tertinggi</span>
          <span class="stat-value" style="font-size: 18px; color: var(--soft-sage-green);"><?= $highestScore ? number_format($highestScore, 1) : '-' ?></span>
        </div>
        <div class="stat-box" style="padding: 10px 12px;">
          <span class="stat-label">Rata-rata</span>
          <span class="stat-value" style="font-size: 18px; color: var(--warm-amber);"><?= $avgScore ? number_format($avgScore, 1) : '-' ?></span>
        </div>
      </div>
    </div>

    <!-- Tren Nilai Try Out (Canvas Line Chart) -->
    <div class="card" style="padding: 16px; gap: 10px;">
      <h3 style="font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 700; color: var(--dark-navy);">
        Tren Nilai Try Out
      </h3>

      <?php 
        $chartPoints = !empty($trendData) ? $trendData : [
          ['score' => 70, 'date' => 'TO 1'],
          ['score' => 76, 'date' => 'TO 2'],
          ['score' => 82.33, 'date' => 'TO 3'],
        ];
        $chartScores = array_map(function($p) { return $p['score']; }, $chartPoints);
        $chartLabels = array_map(function($p) { return $p['date']; }, $chartPoints);
      ?>

      <div class="chart-container" style="height: 140px;">
        <canvas id="trend-chart"></canvas>
      </div>
    </div>
  </div>

  <!-- Row 2 on Desktop: Keaktifan Belajar & Bimbingan / Premium -->
  <div class="murid-dashboard-split">
    <!-- Keaktifan Belajar & Premium Card Column -->
    <div style="display: flex; flex-direction: column; gap: 14px;">
      <!-- Keaktifan Belajar (Attendance) -->
      <div class="card" style="padding: 14px 18px; flex-direction: row; align-items: center; justify-content: space-between;">
        <div style="display: flex; flex-direction: column; gap: 2px;">
          <span style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px;">
            Keaktifan Belajar
          </span>
          <span style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
            <?= $student['attendance_rate'] ?? 92 ?>% Kehadiran
          </span>
        </div>
        <span class="badge badge-sage" style="font-size: 11px; padding: 4px 10px;">Rajin & Konsisten</span>
      </div>

      <!-- Premium Card -->
      <?php if (!empty($student['is_premium'])): ?>
        <div class="card" style="padding: 16px; border-color: var(--soft-sage-border); background: var(--soft-sage-bg);">
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; flex-direction: column; gap: 2px;">
              <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #1B5E2E;">Akses Premium</span>
              <span style="font-size: 13px; font-weight: 700; color: var(--dark-navy);">
                Aktif sampai <?= date('d M Y', strtotime($student['premium_expiry'])) ?>
              </span>
            </div>
            <span class="premium-badge-active">Aktif</span>
          </div>
        </div>
      <?php else: ?>
        <div class="card" style="padding: 16px; border-color: var(--soft-peach-border); background: var(--soft-peach-bg); gap: 8px;">
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; flex-direction: column; gap: 2px;">
              <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #991B1B;">Akses Premium</span>
              <span style="font-size: 13px; font-weight: 700; color: var(--dark-navy);">
                Paket Premium Terkunci
              </span>
            </div>
            <span class="premium-badge-locked">Free</span>
          </div>
          <p style="font-size: 12px; color: var(--text-muted); line-height: 1.4;">
            Tingkatkan ke Premium untuk membuka semua try out kedinasan & pembahasan lengkap.
          </p>
          <a href="<?= base_url('murid/soal/premium') ?>" class="btn btn-amber" style="height: 38px; font-size: 12px; margin-top: 4px;">
            Upgrade Premium &rarr;
          </a>
        </div>
      <?php endif; ?>
    </div>

    <!-- Bimbingan Selanjutnya Column -->
    <div>
      <?php if ($nextClass): ?>
        <div class="card card-navy" style="border-radius: var(--radius-lg); padding: 18px;">
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--warm-amber);">
              Bimbingan Selanjutnya
            </span>
            <span class="badge" style="background: rgba(255,255,255,0.12); color: #FFFFFF; font-size: 10px;">
              <?= esc($nextClass['platform']) ?>
            </span>
          </div>

          <div>
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 700; color: #FFFFFF; margin: 4px 0 2px 0;">
              <?= esc($nextClass['subject']) ?>
            </h2>
            <p class="text-muted" style="font-size: 13px;">
              <?= esc($nextClass['start_time']) ?> – <?= esc($nextClass['end_time']) ?> WIB • <?= esc($nextClass['day']) ?>
            </p>
          </div>

          <?php if (!empty($nextClass['meeting_link'])): ?>
            <a href="<?= esc($nextClass['meeting_link']) ?>" target="_blank" class="btn" style="background-color: #FFFFFF; color: var(--dark-navy); font-weight: 700; height: 42px; font-size: 13px; margin-top: 4px;">
              Masuk Kelas &rarr;
            </a>
          <?php else: ?>
            <button disabled class="btn" style="background-color: rgba(255,255,255,0.2); color: #fff; height: 42px; font-size: 13px;">
              Link Belum Dibuka
            </button>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div class="card" style="padding: 24px; text-align: center; border-radius: var(--radius-lg);">
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">Tidak Ada Sesi Bimbingan</h3>
          <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Lihat jadwal lengkap kelas bimbingan online di menu Jadwal.</p>
          <a href="<?= base_url('murid/jadwal') ?>" class="btn btn-secondary" style="margin-top: 12px;">Lihat Jadwal Saya</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  drawLineChart(
    'trend-chart',
    <?= json_encode(array_values($chartScores)) ?>,
    <?= json_encode(array_values($chartLabels)) ?>
  );
});
</script>
<?= $this->endSection() ?>

