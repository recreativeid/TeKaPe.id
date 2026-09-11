<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="cbt-container" style="display: flex; flex-direction: column; gap: 16px; min-height: 90vh;">

  <!-- CBT Exam Top Header Bar -->
  <div style="display: flex; align-items: center; justify-content: space-between; background: #FFFFFF; padding: 12px 16px; border-radius: var(--radius-md); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
    <div style="display: flex; flex-direction: column;">
      <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">
        <?= esc($package['title']) ?>
      </span>
      <span style="font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 800; color: var(--dark-navy);">
        Soal <span id="current-q-index-text">1</span> dari <?= count($package['questions']) ?>
      </span>
    </div>

    <!-- Countdown Timer & Question Grid Toggle -->
    <div style="display: flex; align-items: center; gap: 8px;">
      <div style="background: var(--dark-navy); color: #FFFFFF; font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 700; padding: 6px 10px; border-radius: var(--radius-sm); display: flex; align-items: center; gap: 4px;">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span id="exam-timer">45:00</span>
      </div>

      <button type="button" class="btn btn-secondary btn-sm" onclick="toggleNavigatorDrawer()" style="height: 34px; padding: 0 10px;" title="Navigasi Soal">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
      </button>
    </div>
  </div>

  <!-- Progress Bar -->
  <div style="width: 100%; height: 5px; background: #E2E8F0; border-radius: 999px; overflow: hidden;">
    <div id="cbt-progress-bar" style="height: 100%; width: <?= round(1 / count($package['questions']) * 100) ?>%; background: var(--dark-navy); transition: width 0.2s;"></div>
  </div>

  <!-- Main Question Answering Form -->
  <form id="cbt-exam-form" action="<?= base_url("murid/soal/submitUjian/{$package['id']}") ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="start_time" value="<?= date('Y-m-d H:i:s') ?>">

    <!-- Question Slides -->
    <?php foreach ($package['questions'] as $qIdx => $q): ?>
      <div class="cbt-question-slide" id="slide-<?= $qIdx ?>" style="display: <?= $qIdx === 0 ? 'flex' : 'none' ?>; flex-direction: column; gap: 14px;">
        
        <!-- Category Badge -->
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <span class="badge badge-navy" style="font-size: 11px; padding: 4px 10px;">
            <?= esc($q['category_code'] ?? 'TWK') ?> • <?= $q['type'] === 'pilihan_ganda' ? 'Pilihan Ganda' : 'Isian Singkat' ?>
          </span>
          <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">
            No. <?= $qIdx + 1 ?>
          </span>
        </div>

        <!-- Question Card -->
        <div class="card" style="padding: 18px; gap: 12px;">
          <div style="font-size: 15px; color: var(--dark-navy); line-height: 1.6; font-weight: 500;">
            <?= nl2br(esc($q['narrative'])) ?>
          </div>

          <?php if (!empty($q['image_url'])): ?>
            <div style="text-align: center; margin: 8px 0;">
              <img src="<?= esc($q['image_url']) ?>" alt="Gambar Soal" style="max-width: 100%; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
            </div>
          <?php endif; ?>
        </div>

        <!-- Answer Choices Section -->
        <?php if ($q['type'] === 'pilihan_ganda'): ?>
          <input type="hidden" name="answers[<?= $q['id'] ?>]" id="answer-input-<?= $q['id'] ?>" value="">
          
          <div class="cbt-options-container" style="display: flex; flex-direction: column; gap: 10px;">
            <?php if (!empty($q['options'])): ?>
              <?php foreach ($q['options'] as $opt): ?>
                <div class="cbt-option-card" onclick="selectCBTOption(this, <?= $q['id'] ?>, '<?= $opt['option_label'] ?>')">
                  <div class="cbt-option-label">
                    <?= $opt['option_label'] ?>
                  </div>
                  <div class="cbt-option-text">
                    <?= esc($opt['option_text']) ?>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

        <?php else: ?>
          <!-- Essay / Isian Singkat -->
          <div class="card" style="padding: 16px;">
            <label class="form-label">Jawaban Isian Anda:</label>
            <textarea name="answers[<?= $q['id'] ?>]" class="form-control" rows="3" placeholder="Ketik jawaban Anda di sini..." oninput="markAnswered(<?= $q['id'] ?>, this.value)"></textarea>
          </div>
        <?php endif; ?>

      </div>
    <?php endforeach; ?>

    <!-- Navigation Controls (Sebelumnya, Berikutnya, Selesai) -->
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--border-color);">
      <button type="button" id="prev-btn" class="btn btn-secondary" onclick="prevQuestion()" style="flex: 1; height: 46px; font-size: 13px;" disabled>
        &larr; Sebelumnya
      </button>

      <button type="button" id="next-btn" class="btn btn-primary" onclick="nextQuestion()" style="flex: 1; height: 46px; font-size: 13px;">
        Berikutnya &rarr;
      </button>
    </div>

    <!-- Final Submission CTA -->
    <div style="margin-top: 12px;">
      <button type="button" class="btn btn-amber" onclick="openFinishModal()" style="height: 48px; font-weight: 700;">
        Selesai & Lihat Hasil &rarr;
      </button>
    </div>

    <!-- Modal Konfirmasi Selesai Ujian -->
    <div id="finish-modal" class="modal-backdrop">
      <div class="modal-dialog">
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 700; color: var(--dark-navy);">
          Selesaikan Try Out?
        </h3>
        <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5;">
          Pastikan semua butir pertanyaan telah Anda jawab. Setelah mengonfirmasi, hasil evaluasi dan nilai akhir Anda akan langsung diproses.
        </p>

        <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 6px;">
          <button type="submit" class="btn btn-primary">
            Ya, Kumpulkan Jawaban
          </button>
          <button type="button" data-modal-close class="btn btn-secondary">
            Periksa Kembali
          </button>
        </div>
      </div>
    </div>
  </form>

  <!-- Expandable Question Navigator Drawer -->
  <div id="navigator-modal" class="modal-backdrop">
    <div class="modal-dialog" style="max-height: 80vh; overflow-y: auto;">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
          Navigasi Soal
        </h3>
        <button type="button" data-modal-close style="background: none; border: none; font-size: 18px; cursor: pointer;">&times;</button>
      </div>

      <div class="cbt-grid">
        <?php foreach ($package['questions'] as $nIdx => $nq): ?>
          <a href="javascript:void(0)" id="grid-q-<?= $nq['id'] ?>" class="cbt-grid-item <?= $nIdx === 0 ? 'current' : '' ?>" onclick="goToQuestion(<?= $nIdx ?>)">
            <?= $nIdx + 1 ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const totalQuestions = <?= count($package['questions']) ?>;
let currentIndex = 0;

function updateSlideView() {
  document.querySelectorAll('.cbt-question-slide').forEach((el, idx) => {
    el.style.display = (idx === currentIndex) ? 'flex' : 'none';
  });

  document.getElementById('current-q-index-text').innerText = currentIndex + 1;
  document.getElementById('cbt-progress-bar').style.width = `${((currentIndex + 1) / totalQuestions) * 100}%`;

  document.getElementById('prev-btn').disabled = (currentIndex === 0);
  
  if (currentIndex === totalQuestions - 1) {
    document.getElementById('next-btn').innerText = 'Ke Soal 1';
  } else {
    document.getElementById('next-btn').innerHTML = 'Berikutnya &rarr;';
  }

  // Update current grid indicator
  document.querySelectorAll('.cbt-grid-item').forEach((el, idx) => {
    if (idx === currentIndex) {
      el.classList.add('current');
    } else {
      el.classList.remove('current');
    }
  });
}

function nextQuestion() {
  if (currentIndex < totalQuestions - 1) {
    currentIndex++;
  } else {
    currentIndex = 0;
  }
  updateSlideView();
}

function prevQuestion() {
  if (currentIndex > 0) {
    currentIndex--;
    updateSlideView();
  }
}

function goToQuestion(idx) {
  currentIndex = idx;
  updateSlideView();
  document.getElementById('navigator-modal').classList.remove('open');
}

function toggleNavigatorDrawer() {
  document.getElementById('navigator-modal').classList.add('open');
}

function openFinishModal() {
  document.getElementById('finish-modal').classList.add('open');
}

function markAnswered(qId, val) {
  const item = document.getElementById(`grid-q-${qId}`);
  if (item) {
    if (val.trim() !== '') {
      item.classList.add('answered');
    } else {
      item.classList.remove('answered');
    }
  }
}

// Countdown Timer (45 minutes)
let timeLeft = 45 * 60;
const timerEl = document.getElementById('exam-timer');
const timerInterval = setInterval(() => {
  if (timeLeft <= 0) {
    clearInterval(timerInterval);
    alert('Waktu pengerjaan telah selesai. Jawaban Anda akan dikumpulkan otomatis.');
    document.getElementById('cbt-exam-form').submit();
    return;
  }
  timeLeft--;
  const m = Math.floor(timeLeft / 60).toString().padStart(2, '0');
  const s = (timeLeft % 60).toString().padStart(2, '0');
  if (timerEl) timerEl.innerText = `${m}:${s}`;
}, 1000);
</script>
<?= $this->endSection() ?>
