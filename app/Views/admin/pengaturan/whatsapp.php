<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <div class="page-header-nav">
    <a href="<?= base_url('admin/pengaturan') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Pengaturan WhatsApp</h1>
      <p class="page-subtitle">Nomor kontak resmi bantuan siswa & konsultasi tentor</p>
    </div>
  </div>

  <div class="card" style="padding: 20px;">
    <form action="<?= base_url('admin/pengaturan/saveSettings') ?>" method="post" style="display: flex; flex-direction: column; gap: 14px;">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label">No. WhatsApp Admin (Bantuan & Lupa Akun)</label>
        <input type="text" name="admin_whatsapp" class="form-control" value="<?= esc($settings['admin_whatsapp'] ?? '6281234567890') ?>" placeholder="Format 62812xxxx" required>
        <span style="font-size: 11px; color: var(--text-muted);">Digunakan pada tombol "Hubungi Admin via WhatsApp" di halaman login siswa.</span>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Tentor Resmi</label>
        <input type="text" name="tentor_name" class="form-control" value="<?= esc($settings['tentor_name'] ?? 'Tentor Bima Satria, M.Pd.') ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">No. WhatsApp Tentor (Konsultasi Belajar)</label>
        <input type="text" name="tentor_whatsapp" class="form-control" value="<?= esc($settings['tentor_whatsapp'] ?? '6289876543210') ?>" placeholder="Format 62898xxxx" required>
        <span style="font-size: 11px; color: var(--text-muted);">Digunakan pada menu "Hubungi Tentor" di dashboard dan profil murid.</span>
      </div>

      <button type="submit" class="btn btn-primary" style="margin-top: 6px;">
        Simpan Kontak WhatsApp
      </button>
    </form>
  </div>

</div>
<?= $this->endSection() ?>
