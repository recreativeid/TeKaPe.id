<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header -->
  <div class="page-header">
    <h1 class="page-title">Jadwal & Pembelajaran</h1>
    <p class="page-subtitle">Sesi bimbingan tatap muka daring bersama tentor TeKaPe.id</p>
  </div>

  <!-- Weekly Day Selector -->
  <div class="day-selector-scroll">
    <?php foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $d): ?>
      <a href="<?= base_url("murid/jadwal?day={$d}") ?>" class="day-pill <?= $selectedDay === $d ? 'active' : '' ?>">
        <?= $d ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- Today's Class Displayed First in Dark Navy Card -->
  <?php if ($todayClass): ?>
    <div class="card card-navy" style="border-radius: var(--radius-lg); padding: 20px;">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--warm-amber);">
          Bimbingan Hari Ini
        </span>
        <span class="badge" style="background: rgba(255,255,255,0.12); color: #FFFFFF; font-size: 10px;">
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

      <?php if (!empty($todayClass['meeting_link'])): ?>
        <a href="<?= esc($todayClass['meeting_link']) ?>" target="_blank" class="btn" style="background-color: #FFFFFF; color: var(--dark-navy); font-weight: 700; height: 44px; font-size: 14px; margin-top: 4px;">
          Masuk Kelas Sekarang &rarr;
        </a>
      <?php else: ?>
        <button disabled class="btn" style="background: rgba(255,255,255,0.2); color: #fff;">
          Link Kelas Belum Dibuka
        </button>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <!-- Upcoming Schedules for Selected Day -->
  <div style="display: flex; flex-direction: column; gap: 10px;">
    <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
      Jadwal Hari <?= esc($selectedDay) ?>
    </h3>

    <div style="display: flex; flex-direction: column; gap: 8px;">
      <?php if (!empty($daySchedules)): ?>
        <?php foreach ($daySchedules as $sc): ?>
          <div class="card" style="padding: 16px; gap: 8px;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
              <span class="badge badge-navy"><?= esc($sc['subject']) ?></span>
              <span class="badge badge-sage"><?= esc($sc['platform']) ?></span>
            </div>

            <div>
              <h4 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
                Pendalaman Materi <?= esc($sc['subject']) ?>
              </h4>
              <span style="font-size: 12px; color: var(--text-muted);">
                <?= esc($sc['start_time']) ?> – <?= esc($sc['end_time']) ?> WIB
              </span>
            </div>

            <?php if (!empty($sc['meeting_link'])): ?>
              <a href="<?= esc($sc['meeting_link']) ?>" target="_blank" class="btn btn-secondary btn-sm" style="font-weight: 700; height: 36px; margin-top: 4px;">
                Buka Link <?= esc($sc['platform']) ?> &rarr;
              </a>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="font-size: 12px; color: var(--text-muted); text-align: center; padding: 20px;">
          Tidak ada jadwal kelas pada hari <?= esc($selectedDay) ?>.
        </p>
      <?php endif; ?>
    </div>
  </div>

</div>
<?= $this->endSection() ?>
