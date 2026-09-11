<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button -->
  <div class="page-header-nav">
    <a href="<?= base_url('admin/murid') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Nilai Try Out</h1>
      <p class="page-subtitle">Pantau dan evaluasi hasil try out murid TeKaPe.id</p>
    </div>
  </div>

  <!-- Search & Filter Controls -->
  <div class="card" style="padding: 14px; gap: 10px;">
    <form action="<?= base_url('admin/murid/nilai') ?>" method="get" style="display: flex; gap: 6px;">
      <input type="text" name="q" class="form-control" placeholder="Cari nama murid / paket..." value="<?= esc($search ?? '') ?>" style="height: 40px; font-size: 13px;">
      <button type="submit" class="btn btn-primary btn-sm" style="height: 40px; width: 68px;">Cari</button>
    </form>

    <div style="display: flex; gap: 6px; overflow-x: auto;">
      <a href="<?= base_url('admin/murid/nilai') ?>" class="day-pill <?= empty($type) ? 'active' : '' ?>" style="font-size: 11px; padding: 6px 12px;">Semua</a>
      <a href="<?= base_url('admin/murid/nilai?type=free') ?>" class="day-pill <?= $type === 'free' ? 'active' : '' ?>" style="font-size: 11px; padding: 6px 12px;">Free</a>
      <a href="<?= base_url('admin/murid/nilai?type=premium') ?>" class="day-pill <?= $type === 'premium' ? 'active' : '' ?>" style="font-size: 11px; padding: 6px 12px;">Premium</a>
    </div>
  </div>

  <!-- Tryout Results List -->
  <div style="display: flex; flex-direction: column; gap: 12px;">
    <?php if (!empty($results)): ?>
      <?php foreach ($results as $res): ?>
        <div class="card" style="padding: 16px; gap: 10px;">
          
          <div style="display: flex; align-items: flex-start; justify-content: space-between;">
            <div>
              <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
                <?= esc($res['student_name']) ?>
              </h3>
              <span style="font-size: 12px; color: var(--text-muted);">
                <?= esc($res['package_title']) ?>
              </span>
            </div>

            <div style="text-align: right;">
              <span style="font-family: 'Outfit', sans-serif; font-size: 22px; font-weight: 800; color: var(--dark-navy);">
                <?= number_format($res['final_score'], 1) ?>
              </span>
              <span style="font-size: 10px; color: var(--text-muted); display: block;">Nilai Akhir</span>
            </div>
          </div>

          <!-- Category Breakdown -->
          <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; background: #F8FAFC; padding: 8px 12px; border-radius: var(--radius-sm); font-size: 11px; text-align: center;">
            <div>
              <span style="color: var(--text-muted); display: block;">TWK</span>
              <strong style="color: var(--dark-navy);"><?= number_format($res['twk_score'], 1) ?></strong>
            </div>
            <div>
              <span style="color: var(--text-muted); display: block;">TIU</span>
              <strong style="color: var(--dark-navy);"><?= number_format($res['tiu_score'], 1) ?></strong>
            </div>
            <div>
              <span style="color: var(--text-muted); display: block;">TKP</span>
              <strong style="color: var(--dark-navy);"><?= number_format($res['tkp_score'], 1) ?></strong>
            </div>
          </div>

          <!-- Audit Log for Manual Edit if any -->
          <?php if (!empty($res['is_manual_edited'])): ?>
            <div class="alert alert-warning" style="padding: 8px 12px; font-size: 11px; margin: 0;">
              <span>⚠️ <strong>Nilai Manual</strong> • Diubah oleh: <?= esc($res['edited_by'] ?? 'Admin') ?> (<?= date('d M Y H:i', strtotime($res['edited_at'])) ?>)</span>
            </div>
          <?php endif; ?>

          <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--border-light); padding-top: 8px; font-size: 11px; color: var(--text-muted);">
            <span><?= date('d M Y H:i', strtotime($res['created_at'])) ?></span>
            <button type="button" class="btn btn-secondary btn-sm" onclick="openEditScoreModal(<?= htmlspecialchars(json_encode($res)) ?>)" style="height: 32px; font-size: 11px; font-weight: 700;">
              Edit Nilai Manual
            </button>
          </div>

        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="font-size: 13px; color: var(--text-muted); text-align: center; padding: 24px;">
        Belum ada data nilai try out.
      </p>
    <?php endif; ?>
  </div>

  <!-- Modal Edit Nilai Manual -->
  <div id="modal-edit-nilai" class="modal-backdrop">
    <div class="modal-dialog">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
          Edit Nilai Manual
        </h3>
        <button type="button" data-modal-close style="background: none; border: none; font-size: 18px; cursor: pointer;">&times;</button>
      </div>

      <form id="form-edit-nilai" action="" method="post" style="display: flex; flex-direction: column; gap: 12px;">
        <?= csrf_field() ?>

        <div style="background: #F8FAFC; padding: 10px; border-radius: var(--radius-sm); font-size: 12px;">
          <strong id="modal-student-name" style="color: var(--dark-navy); display: block;"></strong>
          <span id="modal-package-title" style="color: var(--text-muted);"></span>
        </div>

        <div class="form-group">
          <label class="form-label">Nilai Akhir</label>
          <input type="number" step="0.01" name="final_score" id="m-final-score" class="form-control" required>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px;">
          <div class="form-group">
            <label class="form-label" style="font-size: 10px;">TWK</label>
            <input type="number" step="0.1" name="twk_score" id="m-twk-score" class="form-control" style="font-size: 12px;">
          </div>
          <div class="form-group">
            <label class="form-label" style="font-size: 10px;">TIU</label>
            <input type="number" step="0.1" name="tiu_score" id="m-tiu-score" class="form-control" style="font-size: 12px;">
          </div>
          <div class="form-group">
            <label class="form-label" style="font-size: 10px;">TKP</label>
            <input type="number" step="0.1" name="tkp_score" id="m-tkp-score" class="form-control" style="font-size: 12px;">
          </div>
        </div>

        <div style="font-size: 11px; color: var(--text-muted);">
          ℹ️ Perubahan ini akan diberi tanda eksplisit "Nilai Manual" berserta riwayat admin yang melakukan perubahan.
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 4px;">
          Simpan Nilai Manual
        </button>
      </form>
    </div>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function openEditScoreModal(res) {
  document.getElementById('form-edit-nilai').action = `<?= base_url('admin/murid/updateNilaiManual/') ?>/${res.id}`;
  document.getElementById('modal-student-name').innerText = res.student_name;
  document.getElementById('modal-package-title').innerText = res.package_title;
  document.getElementById('m-final-score').value = res.final_score;
  document.getElementById('m-twk-score').value = res.twk_score;
  document.getElementById('m-tiu-score').value = res.tiu_score;
  document.getElementById('m-tkp-score').value = res.tkp_score;

  document.getElementById('modal-edit-nilai').classList.add('open');
}
</script>
<?= $this->endSection() ?>
