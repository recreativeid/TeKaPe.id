<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 75vh; gap: 20px; text-align: center;">

  <!-- Big Checkmark Success Circle with Soft Sage Green Accent -->
  <div style="width: 72px; height: 72px; border-radius: 50%; background-color: var(--soft-sage-bg); border: 2px solid var(--soft-sage-border); display: flex; align-items: center; justify-content: center; color: var(--soft-sage-green); box-shadow: var(--shadow-md);">
    <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
  </div>

  <div>
    <span class="badge badge-sage" style="font-size: 11px; margin-bottom: 6px;">Status: Berhasil</span>
    <h1 class="page-title" style="font-size: 24px;">Pembayaran Berhasil!</h1>
    <p class="page-subtitle" style="font-size: 14px; margin-top: 4px;">
      Akun Premium kamu sudah aktif. Seluruh paket Try Out CAT kini terbuka.
    </p>
  </div>

  <!-- Details Card -->
  <div class="card" style="width: 100%; padding: 20px; text-align: left; gap: 10px;">
    <div style="display: flex; justify-content: space-between; font-size: 13px; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
      <span style="color: var(--text-muted);">Nomor Order:</span>
      <strong style="color: var(--dark-navy);"><?= esc($orderId) ?></strong>
    </div>

    <div style="display: flex; justify-content: space-between; font-size: 13px; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
      <span style="color: var(--text-muted);">Paket:</span>
      <strong style="color: var(--dark-navy);">Akses Premium (<?= esc($duration) ?> Hari)</strong>
    </div>

    <div style="display: flex; justify-content: space-between; font-size: 13px; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
      <span style="color: var(--text-muted);">Total Bayar:</span>
      <strong style="color: var(--warm-amber);">Rp <?= number_format($sub['amount'] ?? 149000, 0, ',', '.') ?></strong>
    </div>

    <div style="display: flex; justify-content: space-between; font-size: 13px;">
      <span style="color: var(--text-muted);">Berlaku Hingga:</span>
      <strong style="color: var(--soft-sage-green);"><?= date('d M Y', strtotime($sub['expired_at'] ?? '+30 days')) ?></strong>
    </div>
  </div>

  <!-- Action Buttons -->
  <div style="width: 100%; display: flex; flex-direction: column; gap: 10px; margin-top: 10px;">
    <a href="<?= base_url('murid/soal/premium') ?>" class="btn btn-primary" style="font-weight: 700;">
      Mulai Belajar & Try Out &rarr;
    </a>
    <a href="<?= base_url('murid/dashboard') ?>" class="btn btn-secondary" style="font-weight: 600;">
      Kembali ke Dashboard
    </a>
  </div>

</div>
<?= $this->endSection() ?>
