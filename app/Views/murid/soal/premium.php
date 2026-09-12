<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button -->
  <div class="page-header-nav">
    <a href="<?= base_url('murid/soal') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Paket Premium</h1>
      <p class="page-subtitle">Try Out komprehensif CAT BKN dengan pembahasan lengkap</p>
    </div>
  </div>

  <!-- Premium Access Status Banner -->
  <?php if ($isPremium): ?>
    <div class="card" style="padding: 16px; border-color: var(--soft-sage-border); background: var(--soft-sage-bg);">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <div>
          <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #065F46;">Status Membership</span>
          <h2 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy); margin-top: 2px;">
            Akses All-Access Premium Aktif
          </h2>
          <span style="font-size: 12px; color: var(--text-muted);">
            Bebas akses seluruh paket hingga: <strong><?= date('d M Y', strtotime($expiryDate)) ?></strong>
          </span>
        </div>
        <span class="badge badge-sage" style="font-size: 11px;">✓ All Unlocked</span>
      </div>
    </div>
  <?php else: ?>
    <div class="card" style="padding: 18px; border-color: var(--soft-peach-border); background: var(--soft-peach-bg); gap: 10px;">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <div>
          <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #991B1B;">Status Langganan</span>
          <h2 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy); margin-top: 2px;">
            Akses Masih Terkunci
          </h2>
          <span style="font-size: 12px; color: var(--text-muted);">
            Cukup 1x aktivasi untuk membuka <strong>semua paket try out CAT Kedinasan</strong> sekaligus (tidak perlu bayar per paket).
          </span>
        </div>
        <span class="badge badge-peach" style="font-size: 11px;">🔒 Terkunci</span>
      </div>

      <a href="<?= base_url('murid/payment') ?>" class="btn btn-amber" style="height: 42px; font-size: 13px;">
        Buka Akses Semua Paket Premium Sekarang &rarr;
      </a>
    </div>
  <?php endif; ?>

  <!-- Package Cards List -->
  <div style="display: flex; flex-direction: column; gap: 12px;">
    <?php if (!empty($packages)): ?>
      <?php foreach ($packages as $pkg): ?>
        <div class="card" style="padding: 18px; gap: 10px; border-color: <?= $isPremium ? '#FDE68A' : 'var(--border-color)' ?>;">
          
          <div style="display: flex; align-items: flex-start; justify-content: space-between;">
            <div>
              <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
                <?= esc($pkg['title']) ?>
              </h3>
              <span style="font-size: 12px; color: var(--text-muted); display: block; margin-top: 2px;">
                <?= esc($pkg['description'] ?? 'Try out resmi standar CAT BKN') ?>
              </span>
            </div>

            <?php if ($isPremium): ?>
              <span class="badge badge-amber">Premium</span>
            <?php else: ?>
              <span class="badge badge-peach">🔒 Terkunci</span>
            <?php endif; ?>
          </div>

          <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted); background: #FFFDF5; padding: 8px 12px; border-radius: var(--radius-sm); border: 1px solid #FEF3C7;">
            <span>📝 <?= $pkg['question_count'] ?? 0 ?> Soal</span>
            <span>•</span>
            <span>⏱ 100 Menit (Simulasi CAT)</span>
            <span>•</span>
            <span>TWK, TIU, TKP</span>
          </div>

          <?php if (!empty($pkg['completed'])): ?>
            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #065F46; background: var(--soft-sage-bg); padding: 8px 12px; border-radius: var(--radius-sm); border: 1px solid var(--soft-sage-border);">
              <span>✓ Sudah dikerjakan</span>
              <strong>Skor Terakhir: <?= number_format($pkg['latest_score'], 1) ?></strong>
            </div>
          <?php endif; ?>

          <?php if ($isPremium): ?>
            <a href="<?= base_url("murid/soal/kerjakan/{$pkg['id']}") ?>" class="btn btn-amber" style="margin-top: 4px;">
              <?= !empty($pkg['completed']) ? 'Kerjakan Ulang &rarr;' : 'Mulai Ujian CAT &rarr;' ?>
            </a>
          <?php else: ?>
            <a href="<?= base_url('murid/payment') ?>" class="btn btn-secondary" style="margin-top: 4px; border-color: var(--warm-amber); color: var(--warm-amber); font-weight: 700;">
              Buka Akses Premium &rarr;
            </a>
          <?php endif; ?>

        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="font-size: 13px; color: var(--text-muted); text-align: center; padding: 24px;">Belum ada paket Premium tersedia.</p>
    <?php endif; ?>
  </div>

</div>
<?= $this->endSection() ?>
