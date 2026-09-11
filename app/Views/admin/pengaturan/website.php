<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <div class="page-header-nav">
    <a href="<?= base_url('admin/pengaturan') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Pengaturan Website</h1>
      <p class="page-subtitle">Nama, identitas, dan tagline platform</p>
    </div>
  </div>

  <div class="card" style="padding: 20px;">
    <form action="<?= base_url('admin/pengaturan/saveSettings') ?>" method="post" style="display: flex; flex-direction: column; gap: 14px;">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label">Nama Website</label>
        <input type="text" name="website_name" class="form-control" value="<?= esc($settings['website_name'] ?? 'TeKaPe.id') ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Tagline / Subtitle</label>
        <input type="text" name="website_subtitle" class="form-control" value="<?= esc($settings['website_subtitle'] ?? 'Tempa Karakteristik & Pengetahuan') ?>" required>
      </div>

      <button type="submit" class="btn btn-primary" style="margin-top: 6px;">
        Simpan Perubahan
      </button>
    </form>
  </div>

</div>
<?= $this->endSection() ?>
