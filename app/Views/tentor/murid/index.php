<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header -->
  <div class="page-header-nav">
    <a href="<?= base_url('tentor/dashboard') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Data Murid & Nilai Siswa</h1>
      <p class="page-subtitle">Pantau kemajuan belajar dan hasil pengerjaan try out siswa</p>
    </div>
  </div>

  <!-- Segmented Tab Navigation -->
  <div class="segmented-control" style="background: #E2E8F0; padding: 4px; border-radius: var(--radius-md); display: flex;">
    <button type="button" id="tab-btn-nilai" class="btn btn-sm" onclick="switchTab('nilai')" style="flex: 1; border-radius: var(--radius-sm); font-weight: 700; background: #FFFFFF; color: var(--dark-navy); box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
      Hasil & Nilai Siswa
    </button>
    <button type="button" id="tab-btn-database" class="btn btn-sm" onclick="switchTab('database')" style="flex: 1; border-radius: var(--radius-sm); font-weight: 700; background: transparent; color: var(--text-muted);">
      Database Murid (<?= count($students) ?>)
    </button>
  </div>

  <!-- SECTION 1: Hasil & Nilai Siswa -->
  <div id="section-nilai" style="display: flex; flex-direction: column; gap: 12px;">
    <div class="card" style="padding: 16px;">
      <h2 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy); margin-bottom: 4px;">
        Nilai Try Out Siswa Terbaru
      </h2>
      <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 12px;">
        Hasil pengerjaan paket soal TWK, TIU, dan TKP oleh para siswa.
      </p>

      <div style="display: flex; flex-direction: column; gap: 8px;">
        <?php if (!empty($tryoutResults)): ?>
          <?php foreach ($tryoutResults as $res): ?>
            <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 12px; display: flex; flex-direction: column; gap: 6px;">
              <div style="display: flex; align-items: center; justify-content: space-between;">
                <strong style="font-size: 14px; color: var(--dark-navy);"><?= esc($res['student_name']) ?></strong>
                <span class="badge <?= $res['is_passed'] ? 'badge-sage' : 'badge-peach' ?>" style="font-size: 10px;">
                  <?= $res['is_passed'] ? 'Lulus PG' : 'Belum Lulus' ?>
                </span>
              </div>

              <span style="font-size: 11px; color: var(--text-muted);">
                Paket: <?= esc($res['package_title'] ?? 'Try Out') ?>
              </span>

              <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px; background: #FFFFFF; padding: 6px 8px; border-radius: 4px; border: 1px solid var(--border-light); font-size: 11px; text-align: center; margin-top: 4px;">
                <div>
                  <span style="color: var(--text-muted); display: block; font-size: 10px;">TWK</span>
                  <strong><?= $res['twk_score'] ?></strong>
                </div>
                <div>
                  <span style="color: var(--text-muted); display: block; font-size: 10px;">TIU</span>
                  <strong><?= $res['tiu_score'] ?></strong>
                </div>
                <div>
                  <span style="color: var(--text-muted); display: block; font-size: 10px;">TKP</span>
                  <strong><?= $res['tkp_score'] ?></strong>
                </div>
                <div>
                  <span style="color: var(--warm-amber); display: block; font-size: 10px; font-weight: 700;">TOTAL</span>
                  <strong style="color: var(--warm-amber);"><?= $res['total_score'] ?></strong>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="font-size: 12px; color: var(--text-muted); text-align: center; padding: 16px 0;">
            Belum ada hasil try out dari siswa.
          </p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- SECTION 2: Database Murid -->
  <div id="section-database" style="display: none; flex-direction: column; gap: 12px;">
    <div class="card" style="padding: 16px;">
      <h2 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy); margin-bottom: 8px;">
        Daftar Siswa Terdaftar
      </h2>

      <div style="display: flex; flex-direction: column; gap: 8px;">
        <?php if (!empty($students)): ?>
          <?php foreach ($students as $stu): ?>
            <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 12px; display: flex; align-items: center; justify-content: space-between;">
              <div style="display: flex; flex-direction: column; gap: 2px;">
                <strong style="font-size: 13px; color: var(--dark-navy);"><?= esc($stu['name']) ?></strong>
                <span style="font-size: 11px; color: var(--text-muted);">@<?= esc($stu['username']) ?></span>
                <span class="badge <?= $stu['is_premium'] ? 'badge-amber' : 'badge-navy' ?>" style="width: fit-content; font-size: 10px; margin-top: 2px;">
                  <?= $stu['is_premium'] ? '⭐ Premium' : 'Free' ?>
                </span>
              </div>

              <?php if (!empty($stu['phone_whatsapp'])): ?>
                <?php 
                  $cleanPhone = preg_replace('/[^0-9]/', '', $stu['phone_whatsapp']);
                  if (substr($cleanPhone, 0, 1) === '0') {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                  }
                ?>
                <a href="https://wa.me/<?= $cleanPhone ?>?text=Halo%20<?= urlencode($stu['name']) ?>,%20saya%20tentor%20Anda%20dari%20TeKaPe.id" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" style="font-size: 11px; display: flex; align-items: center; gap: 4px;">
                  <span>💬 Chat WA</span>
                </a>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="font-size: 12px; color: var(--text-muted); text-align: center; padding: 16px 0;">
            Tidak ada siswa ditemukan.
          </p>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function switchTab(tab) {
  const btnNilai = document.getElementById('tab-btn-nilai');
  const btnDb = document.getElementById('tab-btn-database');
  const secNilai = document.getElementById('section-nilai');
  const secDb = document.getElementById('section-database');

  if (tab === 'nilai') {
    btnNilai.style.background = '#FFFFFF';
    btnNilai.style.color = 'var(--dark-navy)';
    btnNilai.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
    btnDb.style.background = 'transparent';
    btnDb.style.color = 'var(--text-muted)';
    btnDb.style.boxShadow = 'none';

    secNilai.style.display = 'flex';
    secDb.style.display = 'none';
  } else {
    btnDb.style.background = '#FFFFFF';
    btnDb.style.color = 'var(--dark-navy)';
    btnDb.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
    btnNilai.style.background = 'transparent';
    btnNilai.style.color = 'var(--text-muted)';
    btnNilai.style.boxShadow = 'none';

    secNilai.style.display = 'none';
    secDb.style.display = 'flex';
  }
}
</script>
<?= $this->endSection() ?>
