<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button -->
  <div class="page-header-nav">
    <a href="<?= base_url('admin/jadwal') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Edit Jadwal</h1>
      <p class="page-subtitle"><?= $selectedSchedule ? 'Edit: ' . esc($selectedSchedule['subject']) . ' (' . esc($selectedSchedule['day']) . ')' : 'Pilih jadwal yang ingin dimodifikasi' ?></p>
    </div>
  </div>

  <?php if (!$selectedSchedule): ?>
    <!-- Schedule Selector -->
    <div style="display: flex; flex-direction: column; gap: 10px;">
      <?php if (!empty($schedules)): ?>
        <?php foreach ($schedules as $sc): ?>
          <div class="card" style="padding: 14px; gap: 8px;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
              <div style="display: flex; align-items: center; gap: 6px;">
                <span class="badge badge-navy"><?= esc($sc['subject']) ?></span>
                <strong style="color: var(--dark-navy);"><?= esc($sc['day']) ?></strong>
              </div>
              <span class="badge <?= $sc['status'] === 'active' ? 'badge-sage' : 'badge-peach' ?>">
                <?= ucfirst($sc['status']) ?>
              </span>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: var(--text-muted);">
              <span><?= esc($sc['start_time']) ?> – <?= esc($sc['end_time']) ?> WIB • <?= esc($sc['platform']) ?></span>
              <a href="<?= base_url("admin/jadwal/edit/{$sc['id']}") ?>" class="btn btn-primary btn-sm" style="font-weight: 700;">
                Edit &rarr;
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="font-size: 13px; color: var(--text-muted); text-align: center; padding: 24px;">Belum ada jadwal tersimpan.</p>
      <?php endif; ?>
    </div>

  <?php else: ?>
    <!-- Edit Form for Selected Schedule -->
    <div class="card" style="padding: 18px;">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
        <span class="badge badge-navy">Edit Jadwal #<?= $selectedSchedule['id'] ?></span>
        <a href="<?= base_url('admin/jadwal/edit') ?>" style="font-size: 12px; color: var(--text-muted); text-decoration: none;">
          Ganti Jadwal
        </a>
      </div>

      <form action="<?= base_url("admin/jadwal/updateJadwal/{$selectedSchedule['id']}") ?>" method="post" style="display: flex; flex-direction: column; gap: 12px;">
        <?= csrf_field() ?>

        <div class="form-group">
          <label class="form-label">Mata Pelajaran</label>
          <select name="subject" class="form-control" required>
            <option value="TWK" <?= $selectedSchedule['subject'] === 'TWK' ? 'selected' : '' ?>>TWK</option>
            <option value="TIU" <?= $selectedSchedule['subject'] === 'TIU' ? 'selected' : '' ?>>TIU</option>
            <option value="TKP" <?= $selectedSchedule['subject'] === 'TKP' ? 'selected' : '' ?>>TKP</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Hari</label>
          <select name="day" id="schedule-day" class="form-control" required>
            <?php foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day): ?>
              <option value="<?= $day ?>" <?= $selectedSchedule['day'] === $day ? 'selected' : '' ?>><?= $day ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
          <div class="form-group">
            <label class="form-label">Jam Mulai</label>
            <input type="time" name="start_time" id="schedule-start" class="form-control" value="<?= esc($selectedSchedule['start_time']) ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label">Jam Selesai</label>
            <input type="time" name="end_time" id="schedule-end" class="form-control" value="<?= esc($selectedSchedule['end_time']) ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Platform</label>
          <select name="platform" class="form-control">
            <option value="Google Meet" <?= $selectedSchedule['platform'] === 'Google Meet' ? 'selected' : '' ?>>Google Meet</option>
            <option value="Zoom" <?= $selectedSchedule['platform'] === 'Zoom' ? 'selected' : '' ?>>Zoom Meeting</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Meeting URL</label>
          <input type="url" name="meeting_link" class="form-control" value="<?= esc($selectedSchedule['meeting_link']) ?>">
        </div>

        <div class="form-group">
          <label class="form-label">Status</label>
          <select name="status" class="form-control">
            <option value="active" <?= $selectedSchedule['status'] === 'active' ? 'selected' : '' ?>>Aktif</option>
            <option value="inactive" <?= $selectedSchedule['status'] === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 6px;">
          Simpan Perubahan
        </button>

        <a href="<?= base_url("admin/jadwal/deleteJadwal/{$selectedSchedule['id']}") ?>" onclick="return confirm('Hapus sesi jadwal bimbingan ini?')" class="btn btn-outline-danger" style="height: 42px; font-size: 13px;">
          Hapus Jadwal
        </a>
      </form>
    </div>
  <?php endif; ?>

</div>
<?= $this->endSection() ?>
