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
      <h1 class="page-title">Paket Soal Free</h1>
      <p class="page-subtitle">Kelola paket latihan yang dapat diakses semua murid tanpa pembayaran.</p>
    </div>
  </div>

  <!-- Exactly THREE Main Action Cards -->
  <div style="display: flex; flex-direction: column; gap: 12px;">

    <!-- ACTION 1: Tambah Paket -->
    <div class="card" style="padding: 18px;">
      <div style="display: flex; flex-direction: column; gap: 4px; margin-bottom: 8px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
          Tambah Paket
        </h2>
        <p style="font-size: 13px; color: var(--text-muted);">
          Buat paket soal Free baru untuk materi TWK, TIU, atau TKP.
        </p>
      </div>
      <a href="<?= base_url("{$rolePrefix}/soal/free/tambah") ?>" class="btn btn-primary" style="height: 44px; font-size: 13px;">
        + Tambah Paket
      </a>
    </div>

    <!-- ACTION 2: Edit Paket -->
    <div class="card" style="padding: 18px;">
      <div style="display: flex; flex-direction: column; gap: 4px; margin-bottom: 8px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
          Edit Paket
        </h2>
        <p style="font-size: 13px; color: var(--text-muted);">
          Ubah isi, soal, kategori, dan pengaturan paket yang sudah ada.
        </p>
      </div>
      <a href="<?= base_url("{$rolePrefix}/soal/free/edit") ?>" class="btn btn-secondary" style="height: 44px; font-size: 13px;">
        Edit Paket
      </a>
    </div>

    <!-- ACTION 3: Sistem Penilaian -->
    <div class="card" style="padding: 18px;">
      <div style="display: flex; flex-direction: column; gap: 4px; margin-bottom: 8px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
          Sistem Penilaian
        </h2>
        <p style="font-size: 13px; color: var(--text-muted);">
          Atur cara perhitungan nilai soal dan kategori bobot.
        </p>
      </div>
      <a href="<?= base_url("{$rolePrefix}/soal/free/penilaian") ?>" class="btn btn-secondary" style="height: 44px; font-size: 13px;">
        Kelola Penilaian
      </a>
    </div>

  </div>

  <!-- Compact Section: Paket Free Terbaru -->
  <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 6px;">
    <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
      Paket Free Terbaru
    </h3>

    <div class="card" style="padding: 10px 16px; gap: 0;">
      <?php if (!empty($packages)): ?>
        <?php foreach ($packages as $idx => $pkg): ?>
          <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0; <?= $idx > 0 ? 'border-top: 1px solid var(--border-light);' : '' ?>">
            <div style="display: flex; flex-direction: column; gap: 3px;">
              <span style="font-size: 14px; font-weight: 700; color: var(--dark-navy);">
                <?= esc($pkg['title']) ?>
              </span>
              <div style="display: flex; align-items: center; gap: 8px; font-size: 11px; color: var(--text-muted);">
                <span><?= $pkg['question_count'] ?? 0 ?> Soal</span>
                <span>•</span>
                <span class="badge <?= $pkg['status'] === 'active' ? 'badge-sage' : 'badge-peach' ?>" style="padding: 1px 6px; font-size: 10px;">
                  <?= ucfirst($pkg['status']) ?>
                </span>
                <span>•</span>
                <span><?= date('d M Y', strtotime($pkg['created_at'] ?? 'now')) ?></span>
              </div>
            </div>

            <a href="<?= base_url("{$rolePrefix}/soal/free/edit/{$pkg['id']}") ?>" class="btn btn-secondary btn-sm" style="font-weight: 600;">
              Lihat / Edit
            </a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="font-size: 12px; color: var(--text-muted); text-align: center; padding: 16px 0;">
          Belum ada paket soal Free. Klik "Tambah Paket" untuk membuat baru.
        </p>
      <?php endif; ?>
    </div>
  </div>

</div>
<?= $this->endSection() ?>
