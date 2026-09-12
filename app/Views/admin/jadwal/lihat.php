<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button -->
  <div class="page-header-nav">
    <a href="<?= base_url('admin/jadwal') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Jadwal Mingguan</h1>
      <p class="page-subtitle">Kalender kelas daring TeKaPe.id per hari</p>
    </div>
  </div>

  <!-- Day Selector: Senin - Minggu -->
  <div class="day-selector-scroll">
    <?php foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $d): ?>
      <a href="<?= base_url("admin/jadwal/lihat?day={$d}&subject={$filterSubj}") ?>" class="day-pill <?= $selectedDay === $d ? 'active' : '' ?>">
        <?= $d ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- Subject Filter: Semua, TWK, TIU, TKP -->
  <div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
    <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Filter Mapel:</span>
    <?php foreach (['Semua', 'TWK', 'TIU', 'TKP'] as $sub): ?>
      <a href="<?= base_url("admin/jadwal/lihat?day={$selectedDay}&subject={$sub}" . ($selectedTentorId ? "&tentor_id={$selectedTentorId}" : '')) ?>" style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: var(--radius-sm); text-decoration: none; <?= $filterSubj === $sub ? 'background: var(--dark-navy); color: #fff;' : 'background: #FFFFFF; color: var(--text-muted); border: 1px solid var(--border-color);' ?>">
        <?= $sub ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- Tentor Filter for Admin -->
  <?php if (!empty($tentors)): ?>
    <div style="display: flex; align-items: center; gap: 8px;">
      <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Filter Guru:</span>
      <select onchange="location.href='<?= base_url("admin/jadwal/lihat?day={$selectedDay}&subject={$filterSubj}") ?>&tentor_id=' + this.value" class="form-control" style="height: 34px; font-size: 12px; max-width: 250px;">
        <option value="">-- Semua Guru / Tentor --</option>
        <?php foreach ($tentors as $t): ?>
          <option value="<?= $t['id'] ?>" <?= (($selectedTentorId ?? null) == $t['id']) ? 'selected' : '' ?>>
            <?= esc($t['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  <?php endif; ?>

  <!-- WEEKLY TIMETABLE TABLE (Senin - Minggu) -->
  <div style="display: flex; flex-direction: column; gap: 10px;">
    <div style="display: flex; align-items: center; justify-content: space-between;">
      <h2 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy); margin: 0;">
        Tabel Mingguan Keseluruhan (Senin – Minggu)
      </h2>
      <span style="font-size: 11px; color: var(--text-muted);">
        Klik sesi jadwal untuk melihat detail link kelas
      </span>
    </div>

    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; padding-bottom: 8px;">
      <div style="display: grid; grid-template-columns: repeat(7, minmax(160px, 1fr)); gap: 8px; min-width: 1050px;">
        <?php foreach ($days as $d): ?>
          <?php $isSelected = ($d === $selectedDay); ?>
          <div style="display: flex; flex-direction: column; gap: 6px; background: <?= $isSelected ? '#FFFDF5' : '#FAFAFA' ?>; border: 1.5px solid <?= $isSelected ? 'var(--warm-amber)' : 'var(--border-color)' ?>; border-radius: var(--radius-md); padding: 10px 8px;">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid <?= $isSelected ? '#FDE68A' : 'var(--border-light)' ?>; padding-bottom: 6px;">
              <strong style="font-size: 13px; color: var(--dark-navy);"><?= $d ?></strong>
              <span style="font-size: 10px; color: var(--text-muted);"><?= count($weeklySchedules[$d] ?? []) ?> Sesi</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
              <?php if (!empty($weeklySchedules[$d])): ?>
                <?php foreach ($weeklySchedules[$d] as $sc): ?>
                  <div class="schedule-admin-card"
                       onclick="openAdminScheduleModal(<?= htmlspecialchars(json_encode([
                         'subject'      => $sc['subject'],
                         'title'        => 'Kelas Pendalaman ' . $sc['subject'],
                         'day'          => $sc['day'],
                         'time'         => $sc['start_time'] . ' – ' . $sc['end_time'] . ' WIB',
                         'tentor'       => $sc['tentor_name'] ?? 'Tentor',
                         'platform'     => $sc['platform'],
                         'meeting_link' => $sc['meeting_link'],
                       ]), ENT_QUOTES, 'UTF-8') ?>)"
                       style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 8px; cursor: pointer; transition: all 0.15s ease;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2px;">
                      <span class="badge <?= $sc['subject'] === 'TWK' ? 'badge-navy' : ($sc['subject'] === 'TIU' ? 'badge-amber' : 'badge-sage') ?>" style="font-size: 9px; padding: 1px 5px;">
                        <?= esc($sc['subject']) ?>
                      </span>
                      <span style="font-size: 9px; color: #2563EB; font-weight: 700;"><?= esc($sc['platform']) ?></span>
                    </div>
                    <div style="font-size: 11px; font-weight: 700; color: var(--dark-navy); line-height: 1.2;">
                      <?= esc($sc['start_time']) ?> – <?= esc($sc['end_time']) ?>
                    </div>
                    <div style="font-size: 10px; color: var(--text-muted); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                      <?= esc($sc['tentor_name'] ?? 'Tentor') ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <span style="font-size: 10px; color: var(--text-muted); text-align: center; padding: 14px 0;">Kosong</span>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Schedule Detail Cards for Selected Day -->
  <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 6px;">
    <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy); margin: 0;">
      Daftar Sesi Hari <?= esc($selectedDay) ?>
    </h3>

    <?php if (!empty($schedules)): ?>
      <?php foreach ($schedules as $idx => $sc): ?>
        <div class="card <?= $idx === 0 ? 'card-navy' : '' ?>" style="padding: 16px; gap: 8px;">
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 6px;">
              <span class="badge <?= $idx === 0 ? 'badge-amber' : 'badge-navy' ?>" style="font-size: 10px;">
                <?= esc($sc['subject']) ?>
              </span>
              <span class="badge" style="background: rgba(255,255,255,0.15); font-size: 10px; color: <?= $idx === 0 ? '#fff' : 'var(--text-muted)' ?>;">
                <?= esc($sc['platform']) ?>
              </span>
            </div>
            <span class="badge <?= $sc['status'] === 'active' ? 'badge-sage' : 'badge-peach' ?>" style="font-size: 10px;">
              <?= ucfirst($sc['status']) ?>
            </span>
          </div>

          <div>
            <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: <?= $idx === 0 ? '#fff' : 'var(--dark-navy)' ?>; margin-top: 2px;">
              Kelas Pendalaman <?= esc($sc['subject']) ?>
            </h3>
            <p style="font-size: 13px; color: <?= $idx === 0 ? '#CBD5E1' : 'var(--text-muted)' ?>;">
              <?= esc($sc['start_time']) ?> – <?= esc($sc['end_time']) ?> WIB • Hari <?= esc($sc['day']) ?>
              <?php if (!empty($sc['tentor_name'])): ?>
                • <strong style="color: <?= $idx === 0 ? '#FDE68A' : 'var(--warm-amber)' ?>;">Pengajar: <?= esc($sc['tentor_name']) ?></strong>
              <?php endif; ?>
            </p>
          </div>

          <?php if (!empty($sc['meeting_link'])): ?>
            <div style="border-top: 1px solid <?= $idx === 0 ? 'rgba(255,255,255,0.15)' : 'var(--border-light)' ?>; padding-top: 10px; margin-top: 4px; display: flex; align-items: center; justify-content: space-between;">
              <span style="font-size: 11px; color: <?= $idx === 0 ? '#94A3B8' : 'var(--text-muted)' ?>; word-break: break-all; max-width: 240px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                <?= esc($sc['meeting_link']) ?>
              </span>
              <a href="<?= esc($sc['meeting_link']) ?>" target="_blank" class="btn <?= $idx === 0 ? 'btn-secondary' : 'btn-primary' ?> btn-sm" style="height: 32px; font-weight: 700;">
                Masuk Ruang Kelas
              </a>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="font-size: 13px; color: var(--text-muted); text-align: center; padding: 30px;">
        Belum ada jadwal untuk hari <strong><?= esc($selectedDay) ?></strong>.
      </p>
    <?php endif; ?>
  </div>

</div>

<!-- Admin Detail Modal -->
<div id="admin-schedule-modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
  <div style="background: #FFFFFF; border-radius: var(--radius-lg); max-width: 480px; width: 100%; padding: 22px; position: relative; display: flex; flex-direction: column; gap: 14px;">
    <button onclick="closeAdminScheduleModal()" style="position: absolute; right: 16px; top: 16px; background: #F1F5F9; border: none; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-weight: 700;">✕</button>

    <div>
      <span id="adm-modal-subject-badge" class="badge badge-navy" style="font-size: 10px;">TWK</span>
      <h3 id="adm-modal-title" style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: var(--dark-navy); margin: 4px 0 0 0;">Kelas Pendalaman</h3>
    </div>

    <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px; display: flex; flex-direction: column; gap: 8px; font-size: 12px;">
      <div><span style="color: var(--text-muted);">Hari & Jam:</span> <strong id="adm-modal-time" style="color: var(--dark-navy);">Senin, 19:00 - 20:00 WIB</strong></div>
      <div><span style="color: var(--text-muted);">Tentor:</span> <strong id="adm-modal-tentor" style="color: var(--warm-amber);">Bima Sakti, S.Pd</strong></div>
      <div><span style="color: var(--text-muted);">Platform:</span> <strong id="adm-modal-platform" style="color: #2563EB;">Google Meet</strong></div>
    </div>

    <div>
      <span style="font-size: 11px; font-weight: 700; color: var(--dark-navy);">Link Pertemuan:</span>
      <div style="background: #FFFDF5; border: 1px solid #FDE68A; padding: 8px 10px; border-radius: var(--radius-sm); font-size: 11px; color: #1D4ED8; word-break: break-all; margin-top: 4px;">
        <span id="adm-modal-link-text">https://...</span>
      </div>
    </div>

    <div style="display: flex; gap: 8px; margin-top: 4px;">
      <a id="adm-modal-join-btn" href="#" target="_blank" class="btn btn-amber" style="flex: 1; height: 42px; font-size: 13px; font-weight: 700; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 6px;">
        Buka Link Kelas &rarr;
      </a>
      <button onclick="closeAdminScheduleModal()" class="btn btn-secondary" style="height: 42px; font-size: 13px;">Tutup</button>
    </div>
  </div>
</div>

<script>
function openAdminScheduleModal(data) {
  document.getElementById('adm-modal-title').innerText = data.title;
  document.getElementById('adm-modal-subject-badge').innerText = data.subject;
  document.getElementById('adm-modal-subject-badge').className = 'badge ' + (data.subject === 'TWK' ? 'badge-navy' : (data.subject === 'TIU' ? 'badge-amber' : 'badge-sage'));
  document.getElementById('adm-modal-time').innerText = data.day + ', ' + data.time;
  document.getElementById('adm-modal-tentor').innerText = data.tentor;
  document.getElementById('adm-modal-platform').innerText = data.platform;
  document.getElementById('adm-modal-link-text').innerText = data.meeting_link || 'Belum diisi';

  const joinBtn = document.getElementById('adm-modal-join-btn');
  if (data.meeting_link) {
    joinBtn.href = data.meeting_link;
    joinBtn.style.display = 'flex';
  } else {
    joinBtn.style.display = 'none';
  }

  document.getElementById('admin-schedule-modal-overlay').style.display = 'flex';
}

function closeAdminScheduleModal() {
  document.getElementById('admin-schedule-modal-overlay').style.display = 'none';
}

document.getElementById('admin-schedule-modal-overlay').addEventListener('click', function(e) {
  if (e.target === this) closeAdminScheduleModal();
});
</script>

<style>
.schedule-admin-card:hover {
  transform: translateY(-2px);
  border-color: var(--warm-amber) !important;
  box-shadow: 0 4px 10px rgba(0,0,0,0.06) !important;
}
</style>
<?= $this->endSection() ?>
