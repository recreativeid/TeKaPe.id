<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 24px; padding-top: 6px;">

  <!-- Page Header -->
  <div class="page-header">
    <h1 class="page-title">Pilih Paket Soal</h1>
    <p class="page-subtitle">Tentukan jenis paket soal yang ingin dikelola.</p>
  </div>

  <!-- Exactly TWO Large Selection Cards -->
  <div class="selection-cards-grid" style="display: flex; flex-direction: column; gap: 16px;">
    
    <!-- CARD 1: Paket Soal Free -->
    <div class="large-select-card">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <span class="badge badge-navy" style="font-size: 11px;">Gratis untuk Semua</span>
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--text-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
      </div>

      <div>
        <h2 class="large-select-title" style="font-size: 20px;">Paket Soal Free</h2>
        <p class="large-select-desc" style="margin-top: 4px;">
          Kelola paket soal yang dapat dikerjakan murid secara gratis tanpa perlu berlangganan.
        </p>
      </div>

      <?php $rolePrefix = ($role ?? 'admin') === 'tentor' ? 'tentor' : 'admin'; ?>
      <a href="<?= base_url("{$rolePrefix}/soal/free") ?>" class="btn btn-primary" style="margin-top: 6px;">
        Kelola Paket Free &rarr;
      </a>
    </div>

    <!-- CARD 2: Paket Soal Premium -->
    <div class="large-select-card premium-card">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <span class="badge badge-amber" style="font-size: 11px;">Khusus Berlangganan</span>
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--warm-amber);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
      </div>

      <div>
        <h2 class="large-select-title" style="font-size: 20px;">Paket Soal Premium</h2>
        <p class="large-select-desc" style="margin-top: 4px;">
          Kelola paket soal khusus murid dengan akses Premium, try out CAT akurat, dan integrasi pembayaran.
        </p>
      </div>

      <a href="<?= base_url("{$rolePrefix}/soal/premium") ?>" class="btn btn-amber" style="margin-top: 6px;">
        Kelola Paket Premium &rarr;
      </a>
    </div>

  </div>

</div>
<?= $this->endSection() ?>
