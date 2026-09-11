<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button -->
  <div class="page-header-nav">
    <a href="<?= base_url('admin/jadwal') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Tambah Jadwal</h1>
      <p class="page-subtitle">Jadwalkan sesi bimbingan baru dengan proteksi bentrok</p>
    </div>
  </div>

  <!-- Realtime Conflict Warning Badge (Hidden by default, updated via JS) -->
  <div id="schedule-conflict-badge" style="display: none;"></div>

  <div class="card" style="padding: 18px;">
    <form action="<?= base_url('admin/jadwal/saveJadwal') ?>" method="post" style="display: flex; flex-direction: column; gap: 14px;">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label">Mata Pelajaran</label>
        <select name="subject" class="form-control" required>
          <option value="TWK">Tes Wawasan Kebangsaan (TWK)</option>
          <option value="TIU">Tes Inteligensia Umum (TIU)</option>
          <option value="TKP">Tes Karakteristik Pribadi (TKP)</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Hari Pembelajaran</label>
        <select name="day" id="schedule-day" class="form-control" required>
          <option value="">Pilih Hari...</option>
          <?php foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day): ?>
            <option value="<?= $day ?>"><?= $day ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
        <div class="form-group">
          <label class="form-label">Jam Mulai</label>
          <input type="time" name="start_time" id="schedule-start" class="form-control" required>
        </div>
        <div class="form-group">
          <label class="form-label">Jam Selesai</label>
          <input type="time" name="end_time" id="schedule-end" class="form-control" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Platform Meeting</label>
        <select name="platform" class="form-control">
          <option value="Google Meet">Google Meet</option>
          <option value="Zoom">Zoom Meeting</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Tautan Meeting (URL)</label>
        <input type="url" name="meeting_link" class="form-control" placeholder="https://meet.google.com/... atau https://zoom.us/..." required>
      </div>

      <div class="form-group">
        <label class="form-label">Status Jadwal</label>
        <select name="status" class="form-control">
          <option value="active" selected>Aktif</option>
          <option value="inactive">Nonaktif</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary" style="margin-top: 6px;">
        Simpan Jadwal
      </button>
    </form>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
window.existingSchedules = <?= json_encode($existing ?? []) ?>;
</script>
<?= $this->endSection() ?>
