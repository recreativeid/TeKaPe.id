<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button -->
  <div class="page-header-nav">
    <a href="<?= base_url('murid/soal/premium') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Berlangganan Premium</h1>
      <p class="page-subtitle">Aktivasi akses penuh Try Out dan evaluasi intensif</p>
    </div>
  </div>

  <form action="<?= base_url('murid/payment/process') ?>" method="post" style="display: flex; flex-direction: column; gap: 16px;">
    <?= csrf_field() ?>

    <!-- One Clear Subscription Card -->
    <div class="card" style="padding: 20px; border-color: #FDE68A; background: linear-gradient(180deg, #FFFFFF 0%, #FFFBEB 100%);">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <span class="badge badge-amber" style="font-size: 11px;">Akses Penuh</span>
        <span style="font-size: 12px; color: var(--text-muted);"><?= esc($duration) ?> Hari Akses</span>
      </div>

      <div style="margin: 8px 0;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 26px; font-weight: 800; color: var(--dark-navy);">
          Rp <?= number_format($price, 0, ',', '.') ?>
        </h2>
        <span style="font-size: 12px; color: var(--text-muted);">Sekali bayar untuk 30 hari akses tanpa perpanjangan otomatis paksa.</span>
      </div>

      <!-- Benefits List -->
      <div style="display: flex; flex-direction: column; gap: 8px; border-top: 1px solid #FEF3C7; padding-top: 12px; font-size: 13px; color: var(--dark-navy);">
        <div style="display: flex; align-items: center; gap: 8px;">
          <span style="color: var(--soft-sage-green); font-weight: 800;">✓</span>
          <span>Akses seluruh paket soal Premium</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
          <span style="color: var(--soft-sage-green); font-weight: 800;">✓</span>
          <span>Latihan lebih lengkap berstandar CAT BKN</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
          <span style="color: var(--soft-sage-green); font-weight: 800;">✓</span>
          <span>Evaluasi hasil try out & pembahasan mendalam</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
          <span style="color: var(--soft-sage-green); font-weight: 800;">✓</span>
          <span>Konsultasi materi langsung dengan tentor</span>
        </div>
      </div>
    </div>

    <!-- Payment Method Section -->
    <div class="card" style="padding: 18px; gap: 10px;">
      <h3 style="font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 700; color: var(--dark-navy);">
        Pilih Metode Pembayaran
      </h3>

      <div style="display: flex; flex-direction: column; gap: 8px;">
        <label style="display: flex; align-items: center; justify-content: space-between; padding: 12px 14px; border: 1.5px solid var(--dark-navy); border-radius: var(--radius-sm); background: #F8FAFC; cursor: pointer;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <input type="radio" name="payment_method" value="QRIS / E-Wallet (Instan)" checked>
            <span style="font-size: 13px; font-weight: 700; color: var(--dark-navy);">QRIS / E-Wallet (GoPay, OVO, DANA)</span>
          </div>
          <span class="badge badge-sage" style="font-size: 9px;">Otomatis</span>
        </label>

        <label style="display: flex; align-items: center; justify-content: space-between; padding: 12px 14px; border: 1px solid var(--border-color); border-radius: var(--radius-sm); background: #FFFFFF; cursor: pointer;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <input type="radio" name="payment_method" value="Virtual Account Bank">
            <span style="font-size: 13px; font-weight: 600; color: var(--dark-navy);">Virtual Account (BCA, Mandiri, BRI, BNI)</span>
          </div>
        </label>
      </div>
    </div>

    <!-- Order Summary -->
    <div class="card" style="padding: 16px; font-size: 13px; color: var(--text-muted);">
      <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
        <span>Paket Premium (30 Hari)</span>
        <span style="color: var(--dark-navy);">Rp <?= number_format($price, 0, ',', '.') ?></span>
      </div>
      <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
        <span>Biaya Layanan Gateway</span>
        <span style="color: var(--soft-sage-green);">Gratis (Rp 0)</span>
      </div>
      <div style="display: flex; justify-content: space-between; border-top: 1px solid var(--border-light); padding-top: 8px; font-weight: 800; font-size: 15px; color: var(--dark-navy);">
        <span>Total Pembayaran</span>
        <span style="color: var(--warm-amber);">Rp <?= number_format($price, 0, ',', '.') ?></span>
      </div>
    </div>

    <div style="display: flex; align-items: center; justify-content: center; gap: 6px; font-size: 11px; color: var(--text-muted);">
      <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
      <span>Enkripsi 256-bit aman & aktivasi otomatis seketika.</span>
    </div>

    <button type="submit" class="btn btn-amber" style="height: 50px; font-size: 15px; font-weight: 700;">
      Bayar Sekarang Rp <?= number_format($price, 0, ',', '.') ?> &rarr;
    </button>
  </form>

</div>
<?= $this->endSection() ?>
