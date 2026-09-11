<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php $rolePrefix = ($role ?? 'admin') === 'tentor' ? 'tentor' : 'admin'; ?>

<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button -->
  <div class="page-header-nav">
    <a href="<?= base_url("{$rolePrefix}/soal") ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Paket Soal Premium</h1>
      <p class="page-subtitle">Kelola paket soal khusus murid dengan akses berlangganan.</p>
    </div>
  </div>

  <!-- Compact Premium Status Section -->
  <div class="card" style="background: linear-gradient(180deg, #FFFFFF 0%, #FFFBEB 100%); border-color: #FDE68A; padding: 16px;">
    <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: var(--warm-amber);">
      Status Layanan Premium
    </span>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-top: 6px;">
      <div style="display: flex; flex-direction: column;">
        <span style="font-size: 11px; color: var(--text-muted);">Harga Standar</span>
        <strong style="font-family: 'Outfit', sans-serif; font-size: 15px; color: var(--dark-navy);">Rp <?= number_format($premiumPrice, 0, ',', '.') ?></strong>
      </div>
      <div style="display: flex; flex-direction: column;">
        <span style="font-size: 11px; color: var(--text-muted);">Durasi Akses</span>
        <strong style="font-family: 'Outfit', sans-serif; font-size: 15px; color: var(--dark-navy);"><?= esc($premiumDuration) ?> Hari</strong>
      </div>
      <div style="display: flex; flex-direction: column;">
        <span style="font-size: 11px; color: var(--text-muted);">Paket Aktif</span>
        <strong style="font-family: 'Outfit', sans-serif; font-size: 15px; color: var(--warm-amber);"><?= esc($activeCount) ?> Paket</strong>
      </div>
    </div>
  </div>

  <!-- Exactly THREE Primary Actions -->
  <div style="display: flex; flex-direction: column; gap: 12px;">

    <!-- ACTION 1: Tambah Paket -->
    <div class="card" style="padding: 18px;">
      <div style="display: flex; flex-direction: column; gap: 4px; margin-bottom: 8px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
          Tambah Paket
        </h2>
        <p style="font-size: 13px; color: var(--text-muted);">
          Buat paket try out Premium baru dengan harga dan durasi khusus.
        </p>
      </div>
      <a href="<?= base_url("{$rolePrefix}/soal/premium/tambah") ?>" class="btn btn-amber" style="height: 44px; font-size: 13px;">
        + Tambah Paket Premium
      </a>
    </div>

    <!-- ACTION 2: Edit Paket -->
    <div class="card" style="padding: 18px;">
      <div style="display: flex; flex-direction: column; gap: 4px; margin-bottom: 8px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
          Edit Paket
        </h2>
        <p style="font-size: 13px; color: var(--text-muted);">
          Kelola bank soal, nomor, opsi jawaban, dan tarif paket Premium.
        </p>
      </div>
      <a href="<?= base_url("{$rolePrefix}/soal/premium/edit") ?>" class="btn btn-secondary" style="height: 44px; font-size: 13px;">
        Edit Paket Premium
      </a>
    </div>

    <!-- ACTION 3: Sistem Penilaian -->
    <div class="card" style="padding: 18px;">
      <div style="display: flex; flex-direction: column; gap: 4px; margin-bottom: 8px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
          Sistem Penilaian
        </h2>
        <p style="font-size: 13px; color: var(--text-muted);">
          Atur skor maksimum per kategori dan formula nilai akhir.
        </p>
      </div>
      <a href="<?= base_url("{$rolePrefix}/soal/premium/penilaian") ?>" class="btn btn-secondary" style="height: 44px; font-size: 13px;">
        Kelola Penilaian Premium
      </a>
    </div>

  </div>

  <!-- Compact Section: Paket Premium Terbaru -->
  <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 6px;">
    <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
      Paket Premium Terbaru
    </h3>

    <div class="card" style="padding: 10px 16px; gap: 0;">
      <?php if (!empty($packages)): ?>
        <?php foreach ($packages as $idx => $pkg): ?>
          <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0; <?= $idx > 0 ? 'border-top: 1px solid var(--border-light);' : '' ?>">
            <div style="display: flex; flex-direction: column; gap: 3px;">
              <span style="font-size: 14px; font-weight: 700; color: var(--dark-navy);">
                <?= esc($pkg['title']) ?>
              </span>
              <div style="display: flex; align-items: center; gap: 6px; font-size: 11px; color: var(--text-muted);">
                <span><?= $pkg['question_count'] ?? 0 ?> Soal</span>
                <span>•</span>
                <span style="color: var(--warm-amber); font-weight: 700;">Rp <?= number_format($pkg['price'], 0, ',', '.') ?></span>
                <span>•</span>
                <span class="badge <?= $pkg['status'] === 'active' ? 'badge-sage' : 'badge-peach' ?>" style="padding: 1px 6px; font-size: 10px;">
                  <?= ucfirst($pkg['status']) ?>
                </span>
              </div>
            </div>

            <a href="<?= base_url("{$rolePrefix}/soal/premium/edit/{$pkg['id']}") ?>" class="btn btn-secondary btn-sm" style="font-weight: 600;">
              Lihat / Edit
            </a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="font-size: 12px; color: var(--text-muted); text-align: center; padding: 16px 0;">
          Belum ada paket Premium. Klik "+ Tambah Paket Premium" untuk membuat.
        </p>
      <?php endif; ?>
    </div>
  </div>

</div>
<?= $this->endSection() ?>
