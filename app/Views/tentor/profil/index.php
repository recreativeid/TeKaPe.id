<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px; padding-top: 4px;">

  <!-- Page Header -->
  <div class="page-header-nav">
    <a href="<?= base_url('tentor/dashboard') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Profil Guru / Tentor</h1>
      <p class="page-subtitle">Informasi identitas dan kontak pengajar Anda</p>
    </div>
  </div>

  <!-- Profile Card Info -->
  <div class="card" style="padding: 20px; text-align: center; align-items: center;">
    <div style="width: 64px; height: 64px; border-radius: 20px; background: var(--warm-cream); display: flex; align-items: center; justify-content: center; border: 2px solid var(--border-light); margin-bottom: 8px;">
      <span style="font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 700; color: var(--dark-navy);">
        <?= strtoupper(substr($tentor['full_name'] ?? 'T', 0, 1)) ?>
      </span>
    </div>
    <h2 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 700; color: var(--dark-navy); margin: 0;">
      <?= esc($tentor['full_name'] ?? 'Tentor') ?>
    </h2>
    <span style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">
      @<?= esc($tentor['username'] ?? 'tentor') ?>
    </span>
    <div style="margin-top: 8px;">
      <span class="badge badge-navy">Guru / Tentor Resmi</span>
    </div>
  </div>

  <!-- Edit Profile Form -->
  <form action="<?= base_url('tentor/profil/update') ?>" method="POST" class="card" style="padding: 20px; gap: 16px;">
    <?= csrf_field() ?>

    <h3 style="font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 700; color: var(--dark-navy); margin: 0;">
      Data Akun Tentor
    </h3>

    <div class="form-group">
      <label class="form-label">Username</label>
      <input type="text" class="form-input" value="<?= esc($tentor['username'] ?? '') ?>" disabled style="background: var(--warm-cream); cursor: not-allowed;">
      <span class="form-hint">Username login bersifat tetap dan tidak dapat diubah sendiri.</span>
    </div>

    <div class="form-group">
      <label class="form-label">Nama Lengkap & Gelar</label>
      <input type="text" name="full_name" class="form-input" value="<?= esc($tentor['full_name'] ?? '') ?>" required placeholder="Contoh: Bima Satria, M.Pd.">
    </div>

    <div class="form-group">
      <label class="form-label">Alamat Email</label>
      <input type="email" name="email" class="form-input" value="<?= esc($tentor['email'] ?? '') ?>" placeholder="tentor@tekape.id">
    </div>

    <div class="form-group">
      <label class="form-label">Nomor WhatsApp Aktif</label>
      <input type="text" name="phone_number" class="form-input" value="<?= esc($tentor['phone_number'] ?? '') ?>" placeholder="08xxxxxxxxxx">
      <span class="form-hint">Digunakan murid untuk konsultasi materi bimbel online.</span>
    </div>

    <button type="submit" class="btn btn-primary" style="height: 48px; margin-top: 6px;">
      Simpan Perubahan Profil
    </button>
  </form>

  <!-- Security Settings Link -->
  <div class="card" style="padding: 18px; display: flex; align-items: center; justify-content: space-between;">
    <div style="display: flex; align-items: center; gap: 12px;">
      <div style="width: 38px; height: 38px; border-radius: 12px; background: #FEF3C7; display: flex; align-items: center; justify-content: center; color: var(--warm-amber);">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
      </div>
      <div>
        <h4 style="font-size: 14px; font-weight: 700; color: var(--dark-navy); margin: 0;">Keamanan Sandi</h4>
        <p style="font-size: 12px; color: var(--text-muted); margin: 0;">Ubah password login & soal Anda</p>
      </div>
    </div>
    <a href="<?= base_url('tentor/profil/password') ?>" class="btn btn-secondary btn-sm" style="font-weight: 600;">
      Ubah Sandi &rarr;
    </a>
  </div>

</div>
<?= $this->endSection() ?>
