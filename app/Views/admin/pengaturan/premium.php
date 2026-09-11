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

  <div class="card" style="padding: 20px; border-color: #FDE68A;">
    <form action="<?= base_url('admin/pengaturan/saveSettings') ?>" method="post" style="display: flex; flex-direction: column; gap: 14px;">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label">Tarif Langganan Default (Rp)</label>
        <input type="number" name="premium_price" class="form-control" value="<?= esc($settings['premium_price'] ?? '149000') ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Durasi Masa Aktif Akses (Hari)</label>
        <input type="number" name="premium_duration_days" class="form-control" value="<?= esc($settings['premium_duration_days'] ?? '30') ?>" required>
      </div>

      <button type="submit" class="btn btn-amber" style="margin-top: 6px;">
        Simpan Tarif Premium
      </button>
    </form>
  </div>

</div>
<?= $this->endSection() ?>
