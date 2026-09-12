<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 24px;">

  <!-- Header -->
  <div class="page-header">
    <h1 class="page-title">Jadwal Bimbingan Mingguan</h1>
    <p class="page-subtitle">Tabel sesi tatap muka online Senin – Minggu. Klik jadwal untuk melihat detail dan link kelas.</p>
  </div>

  <!-- Today's Class Spotlight Card -->
  <?php if ($todayClass): ?>
    <div class="card card-navy" style="border-radius: var(--radius-lg); padding: 22px;">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--warm-amber);">
          Sesi Bimbingan Hari Ini (<?= esc($todayDay) ?>)
        </span>
        <span class="badge" style="background: rgba(255,255,255,0.15); color: #FFFFFF; font-size: 11px;">
          <?= esc($todayClass['platform']) ?>
        </span>
      </div>

      <div style="margin: 6px 0 10px 0;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 22px; font-weight: 800; color: #FFFFFF; margin: 4px 0 2px 0;">
          Bimbel Private <?= esc($todayClass['subject']) ?>
        </h2>
        <p style="font-size: 13px; color: #CBD5E1; margin: 0;">
          ⏱ <strong><?= esc($todayClass['start_time']) ?> – <?= esc($todayClass['end_time']) ?> WIB</strong> • Guru: <strong><?= esc($todayClass['tentor_name'] ?? 'Tentor TeKaPe') ?></strong>
        </p>
      </div>

      <?php if (!empty($todayClass['meeting_link'])): ?>
        <a href="<?= esc($todayClass['meeting_link']) ?>" target="_blank" class="btn" style="background-color: var(--warm-amber); color: #FFFFFF; font-weight: 700; height: 46px; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
          <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
          Masuk <?= esc($todayClass['platform']) ?> Sekarang &rarr;
        </a>
      <?php else: ?>
        <button disabled class="btn" style="background: rgba(255,255,255,0.2); color: #fff;">
          Link Kelas Belum Dibuka
        </button>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <!-- WEEKLY TIMETABLE TABLE (Senin - Minggu) -->
  <div style="display: flex; flex-direction: column; gap: 12px;">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
      <h2 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 700; color: var(--dark-navy); margin: 0;">
        Tabel Jadwal Kelas (Senin – Minggu)
      </h2>
      <span style="font-size: 12px; color: var(--text-muted);">
        💡 Klik pada kartu jadwal untuk membuka detail & link Google Meet / Zoom
      </span>
    </div>

    <!-- Desktop & Tablet Timetable Grid (7 Columns) -->
    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; padding-bottom: 10px;">
      <div style="display: grid; grid-template-columns: repeat(7, minmax(170px, 1fr)); gap: 10px; min-width: 1100px;">
        <?php foreach ($days as $d): ?>
          <?php $isToday = ($d === $todayDay); ?>
          <div style="display: flex; flex-direction: column; gap: 8px; background: <?= $isToday ? '#FFFDF5' : '#FAFAFA' ?>; border: 1.5px solid <?= $isToday ? 'var(--warm-amber)' : 'var(--border-color)' ?>; border-radius: var(--radius-md); padding: 12px 10px;">
            
            <!-- Day Column Header -->
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid <?= $isToday ? '#FDE68A' : 'var(--border-light)' ?>; padding-bottom: 8px;">
              <span style="font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 800; color: var(--dark-navy);">
                <?= $d ?>
              </span>
              <?php if ($isToday): ?>
                <span class="badge badge-amber" style="font-size: 9px; padding: 2px 6px;">Hari Ini</span>
              <?php else: ?>
                <span style="font-size: 11px; color: var(--text-muted); font-weight: 600;">
                  <?= count($weeklySchedules[$d]) ?> Sesi
                </span>
              <?php endif; ?>
            </div>

            <!-- Schedule Items in this day -->
            <div style="display: flex; flex-direction: column; gap: 8px;">
              <?php if (!empty($weeklySchedules[$d])): ?>
                <?php foreach ($weeklySchedules[$d] as $sc): ?>
                  <div class="schedule-item-card" 
                       onclick="openScheduleModal(<?= htmlspecialchars(json_encode([
                         'subject'      => $sc['subject'],
                         'title'        => 'Bimbel Private ' . $sc['subject'],
                         'day'          => $sc['day'],
                         'time'         => $sc['start_time'] . ' – ' . $sc['end_time'] . ' WIB',
                         'tentor'       => $sc['tentor_name'] ?? 'Tentor TeKaPe',
                         'platform'     => $sc['platform'],
                         'meeting_link' => $sc['meeting_link'],
                       ]), ENT_QUOTES, 'UTF-8') ?>)"
                       style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 10px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                    
                    <!-- Time & Subject Badge -->
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
                      <span class="badge <?= $sc['subject'] === 'TWK' ? 'badge-navy' : ($sc['subject'] === 'TIU' ? 'badge-amber' : 'badge-sage') ?>" style="font-size: 9px; padding: 1px 6px;">
                        <?= esc($sc['subject']) ?>
                      </span>
                      <span style="font-size: 10px; font-weight: 700; color: #2563EB;">
                        <?= esc($sc['platform']) ?>
                      </span>
                    </div>

                    <!-- Title & Time -->
                    <h4 style="font-size: 13px; font-weight: 700; color: var(--dark-navy); margin: 2px 0; line-height: 1.3;">
                      Private <?= esc($sc['subject']) ?>
                    </h4>
                    <span style="font-size: 11px; font-weight: 600; color: var(--warm-amber); display: block;">
                      ⏱ <?= esc($sc['start_time']) ?> – <?= esc($sc['end_time']) ?>
                    </span>
                    <span style="font-size: 11px; color: var(--text-muted); display: block; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                      👤 <?= esc($sc['tentor_name'] ?? 'Tentor') ?>
                    </span>

                    <div style="margin-top: 6px; border-top: 1px dashed var(--border-light); padding-top: 5px; font-size: 10px; color: #2563EB; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                      <span>Detail & Link</span> &rarr;
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div style="padding: 24px 8px; text-align: center; color: var(--text-muted); font-size: 11px;">
                  Tidak ada kelas
                </div>
              <?php endif; ?>
            </div>

          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</div>

<!-- INTERACTIVE SCHEDULE DETAIL MODAL -->
<div id="schedule-modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
  <div style="background: #FFFFFF; border-radius: var(--radius-lg); max-width: 500px; width: 100%; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); position: relative; display: flex; flex-direction: column; gap: 16px;">
    
    <!-- Close Button -->
    <button onclick="closeScheduleModal()" style="position: absolute; right: 18px; top: 18px; background: #F1F5F9; border: none; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--dark-navy); font-size: 16px; font-weight: 700;">
      ✕
    </button>

    <!-- Modal Title -->
    <div>
      <span id="modal-subject-badge" class="badge badge-navy" style="font-size: 11px; margin-bottom: 6px;">TWK</span>
      <h3 id="modal-title" style="font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 800; color: var(--dark-navy); margin: 4px 0 0 0;">
        Bimbel Private Online
      </h3>
    </div>

    <!-- Details Box -->
    <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px; display: flex; flex-direction: column; gap: 12px; font-size: 13px;">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <span style="color: var(--text-muted);">Hari & Jam:</span>
        <strong id="modal-time" style="color: var(--dark-navy); font-weight: 700;">Senin, 19:00 - 20:00 WIB</strong>
      </div>
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <span style="color: var(--text-muted);">Guru / Tentor:</span>
        <strong id="modal-tentor" style="color: var(--warm-amber); font-weight: 700;">Bima Sakti, S.Pd</strong>
      </div>
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <span style="color: var(--text-muted);">Platform Kelas:</span>
        <strong id="modal-platform" style="color: #2563EB; font-weight: 700;">Google Meet</strong>
      </div>
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <span style="color: var(--text-muted);">Format:</span>
        <span class="badge badge-sage" style="font-size: 10px;">Interaktif / Tanya Jawab Live</span>
      </div>
    </div>

    <!-- Meeting Link Direct Box -->
    <div id="modal-link-box" style="display: flex; flex-direction: column; gap: 8px;">
      <span style="font-size: 12px; font-weight: 700; color: var(--dark-navy);">Tautan Ruang Kelas:</span>
      <div style="background: #FFFDF5; border: 1px solid #FDE68A; padding: 10px 12px; border-radius: var(--radius-sm); font-size: 12px; color: var(--dark-navy); word-break: break-all; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
        <span id="modal-link-text" style="color: #1D4ED8; font-family: monospace;">https://meet.google.com/...</span>
      </div>
    </div>

    <!-- CTA Button to Join Meeting Directly -->
    <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 4px;">
      <a id="modal-join-btn" href="#" target="_blank" class="btn btn-amber" style="height: 48px; font-size: 14px; font-weight: 700; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        Buka Ruang Kelas (Masuk Sekarang) &rarr;
      </a>
      <button onclick="closeScheduleModal()" class="btn btn-secondary" style="height: 40px; font-size: 13px;">
        Tutup
      </button>
    </div>

  </div>
</div>

<script>
function openScheduleModal(data) {
  document.getElementById('modal-title').innerText = data.title;
  document.getElementById('modal-subject-badge').innerText = data.subject;
  document.getElementById('modal-subject-badge').className = 'badge ' + (data.subject === 'TWK' ? 'badge-navy' : (data.subject === 'TIU' ? 'badge-amber' : 'badge-sage'));
  document.getElementById('modal-time').innerText = data.day + ', ' + data.time;
  document.getElementById('modal-tentor').innerText = data.tentor;
  document.getElementById('modal-platform').innerText = data.platform;
  
  const link = data.meeting_link || '#';
  document.getElementById('modal-link-text').innerText = link;
  
  const joinBtn = document.getElementById('modal-join-btn');
  if (data.meeting_link) {
    joinBtn.href = data.meeting_link;
    joinBtn.style.display = 'flex';
  } else {
    joinBtn.style.display = 'none';
  }

  const modal = document.getElementById('schedule-modal-overlay');
  modal.style.display = 'flex';
}

function closeScheduleModal() {
  document.getElementById('schedule-modal-overlay').style.display = 'none';
}

// Close when clicking background overlay
document.getElementById('schedule-modal-overlay').addEventListener('click', function(e) {
  if (e.target === this) {
    closeScheduleModal();
  }
});
</script>

<style>
.schedule-item-card:hover {
  transform: translateY(-2px);
  border-color: var(--warm-amber) !important;
  box-shadow: 0 4px 12px rgba(217, 130, 43, 0.15) !important;
}
</style>
<?= $this->endSection() ?>
