<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Page Header -->
  <div class="page-header">
    <h1 class="page-title">Kelola Jadwal Bimbel</h1>
    <p class="page-subtitle">Atur kalender belajar, jam kelas daring, dan tautan live meeting.</p>
  </div>

  <!-- Four Main Action Cards -->
  <div class="action-cards-grid" style="display: flex; flex-direction: column; gap: 12px;">
    
    <!-- Action 1: Lihat Jadwal -->
    <a href="<?= base_url('admin/jadwal/lihat') ?>" class="action-card">
      <div class="action-card-body">
        <h2 class="action-card-title">Lihat Jadwal</h2>
        <p class="action-card-desc">Lihat seluruh jadwal pembelajaran mingguan (Senin - Minggu).</p>
      </div>
      <span class="action-card-arrow">&rarr;</span>
    </a>

    <!-- Action 2: Tambah Jadwal -->
    <a href="<?= base_url('admin/jadwal/tambah') ?>" class="action-card">
      <div class="action-card-body">
        <h2 class="action-card-title">Tambah Jadwal</h2>
        <p class="action-card-desc">Buat jadwal baru TWK, TIU, atau TKP dengan validasi anti-bentrok.</p>
      </div>
      <span class="action-card-arrow">&rarr;</span>
    </a>

    <!-- Action 3: Edit Jadwal -->
    <a href="<?= base_url('admin/jadwal/edit') ?>" class="action-card">
      <div class="action-card-body">
        <h2 class="action-card-title">Edit Jadwal</h2>
        <p class="action-card-desc">Ubah hari, jam mulai/selesai, mapel, atau hapus sesi bimbingan.</p>
      </div>
      <span class="action-card-arrow">&rarr;</span>
    </a>

    <!-- Action 4: Kelola Link Kelas -->
    <a href="<?= base_url('admin/jadwal/link') ?>" class="action-card">
      <div class="action-card-body">
        <h2 class="action-card-title">Kelola Link Kelas</h2>
        <p class="action-card-desc">Perbarui tautan Zoom dan Google Meet untuk setiap mata pelajaran.</p>
      </div>
      <span class="action-card-arrow">&rarr;</span>
    </a>

  </div>

  <!-- Compact: Jadwal Hari Ini -->
  <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 4px;">
    <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
      Jadwal Hari Ini (<?= esc($todayDay) ?>)
    </h3>

    <div class="card" style="padding: 12px 16px; gap: 8px;">
      <?php if (!empty($todayClasses)): ?>
        <?php foreach ($todayClasses as $idx => $cls): ?>
          <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0; <?= $idx > 0 ? 'border-top: 1px solid var(--border-light);' : '' ?>">
            <div>
              <div style="display: flex; align-items: center; gap: 6px;">
                <span style="font-weight: 800; font-size: 15px; color: var(--dark-navy);"><?= esc($cls['subject']) ?></span>
                <span class="badge badge-navy" style="font-size: 10px;"><?= esc($cls['platform']) ?></span>
              </div>
              <span style="font-size: 12px; color: var(--text-muted);">
                <?= esc($cls['start_time']) ?> – <?= esc($cls['end_time']) ?> WIB
              </span>
            </div>

            <?php if (!empty($cls['meeting_link'])): ?>
              <a href="<?= esc($cls['meeting_link']) ?>" target="_blank" class="btn btn-primary btn-sm" style="font-weight: 600;">
                Buka Link
              </a>
            <?php else: ?>
              <span class="badge badge-peach" style="font-size: 10px;">Link Belum Ada</span>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="font-size: 12px; color: var(--text-muted); text-align: center; padding: 12px 0;">
          Tidak ada jadwal bimbingan untuk hari ini (<?= esc($todayDay) ?>).
        </p>
      <?php endif; ?>
    </div>
  </div>

</div>
<?= $this->endSection() ?>
