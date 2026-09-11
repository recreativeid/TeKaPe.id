<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button -->
  <div class="page-header-nav">
    <a href="<?= base_url('admin/murid') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Absensi</h1>
      <p class="page-subtitle">Pantau kehadiran siswa di sesi bimbingan daring</p>
    </div>
  </div>

  <!-- Top Summary Blocks -->
  <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;">
    <div class="stat-box" style="padding: 12px 14px;">
      <span class="stat-label">Hadir</span>
      <span class="stat-value" style="font-size: 20px; color: var(--soft-sage-green);"><?= esc($totalHadir) ?></span>
    </div>
    <div class="stat-box" style="padding: 12px 14px;">
      <span class="stat-label">Tidak Hadir</span>
      <span class="stat-value" style="font-size: 20px; color: var(--soft-peach);"><?= esc($totalTidak) ?></span>
    </div>
    <div class="stat-box" style="padding: 12px 14px;">
      <span class="stat-label">Kehadiran</span>
      <span class="stat-value" style="font-size: 20px;"><?= esc($pctKehadiran) ?></span>
    </div>
  </div>

  <!-- Filters: Tanggal, Mapel, Nama Murid -->
  <div class="card" style="padding: 14px;">
    <form action="<?= base_url('admin/murid/absensi') ?>" method="get" style="display: flex; flex-direction: column; gap: 8px;">
      <div style="display: flex; gap: 6px;">
        <input type="text" name="q" class="form-control" placeholder="Cari nama murid..." value="<?= esc($filterStudent ?? '') ?>" style="height: 38px; font-size: 12px;">
        <select name="subject" class="form-control" style="height: 38px; font-size: 12px; width: 100px;">
          <option value="">Semua Mapel</option>
          <option value="TWK" <?= ($filterSubject === 'TWK') ? 'selected' : '' ?>>TWK</option>
          <option value="TIU" <?= ($filterSubject === 'TIU') ? 'selected' : '' ?>>TIU</option>
          <option value="TKP" <?= ($filterSubject === 'TKP') ? 'selected' : '' ?>>TKP</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm" style="height: 38px; width: 64px;">Filter</button>
      </div>
    </form>
  </div>

  <!-- Attendance Cards List -->
  <div style="display: flex; flex-direction: column; gap: 8px;">
    <?php if (!empty($attendances)): ?>
      <?php foreach ($attendances as $att): ?>
        <div class="card" style="padding: 12px 16px; flex-direction: row; align-items: center; justify-content: space-between;">
          <div style="display: flex; flex-direction: column; gap: 2px;">
            <span style="font-size: 14px; font-weight: 700; color: var(--dark-navy);">
              <?= esc($att['student_name']) ?>
            </span>
            <div style="display: flex; align-items: center; gap: 6px; font-size: 11px; color: var(--text-muted);">
              <span><?= esc($att['subject']) ?></span>
              <span>•</span>
              <span><?= date('d M Y', strtotime($att['date'])) ?></span>
            </div>
          </div>

          <div>
            <?php if ($att['status'] === 'hadir'): ?>
              <span class="badge badge-sage" style="font-size: 11px; padding: 4px 10px;">
                ✓ Hadir
              </span>
            <?php else: ?>
              <span class="badge badge-peach" style="font-size: 11px; padding: 4px 10px;">
                ✕ Tidak Hadir
              </span>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="font-size: 13px; color: var(--text-muted); text-align: center; padding: 24px;">
        Tidak ada catatan absensi sesuai filter.
      </p>
    <?php endif; ?>
  </div>

</div>
<?= $this->endSection() ?>
