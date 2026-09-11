<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button -->
  <div class="page-header-nav">
    <a href="<?= base_url('admin/dashboard') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Pengaturan Sistem</h1>
      <p class="page-subtitle">Konfigurasi platform, kontak, tarif, dan keamanan</p>
    </div>
  </div>

  <!-- Exactly 5 Separate Cards Opening Dedicated Pages -->
  <div class="action-cards-grid" style="display: flex; flex-direction: column; gap: 12px;">

    <!-- CARD 1: Pengaturan Website -->
    <a href="<?= base_url('admin/pengaturan/website') ?>" class="action-card">
      <div class="action-card-body">
        <h2 class="action-card-title">Pengaturan Website</h2>
        <p class="action-card-desc">Nama platform, slogan, dan informasi umum TeKaPe.id.</p>
      </div>
      <span class="action-card-arrow">&rarr;</span>
    </a>

    <!-- CARD 2: Pengaturan WhatsApp -->
    <a href="<?= base_url('admin/pengaturan/whatsapp') ?>" class="action-card">
      <div class="action-card-body">
        <h2 class="action-card-title">Pengaturan WhatsApp</h2>
        <p class="action-card-desc">Nomor WhatsApp resmi admin dan tentor untuk layanan bantuan murid.</p>
      </div>
      <span class="action-card-arrow">&rarr;</span>
    </a>

    <!-- CARD 3: Payment Gateway -->
    <a href="<?= base_url('admin/pengaturan/payment') ?>" class="action-card">
      <div class="action-card-body">
        <h2 class="action-card-title">Payment Gateway</h2>
        <p class="action-card-desc">Konfigurasi kanal pembayaran QRIS, e-wallet, dan virtual account.</p>
      </div>
      <span class="action-card-arrow">&rarr;</span>
    </a>

    <!-- CARD 4: Pengaturan Premium -->
    <a href="<?= base_url('admin/pengaturan/premium') ?>" class="action-card">
      <div class="action-card-body">
        <h2 class="action-card-title">Pengaturan Premium</h2>
        <p class="action-card-desc">Tarif langganan standar dan durasi masa aktif akses paket Premium.</p>
      </div>
      <span class="action-card-arrow">&rarr;</span>
    </a>

    <!-- CARD 5: Keamanan Akun -->
    <a href="<?= base_url('admin/pengaturan/keamanan') ?>" class="action-card">
      <div class="action-card-body">
        <h2 class="action-card-title">Keamanan Akun</h2>
        <p class="action-card-desc">Username, kata sandi login, dan password verifikasi kedua modul soal.</p>
      </div>
      <span class="action-card-arrow">&rarr;</span>
    </a>

  </div>

</div>
<?= $this->endSection() ?>
