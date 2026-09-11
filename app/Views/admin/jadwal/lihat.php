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
  <div style="display: flex; gap: 6px; align-items: center;">
    <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Filter Mapel:</span>
    <?php foreach (['Semua', 'TWK', 'TIU', 'TKP'] as $sub): ?>
      <a href="<?= base_url("admin/jadwal/lihat?day={$selectedDay}&subject={$sub}") ?>" style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: var(--radius-sm); text-decoration: none; <?= $filterSubj === $sub ? 'background: var(--dark-navy); color: #fff;' : 'background: #FFFFFF; color: var(--text-muted); border: 1px solid var(--border-color);' ?>">
        <?= $sub ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- Schedule Cards -->
  <div style="display: flex; flex-direction: column; gap: 10px;">
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
            </p>
          </div>

          <?php if (!empty($sc['meeting_link'])): ?>
            <div style="border-top: 1px solid <?= $idx === 0 ? 'rgba(255,255,255,0.15)' : 'var(--border-light)' ?>; padding-top: 10px; margin-top: 4px; display: flex; align-items: center; justify-content: space-between;">
              <span style="font-size: 11px; color: <?= $idx === 0 ? '#94A3B8' : 'var(--text-muted)' ?>; word-break: break-all; max-width: 240px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                <?= esc($sc['meeting_link']) ?>
              </span>
              <a href="<?= esc($sc['meeting_link']) ?>" target="_blank" class="btn <?= $idx === 0 ? 'btn-secondary' : 'btn-primary' ?> btn-sm" style="height: 32px; font-weight: 700;">
                Masuk
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
<?= $this->endSection() ?>
