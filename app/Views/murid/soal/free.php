<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button -->
  <div class="page-header-nav">
    <a href="<?= base_url('murid/soal') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Paket Free</h1>
      <p class="page-subtitle">Pilih paket latihan gratis untuk mulai menguji kemampuan</p>
    </div>
  </div>

  <!-- Search & Filter Controls -->
  <div class="card" style="padding: 14px;">
    <form action="<?= base_url('murid/soal/free') ?>" method="get" style="display: flex; gap: 6px;">
      <input type="text" name="q" class="form-control" placeholder="Cari paket soal..." value="<?= esc($search ?? '') ?>" style="height: 40px; font-size: 13px;">
      <button type="submit" class="btn btn-primary btn-sm" style="height: 40px; width: 68px;">Cari</button>
    </form>
  </div>

  <!-- Package Cards List -->
  <div style="display: flex; flex-direction: column; gap: 12px;">
    <?php if (!empty($packages)): ?>
      <?php foreach ($packages as $pkg): ?>
        <div class="card" style="padding: 18px; gap: 10px;">
          
          <div style="display: flex; align-items: flex-start; justify-content: space-between;">
            <div>
              <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
                <?= esc($pkg['title']) ?>
              </h3>
              <span style="font-size: 12px; color: var(--text-muted); display: block; margin-top: 2px;">
                <?= esc($pkg['description'] ?? 'Latihan soal mandiri') ?>
              </span>
            </div>

            <span class="badge badge-navy">Gratis</span>
          </div>

          <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted); background: #F8FAFC; padding: 8px 12px; border-radius: var(--radius-sm);">
            <span>📝 <?= $pkg['question_count'] ?? 0 ?> Soal</span>
            <span>•</span>
            <span>⏱ Estimasi 45 Menit</span>
            <span>•</span>
            <span>TWK, TIU, TKP</span>
          </div>

          <?php if (!empty($pkg['completed'])): ?>
            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #065F46; background: var(--soft-sage-bg); padding: 8px 12px; border-radius: var(--radius-sm); border: 1px solid var(--soft-sage-border);">
              <span>✓ Sudah dikerjakan</span>
              <strong>Skor Terakhir: <?= number_format($pkg['latest_score'], 1) ?></strong>
            </div>
          <?php endif; ?>

          <a href="<?= base_url("murid/soal/kerjakan/{$pkg['id']}") ?>" class="btn btn-primary" style="margin-top: 4px;">
            <?= !empty($pkg['completed']) ? 'Kerjakan Ulang &rarr;' : 'Mulai Latihan &rarr;' ?>
          </a>

        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="font-size: 13px; color: var(--text-muted); text-align: center; padding: 24px;">
        Tidak ada paket soal yang sesuai dengan pencarian.
      </p>
    <?php endif; ?>
  </div>

</div>
<?= $this->endSection() ?>
