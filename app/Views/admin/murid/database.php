<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button -->
  <div class="page-header-nav">
    <a href="<?= base_url('admin/murid') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Database Murid</h1>
      <p class="page-subtitle">Daftar seluruh profil siswa terdaftar di TeKaPe.id</p>
    </div>
  </div>

  <!-- Search & Filter Controls -->
  <div class="card" style="padding: 14px; gap: 10px;">
    <form action="<?= base_url('admin/murid/database') ?>" method="get" style="display: flex; gap: 6px;">
      <input type="hidden" name="filter" value="<?= esc($filter) ?>">
      <input type="text" name="q" class="form-control" placeholder="Cari nama atau username..." value="<?= esc($search ?? '') ?>" style="height: 40px; font-size: 13px;">
      <button type="submit" class="btn btn-primary btn-sm" style="height: 40px; width: 68px;">Cari</button>
    </form>

    <!-- Filter Pills -->
    <div style="display: flex; gap: 6px; overflow-x: auto; padding-top: 4px;">
      <a href="<?= base_url('admin/murid/database?filter=semua' . ($search ? "&q={$search}" : '')) ?>" class="day-pill <?= $filter === 'semua' ? 'active' : '' ?>" style="font-size: 11px; padding: 6px 12px;">Semua</a>
      <a href="<?= base_url('admin/murid/database?filter=free' . ($search ? "&q={$search}" : '')) ?>" class="day-pill <?= $filter === 'free' ? 'active' : '' ?>" style="font-size: 11px; padding: 6px 12px;">Free</a>
      <a href="<?= base_url('admin/murid/database?filter=premium' . ($search ? "&q={$search}" : '')) ?>" class="day-pill <?= $filter === 'premium' ? 'active' : '' ?>" style="font-size: 11px; padding: 6px 12px;">Premium</a>
    </div>
  </div>

  <!-- Student Cards List -->
  <div style="display: flex; flex-direction: column; gap: 10px;">
    <?php if (!empty($students)): ?>
      <?php foreach ($students as $st): ?>
        <div class="card" style="padding: 16px; gap: 10px;">
          <div style="display: flex; align-items: flex-start; justify-content: space-between;">
            <div style="display: flex; flex-direction: column; gap: 2px;">
              <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
                <?= esc($st['name']) ?>
              </h3>
              <span style="font-size: 12px; color: var(--text-muted);">
                @<?= esc($st['username']) ?> • WA: <?= esc($st['phone_whatsapp'] ?? '-') ?>
              </span>
            </div>

            <?php if ($st['is_premium']): ?>
              <span class="badge badge-amber">Premium</span>
            <?php else: ?>
              <span class="badge badge-navy">Free</span>
            <?php endif; ?>
          </div>

          <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; background: #F8FAFC; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border-light); font-size: 11px;">
            <div>
              <span style="color: var(--text-muted); display: block;">Masa Berlaku</span>
              <strong style="color: var(--dark-navy);">
                <?= $st['is_premium'] && $st['premium_expiry'] ? date('d M Y', strtotime($st['premium_expiry'])) : 'Akses Free' ?>
              </strong>
            </div>
            <div>
              <span style="color: var(--text-muted); display: block;">Kehadiran</span>
              <strong style="color: var(--soft-sage-green);"><?= $st['attendance_rate'] ?? 92 ?>%</strong>
            </div>
            <div>
              <span style="color: var(--text-muted); display: block;">Skor Terakhir</span>
              <strong style="color: var(--dark-navy);"><?= $st['latest_score'] ? number_format($st['latest_score'], 1) : '-' ?></strong>
            </div>
          </div>

          <div style="display: flex; justify-content: flex-end; margin-top: 2px;">
            <a href="<?= base_url("admin/murid/detail/{$st['id']}") ?>" class="btn btn-secondary btn-sm" style="font-weight: 700; width: 100%;">
              Lihat Detail Murid &rarr;
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="font-size: 13px; color: var(--text-muted); text-align: center; padding: 24px;">
        Tidak ada data murid yang sesuai kriteria pencarian.
      </p>
    <?php endif; ?>
  </div>

</div>
<?= $this->endSection() ?>
