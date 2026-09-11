<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button -->
  <div class="page-header-nav">
    <a href="<?= base_url('admin/jadwal') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Kelola Link Kelas</h1>
      <p class="page-subtitle">Atur tautan Google Meet dan Zoom untuk setiap kelas</p>
    </div>
  </div>

  <!-- List Scheduled Classes with Link Edit -->
  <div style="display: flex; flex-direction: column; gap: 12px;">
    <?php if (!empty($schedules)): ?>
      <?php foreach ($schedules as $sc): ?>
        <div class="card" style="padding: 16px; gap: 10px;">
          
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 6px;">
              <span class="badge badge-navy"><?= esc($sc['subject']) ?></span>
              <strong style="color: var(--dark-navy); font-size: 14px;"><?= esc($sc['day']) ?></strong>
              <span style="font-size: 12px; color: var(--text-muted);">(<?= esc($sc['start_time']) ?>–<?= esc($sc['end_time']) ?>)</span>
            </div>

            <?php if (!empty($sc['meeting_link'])): ?>
              <span class="badge badge-sage" style="font-size: 10px;">Link Aktif</span>
            <?php else: ?>
              <span class="badge badge-peach" style="font-size: 10px;">Link Belum Tersedia</span>
            <?php endif; ?>
          </div>

          <form action="<?= base_url("admin/jadwal/updateLink/{$sc['id']}") ?>" method="post" style="display: flex; flex-direction: column; gap: 8px; border-top: 1px solid var(--border-light); padding-top: 10px;">
            <?= csrf_field() ?>

            <div style="display: flex; gap: 8px;">
              <select name="platform" class="form-control" style="height: 38px; width: 130px; font-size: 12px;">
                <option value="Google Meet" <?= $sc['platform'] === 'Google Meet' ? 'selected' : '' ?>>Google Meet</option>
                <option value="Zoom" <?= $sc['platform'] === 'Zoom' ? 'selected' : '' ?>>Zoom</option>
              </select>

              <input type="url" name="meeting_link" class="form-control" style="height: 38px; font-size: 12px;" placeholder="URL meeting..." value="<?= esc($sc['meeting_link']) ?>">

              <button type="submit" class="btn btn-primary btn-sm" style="height: 38px; width: 80px; font-weight: 600;">
                Simpan
              </button>
            </div>
          </form>

        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="font-size: 13px; color: var(--text-muted); text-align: center; padding: 24px;">Belum ada jadwal tersedia.</p>
    <?php endif; ?>
  </div>

</div>
<?= $this->endSection() ?>
