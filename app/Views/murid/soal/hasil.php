<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button -->
  <div class="page-header-nav">
    <a href="<?= base_url('murid/soal') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Hasil Try Out</h1>
      <p class="page-subtitle"><?= esc($session['package_title']) ?></p>
    </div>
  </div>

  <!-- Big Final Score Card -->
  <div class="card card-navy" style="text-align: center; padding: 24px 18px; border-radius: var(--radius-lg); gap: 4px;">
    <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; color: var(--warm-amber); font-weight: 700;">
      Nilai Akhir Try Out
    </span>
    
    <div style="font-family: 'Outfit', sans-serif; font-size: 48px; font-weight: 800; color: #FFFFFF; line-height: 1.1; margin: 4px 0;">
      <?= number_format($session['final_score'], 2) ?>
    </div>

    <div style="display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 12px; color: #CBD5E1; margin-top: 6px;">
      <span>✓ <?= $session['correct_count'] ?> Benar</span>
      <span>•</span>
      <span>✕ <?= $session['incorrect_count'] ?> Salah</span>
      <span>•</span>
      <span>⏱ <?= $durationMin ?> Menit</span>
    </div>
  </div>

  <!-- Category Score Cards: TWK, TIU, TKP -->
  <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;">
    <div class="stat-box" style="padding: 12px 10px; text-align: center;">
      <span class="stat-label">Nilai TWK</span>
      <span class="stat-value" style="font-size: 18px;"><?= number_format($session['twk_score'], 1) ?></span>
    </div>
    <div class="stat-box" style="padding: 12px 10px; text-align: center;">
      <span class="stat-label">Nilai TIU</span>
      <span class="stat-value" style="font-size: 18px;"><?= number_format($session['tiu_score'], 1) ?></span>
    </div>
    <div class="stat-box" style="padding: 12px 10px; text-align: center;">
      <span class="stat-label">Nilai TKP</span>
      <span class="stat-value" style="font-size: 18px;"><?= number_format($session['tkp_score'], 1) ?></span>
    </div>
  </div>

  <!-- Review Jawaban Section -->
  <div style="display: flex; flex-direction: column; gap: 10px;">
    <div style="display: flex; align-items: center; justify-content: space-between;">
      <h2 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
        Review Jawaban & Pembahasan
      </h2>
      <span style="font-size: 11px; color: var(--text-muted);">
        <?= count($session['answers'] ?? []) ?> Butir Soal
      </span>
    </div>

    <div style="display: flex; flex-direction: column; gap: 10px;">
      <?php if (!empty($session['answers'])): ?>
        <?php foreach ($session['answers'] as $aIdx => $ans): ?>
          <div class="card" style="padding: 16px; gap: 8px; border-color: <?= $ans['is_correct'] ? 'var(--soft-sage-border)' : 'var(--soft-peach-border)' ?>; background: <?= $ans['is_correct'] ? '#FBFCFD' : 'var(--soft-peach-bg)' ?>;">
            
            <div style="display: flex; align-items: center; justify-content: space-between;">
              <div style="display: flex; align-items: center; gap: 6px;">
                <span style="font-weight: 800; font-size: 13px; color: var(--dark-navy);">#<?= $ans['question_number'] ?></span>
                <span class="badge badge-navy" style="font-size: 10px;"><?= esc($ans['category_code'] ?? 'TWK') ?></span>
              </div>

              <?php if ($ans['is_correct']): ?>
                <span class="badge badge-sage" style="font-size: 10px;">✓ Benar (+<?= $ans['score_earned'] ?>)</span>
              <?php else: ?>
                <span class="badge badge-peach" style="font-size: 10px;">✕ Salah (+<?= $ans['score_earned'] ?>)</span>
              <?php endif; ?>
            </div>

            <p style="font-size: 13px; color: var(--dark-navy); line-height: 1.45;">
              <?= esc($ans['narrative']) ?>
            </p>

            <!-- User vs Correct Answer -->
            <div style="display: flex; flex-direction: column; gap: 3px; font-size: 12px; background: #FFFFFF; padding: 8px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
              <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--text-muted);">Jawaban Kamu:</span>
                <strong style="color: <?= $ans['is_correct'] ? 'var(--soft-sage-green)' : '#DC2626' ?>;">
                  <?= esc($ans['user_answer'] ?: '(Tidak Dijawab)') ?>
                </strong>
              </div>
            </div>

            <!-- Discussion Details Accordion -->
            <?php if (!empty($ans['discussion'])): ?>
              <details style="background: #FFFFFF; border: 1px solid var(--border-light); border-radius: var(--radius-sm); padding: 8px 12px; font-size: 12px;">
                <summary style="cursor: pointer; font-weight: 700; color: var(--dark-navy); outline: none;">
                  💡 Lihat Pembahasan Lengkap
                </summary>
                <div style="margin-top: 6px; color: var(--text-main); line-height: 1.5; border-top: 1px dashed var(--border-color); padding-top: 6px;">
                  <?= nl2br(esc($ans['discussion'])) ?>
                </div>
              </details>
            <?php endif; ?>

          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <!-- Bottom Actions: Kerjakan Lagi & Kembali -->
  <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 6px;">
    <a href="<?= base_url("murid/soal/kerjakan/{$session['package_id']}") ?>" class="btn btn-primary">
      Kerjakan Lagi &rarr;
    </a>
    <a href="<?= base_url('murid/dashboard') ?>" class="btn btn-secondary">
      Kembali ke Dashboard
    </a>
  </div>

</div>
<?= $this->endSection() ?>
