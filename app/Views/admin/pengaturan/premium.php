<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <div class="page-header-nav">
    <a href="<?= base_url('admin/pengaturan') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Pengaturan Premium</h1>
      <p class="page-subtitle">Tarif standar berlangganan dan masa aktif paket</p>
    </div>
  </div>

  <!-- Info Banner -->
  <div class="card" style="padding: 16px; background: #FFFDF5; border-color: #FDE68A; gap: 6px;">
    <div style="display: flex; align-items: center; gap: 8px;">
      <span class="badge badge-amber" style="font-size: 11px;">Model Langganan Bulanan</span>
      <span style="font-size: 13px; font-weight: 700; color: var(--dark-navy);">Akses Penuh Seluruh Paket Kedinasan</span>
    </div>
    <p style="font-size: 12px; color: var(--text-muted); line-height: 1.5; margin: 0;">
      Admin menentukan tarif per bulan di bawah ini. Ketika murid membayar melalui Midtrans, seluruh paket try out CAT Kedinasan akan <strong>otomatis terbuka kuncinya</strong> selama durasi masa aktif yang ditentukan.
    </p>
  </div>

  <div class="card" style="padding: 20px; border-color: #FDE68A;">
    <form action="<?= base_url('admin/pengaturan/saveSettings') ?>" method="post" style="display: flex; flex-direction: column; gap: 16px;">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label" style="font-weight: 700;">Tarif Langganan Bulanan (Rp)</label>
        <div style="position: relative;">
          <span style="position: absolute; left: 12px; top: 12px; font-weight: 700; color: var(--text-muted); font-size: 14px;">Rp</span>
          <input type="number" name="premium_price" class="form-control" style="padding-left: 40px; font-size: 15px; font-weight: 700; color: var(--dark-navy);" value="<?= esc($settings['premium_price'] ?? '149000') ?>" required>
        </div>
        <span style="font-size: 11px; color: var(--text-muted); margin-top: 4px; display: block;">
          Besaran biaya langganan yang akan ditagihkan ke murid pada saat checkout melalui Midtrans.
        </span>
      </div>

      <div class="form-group">
        <label class="form-label" style="font-weight: 700;">Durasi Masa Aktif Akses (Hari)</label>
        <input type="number" name="premium_duration_days" class="form-control" value="<?= esc($settings['premium_duration_days'] ?? '30') ?>" required>
        <span style="font-size: 11px; color: var(--text-muted); margin-top: 4px; display: block;">
          Standar durasi 30 hari untuk 1 bulan penuh akses.
        </span>
      </div>

      <button type="submit" class="btn btn-amber" style="margin-top: 6px; height: 46px; font-weight: 700;">
        Simpan Tarif Langganan Bulanan
      </button>
    </form>
  </div>

</div>
<?= $this->endSection() ?>
