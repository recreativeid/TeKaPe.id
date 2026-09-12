<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header -->
  <div class="page-header-nav">
    <a href="<?= base_url('tentor/dashboard') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Jadwal Bimbel Saya</h1>
      <p class="page-subtitle">Atur kelas online private & reguler untuk mata pelajaran Anda</p>
    </div>
  </div>

  <!-- Quick Action & Filter -->
  <div style="display: flex; align-items: center; justify-content: space-between;">
    <span style="font-size: 13px; font-weight: 700; color: var(--dark-navy);">
      Total: <?= count($schedules) ?> Sesi Terjadwal
    </span>
    <button type="button" class="btn btn-primary btn-sm" onclick="openModal('add-jadwal-modal')" style="font-weight: 700;">
      + Jadwalkan Bimbel
    </button>
  </div>

  <!-- Schedule List -->
  <div style="display: flex; flex-direction: column; gap: 12px;">
    <?php if (!empty($schedules)): ?>
      <?php foreach ($schedules as $s): ?>
        <div class="card" style="padding: 16px; display: flex; flex-direction: column; gap: 10px;">
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 8px;">
              <span class="badge badge-navy" style="font-size: 11px;"><?= esc($s['subject']) ?></span>
              <strong style="font-size: 14px; color: var(--dark-navy);"><?= esc($s['day']) ?><?= $s['date'] ? ', ' . date('d M Y', strtotime($s['date'])) : '' ?></strong>
            </div>
            <span class="badge badge-sage" style="font-size: 10px;">
              <?= esc($s['platform']) ?>
            </span>
          </div>

          <div style="display: flex; align-items: center; gap: 12px; font-size: 12px; color: var(--text-muted);">
            <span style="display: flex; align-items: center; gap: 4px;">
              <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              <?= esc($s['start_time']) ?> - <?= esc($s['end_time']) ?> WIB
            </span>
            <span>•</span>
            <span style="color: var(--soft-sage-green); font-weight: 600;">Aktif</span>
          </div>

          <?php if (!empty($s['meeting_link'])): ?>
            <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 4px; padding: 8px 10px; display: flex; align-items: center; justify-content: space-between;">
              <span style="font-size: 11px; color: var(--text-muted); text-overflow: ellipsis; overflow: hidden; white-space: nowrap; max-width: 200px;">
                🔗 <?= esc($s['meeting_link']) ?>
              </span>
              <a href="<?= esc($s['meeting_link']) ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" style="height: 28px; font-size: 11px; padding: 0 8px;">
                Buka Link
              </a>
            </div>
          <?php endif; ?>

          <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px; border-top: 1px solid var(--border-light); padding-top: 8px;">
            <button type="button" class="btn btn-secondary btn-sm" onclick='editJadwal(<?= json_encode($s) ?>)' style="height: 30px; font-size: 11px;">
              Edit
            </button>
            <a href="<?= base_url("tentor/jadwal/delete/{$s['id']}") ?>" onclick="return confirm('Yakin ingin menghapus sesi bimbel ini?')" style="font-size: 11px; font-weight: 700; color: #DC2626; text-decoration: none; padding: 4px 8px;">
              Hapus
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="card" style="padding: 24px; text-align: center;">
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 12px;">
          Belum ada jadwal bimbel yang Anda buat. Silakan jadwalkan kelas online perdana Anda.
        </p>
        <button type="button" class="btn btn-primary" onclick="openModal('add-jadwal-modal')">
          + Jadwalkan Sekarang
        </button>
      </div>
    <?php endif; ?>
  </div>

  <!-- MODAL: Tambah / Edit Jadwal -->
  <div id="add-jadwal-modal" class="modal-backdrop">
    <div class="modal-dialog">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
        <h3 id="modal-title" style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
          Jadwalkan Bimbel
        </h3>
        <button type="button" onclick="closeModal('add-jadwal-modal')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
      </div>

      <form id="jadwal-form" action="<?= base_url('tentor/jadwal/tambah') ?>" method="post" style="display: flex; flex-direction: column; gap: 12px;">
        <?= csrf_field() ?>

        <div class="form-group">
          <label class="form-label">Mata Pelajaran</label>
          <select name="subject" id="form-subject" class="form-control">
            <option value="TWK">Tes Wawasan Kebangsaan (TWK)</option>
            <option value="TIU">Tes Inteligensia Umum (TIU)</option>
            <option value="TKP">Tes Karakteristik Pribadi (TKP)</option>
          </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
          <div class="form-group">
            <label class="form-label">Hari</label>
            <select name="day" id="form-day" class="form-control">
              <option value="Senin">Senin</option>
              <option value="Selasa">Selasa</option>
              <option value="Rabu">Rabu</option>
              <option value="Kamis">Kamis</option>
              <option value="Jumat">Jumat</option>
              <option value="Sabtu">Sabtu</option>
              <option value="Minggu">Minggu</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Tanggal (Opsional)</label>
            <input type="date" name="date" id="form-date" class="form-control">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
          <div class="form-group">
            <label class="form-label">Jam Mulai (WIB)</label>
            <input type="time" name="start_time" id="form-start" class="form-control" value="19:00" required>
          </div>
          <div class="form-group">
            <label class="form-label">Jam Selesai (WIB)</label>
            <input type="time" name="end_time" id="form-end" class="form-control" value="20:30" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Platform Meeting</label>
          <select name="platform" id="form-platform" class="form-control">
            <option value="Google Meet">Google Meet</option>
            <option value="Zoom">Zoom</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Link Meeting (Google Meet / Zoom)</label>
          <input type="url" name="meeting_link" id="form-link" class="form-control" placeholder="https://meet.google.com/..." required>
        </div>

        <button type="submit" id="form-submit-btn" class="btn btn-primary" style="margin-top: 6px;">
          Simpan Jadwal Bimbel
        </button>
      </form>
    </div>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function openModal(id) {
  document.getElementById(id).classList.add('open');
}

function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}

function editJadwal(data) {
  document.getElementById('modal-title').innerText = 'Edit Jadwal Bimbel';
  document.getElementById('jadwal-form').action = '<?= base_url('tentor/jadwal/update') ?>/' + data.id;
  document.getElementById('form-subject').value = data.subject;
  document.getElementById('form-day').value = data.day;
  document.getElementById('form-date').value = data.date || '';
  document.getElementById('form-start').value = data.start_time;
  document.getElementById('form-end').value = data.end_time;
  document.getElementById('form-platform').value = data.platform;
  document.getElementById('form-link').value = data.meeting_link;
  document.getElementById('form-submit-btn').innerText = 'Perbarui Jadwal';
  openModal('add-jadwal-modal');
}
</script>
<?= $this->endSection() ?>
