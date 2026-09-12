<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <div class="page-header-nav">
    <a href="<?= base_url('admin/pengaturan') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Payment Gateway</h1>
      <p class="page-subtitle">Konfigurasi integrasi pembayaran otomatis berlangganan</p>
    </div>
  </div>

  <!-- Information Card -->
  <div class="card" style="padding: 16px; background: #F8FAFC; border-color: #E2E8F0; gap: 6px;">
    <div style="display: flex; align-items: center; gap: 8px;">
      <span class="badge badge-navy" style="font-size: 11px;">Midtrans Snap</span>
      <span style="font-size: 13px; font-weight: 700; color: var(--dark-navy);">Otomatisasi Langganan All-Access</span>
    </div>
    <p style="font-size: 12px; color: var(--text-muted); line-height: 1.5; margin: 0;">
      Ketika murid membayar langganan bulanan melalui Midtrans (QRIS, GoPay, Transfer Bank, Kartu Kredit), sistem akan menerima notifikasi otomatis dan <strong>langsung membuka kunci seluruh paket try out premium</strong> bagi murid tersebut.
    </p>
  </div>

  <div class="card" style="padding: 20px;">
    <form action="<?= base_url('admin/pengaturan/saveSettings') ?>" method="post" style="display: flex; flex-direction: column; gap: 16px;">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label" style="font-weight: 700;">Status Gateway Midtrans</label>
        <select name="midtrans_is_active" class="form-control">
          <option value="1" <?= ($settings['midtrans_is_active'] ?? '1') === '1' ? 'selected' : '' ?>>Aktif (Midtrans Snap & Sandbox/Prod Otomatis)</option>
          <option value="0" <?= ($settings['midtrans_is_active'] ?? '1') === '0' ? 'selected' : '' ?>>Nonaktif (Hanya Simulasi Internal)</option>
        </select>
        <span style="font-size: 11px; color: var(--text-muted); margin-top: 4px; display: block;">
          Jika aktif, popup Snap Midtrans akan dipanggil saat murid menekan tombol bayar.
        </span>
      </div>

      <div class="form-group">
        <label class="form-label" style="font-weight: 700;">Environment / Lingkungan</label>
        <select name="midtrans_environment" class="form-control">
          <option value="sandbox" <?= ($settings['midtrans_environment'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' ?>>Sandbox (Uji Coba / Testing)</option>
          <option value="production" <?= ($settings['midtrans_environment'] ?? '') === 'production' ? 'selected' : '' ?>>Production (Live / Transaksi Nyata)</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label" style="font-weight: 700;">Midtrans Merchant ID</label>
        <input type="text" name="midtrans_merchant_id" class="form-control" value="<?= esc($settings['midtrans_merchant_id'] ?? 'G123456789') ?>" placeholder="Contoh: G123456789">
      </div>

      <div class="form-group">
        <label class="form-label" style="font-weight: 700;">Midtrans Client Key</label>
        <input type="text" name="midtrans_client_key" class="form-control" value="<?= esc($settings['midtrans_client_key'] ?? 'SB-Mid-client-sample-key') ?>" placeholder="Contoh: SB-Mid-client-xxxxxxxxxxxx">
        <span style="font-size: 11px; color: var(--text-muted); margin-top: 4px; display: block;">
          Client key digunakan untuk memuat script antarmuka Midtrans Snap pada browser murid.
        </span>
      </div>

      <div class="form-group">
        <label class="form-label" style="font-weight: 700;">Midtrans Server Key</label>
        <input type="text" name="midtrans_server_key" class="form-control" value="<?= esc($settings['midtrans_server_key'] ?? 'SB-Mid-server-sample-key') ?>" placeholder="Contoh: SB-Mid-server-xxxxxxxxxxxx">
        <span style="font-size: 11px; color: var(--text-muted); margin-top: 4px; display: block;">
          Server key digunakan oleh backend TeKaPe.id untuk meminta token transaksi secara aman dan memvalidasi webhook.
        </span>
      </div>

      <!-- Webhook Notification Endpoint Guide -->
      <div style="background: #FFFDF5; border: 1px solid #FEF3C7; padding: 14px; border-radius: var(--radius-sm); display: flex; flex-direction: column; gap: 4px;">
        <span style="font-size: 11px; font-weight: 700; color: #92400E; text-transform: uppercase;">URL Notification / Webhook Midtrans:</span>
        <code style="font-size: 12px; background: #FFFFFF; padding: 6px 10px; border-radius: 4px; border: 1px solid #FDE68A; color: var(--dark-navy); word-break: break-all;">
          <?= base_url('payment/midtrans-notification') ?>
        </code>
        <span style="font-size: 11px; color: #B45309; margin-top: 2px;">
          Salin URL ini ke menu <em>Settings &rarr; Configuration &rarr; Payment Notification URL</em> di dashboard Midtrans Merchant Anda.
        </span>
      </div>

      <button type="submit" class="btn btn-primary" style="margin-top: 6px; height: 46px; font-weight: 700;">
        Simpan Konfigurasi Midtrans
      </button>
    </form>
  </div>

</div>
<?= $this->endSection() ?>
