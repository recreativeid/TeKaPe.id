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

  <div class="card" style="padding: 20px;">
    <form action="<?= base_url('admin/pengaturan/saveSettings') ?>" method="post" style="display: flex; flex-direction: column; gap: 14px;">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label">Nama Payment Gateway</label>
        <input type="text" name="payment_gateway_name" class="form-control" value="<?= esc($settings['payment_gateway_name'] ?? 'Midtrans / QRIS Otomatis') ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Mode Integrasi</label>
        <select name="payment_gateway_status" class="form-control">
          <option value="active" <?= ($settings['payment_gateway_status'] ?? '') === 'active' ? 'selected' : '' ?>>Simulasi / Instan (Sandbox Otomatis)</option>
          <option value="production" <?= ($settings['payment_gateway_status'] ?? '') === 'production' ? 'selected' : '' ?>>Production (API Key Live)</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Client Key / Merchant ID (Opsional)</label>
        <input type="text" name="payment_client_key" class="form-control" value="<?= esc($settings['payment_client_key'] ?? 'SB-Mid-client-TeKaPeDemo') ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Server Key (Opsional)</label>
        <input type="password" name="payment_server_key" class="form-control" value="<?= esc($settings['payment_server_key'] ?? '••••••••••••') ?>">
      </div>

      <button type="submit" class="btn btn-primary" style="margin-top: 6px;">
        Simpan Konfigurasi Payment
      </button>
    </form>
  </div>

</div>
<?= $this->endSection() ?>
