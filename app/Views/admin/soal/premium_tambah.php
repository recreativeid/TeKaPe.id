<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php $rolePrefix = ($role ?? 'admin') === 'tentor' ? 'tentor' : 'admin'; ?>

<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button -->
  <div class="page-header-nav">
    <a href="<?= base_url("{$rolePrefix}/soal/premium") ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Tambah Paket Premium</h1>
      <p class="page-subtitle">Workspace pembuatan materi try out khusus murid Premium</p>
    </div>
  </div>

  <form id="tambah-premium-form" action="<?= base_url("admin/soal/savePackage") ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="type" value="premium">
    <input type="hidden" name="questions_json" id="questions-json-input" value="[]">

    <!-- SECTION 1: Package Information -->
    <div class="card" style="padding: 18px; border-color: #FDE68A; background: linear-gradient(180deg, #FFFFFF 0%, #FFFDF5 100%);">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
          1. Informasi Paket Premium
        </h2>
        <span class="badge badge-amber">Premium Access</span>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Paket Soal</label>
        <input type="text" name="title" class="form-control" placeholder="Contoh: Grand Try Out SKD Kedinasan 2026" required>
      </div>

      <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="2" placeholder="Tuliskan petunjuk khusus pengerjaan paket premium..."></textarea>
      </div>

      <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
        <div class="form-group">
          <label class="form-label">Tarif Layanan (Rp)</label>
          <input type="number" name="price" class="form-control" value="<?= esc($defaultPrice) ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Durasi Akses (Hari)</label>
          <input type="number" name="duration_days" class="form-control" value="<?= esc($defaultDuration) ?>" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
          <option value="active" selected>Aktif (Dapat diakses murid Premium)</option>
          <option value="draft">Draft (Disimpan sementara)</option>
        </select>
      </div>
    </div>

    <!-- SECTION 2: Daftar Soal -->
    <div class="card" style="padding: 18px; margin-top: 14px;">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
          2. Butir Soal (<span id="question-count-badge">0</span>)
        </h2>
        <button type="button" class="btn btn-amber btn-sm" onclick="toggleEditor()" style="font-weight: 700;">
          + Tambah Soal
        </button>
      </div>

      <!-- In-Page Question Creation Interface -->
      <div id="question-editor-card" style="display: none; background: #FFFBEB; border: 1.5px solid #FDE68A; border-radius: var(--radius-md); padding: 16px; margin-bottom: 14px; flex-direction: column; gap: 12px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <span style="font-size: 13px; font-weight: 700; color: var(--dark-navy);">Editor Butir Soal Premium</span>
          <button type="button" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 12px; font-weight: 600;" onclick="toggleEditor()">Tutup</button>
        </div>

        <div class="form-group">
          <label class="form-label">Kategori Soal</label>
          <select id="editor-cat" class="form-control">
            <option value="TWK">Tes Wawasan Kebangsaan (TWK)</option>
            <option value="TIU">Tes Inteligensia Umum (TIU)</option>
            <option value="TKP">Tes Karakteristik Pribadi (TKP)</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Jenis Soal</label>
          <select id="editor-type" class="form-control" onchange="toggleQuestionTypeFields()">
            <option value="pilihan_ganda" selected>Pilihan Ganda (A-E)</option>
            <option value="isian">Isian Singkat</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Narasi Soal</label>
          <textarea id="editor-narrative" class="form-control" rows="3" placeholder="Tuliskan narasi soal lengkap..."></textarea>
        </div>

        <!-- Pilihan Ganda Section -->
        <div id="pg-options-wrapper" style="display: flex; flex-direction: column; gap: 8px;">
          <label class="form-label">Opsi Jawaban & Pembobotan Skor:</label>
          <?php foreach (['A', 'B', 'C', 'D', 'E'] as $opt): ?>
            <div style="display: flex; align-items: center; gap: 6px; background: #FFFFFF; padding: 6px 10px; border-radius: 4px; border: 1px solid var(--border-color);">
              <input type="radio" name="correct_opt_temp" value="<?= $opt ?>" <?= $opt === 'A' ? 'checked' : '' ?> title="Kunci Jawaban">
              <span style="font-weight: 700; font-size: 13px; width: 16px;"><?= $opt ?></span>
              <input type="text" id="opt-text-<?= $opt ?>" class="form-control" style="height: 36px; font-size: 12px;" placeholder="Teks opsi <?= $opt ?>">
              <input type="number" id="opt-score-<?= $opt ?>" class="form-control" style="height: 36px; width: 68px; font-size: 12px; text-align: center;" value="<?= $opt === 'A' ? '5' : '0' ?>" title="Skor opsi">
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Isian Section -->
        <div id="isian-wrapper" style="display: none; flex-direction: column; gap: 8px;">
          <div class="form-group">
            <label class="form-label">Kunci Jawaban Isian</label>
            <input type="text" id="editor-expected" class="form-control" placeholder="Jawaban yang benar">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Pembahasan Mendalam</label>
          <textarea id="editor-discussion" class="form-control" rows="2" placeholder="Tuliskan trik, rumus cepat, dan pembahasan komprehensif..."></textarea>
        </div>

        <button type="button" class="btn btn-primary btn-sm" onclick="addQuestionToList()" style="height: 40px; font-size: 13px;">
          + Masukkan ke Daftar Soal
        </button>
      </div>

      <!-- Questions List Container -->
      <div id="questions-list-container" style="display: flex; flex-direction: column; gap: 8px;">
        <p id="empty-questions-msg" style="font-size: 12px; color: var(--text-muted); text-align: center; padding: 12px 0;">
          Belum ada soal ditambahkan. Klik tombol "+ Tambah Soal" di atas.
        </p>
      </div>
    </div>

    <!-- Review Section -->
    <div class="card" style="padding: 18px; margin-top: 14px;">
      <h2 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy); margin-bottom: 8px;">
        3. Review Paket Premium
      </h2>
      <div style="font-size: 13px; color: var(--text-muted); display: flex; flex-direction: column; gap: 6px;">
        <div style="display: flex; justify-content: space-between;">
          <span>Jumlah Soal:</span>
          <strong id="review-total-q" style="color: var(--dark-navy);">0 Soal</strong>
        </div>
        <div style="display: flex; justify-content: space-between;">
          <span>Distribusi Kategori:</span>
          <span id="review-dist-cat" style="color: var(--dark-navy); font-weight: 600;">TWK: 0 | TIU: 0 | TKP: 0</span>
        </div>
        <div style="display: flex; justify-content: space-between;">
          <span>Tarif Layanan:</span>
          <strong style="color: var(--warm-amber);">Rp <?= number_format($defaultPrice, 0, ',', '.') ?> (<?= esc($defaultDuration) ?> Hari)</strong>
        </div>
      </div>
    </div>

    <!-- Sticky Bottom CTA -->
    <div class="sticky-bottom-bar">
      <button type="submit" class="btn btn-amber">
        Simpan Paket Premium
      </button>
    </div>
  </form>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let questionsData = [];

function toggleEditor() {
  const card = document.getElementById('question-editor-card');
  card.style.display = (card.style.display === 'none' || card.style.display === '') ? 'flex' : 'none';
}

function toggleQuestionTypeFields() {
  const type = document.getElementById('editor-type').value;
  const pgWrapper = document.getElementById('pg-options-wrapper');
  const isianWrapper = document.getElementById('isian-wrapper');

  if (type === 'isian') {
    pgWrapper.style.display = 'none';
    isianWrapper.style.display = 'flex';
  } else {
    pgWrapper.style.display = 'flex';
    isianWrapper.style.display = 'none';
  }
}

function addQuestionToList() {
  const narrative = document.getElementById('editor-narrative').value.trim();
  if (!narrative) {
    alert('Narasi soal tidak boleh kosong.');
    return;
  }

  const category = document.getElementById('editor-cat').value;
  const type = document.getElementById('editor-type').value;
  const discussion = document.getElementById('editor-discussion').value.trim();

  let qObj = {
    category: category,
    type: type,
    narrative: narrative,
    discussion: discussion,
    expected_answer: '',
    options: []
  };

  if (type === 'pilihan_ganda') {
    const selectedRadio = document.querySelector('input[name="correct_opt_temp"]:checked');
    const correctLabel = selectedRadio ? selectedRadio.value : 'A';
    ['A', 'B', 'C', 'D', 'E'].forEach(lbl => {
      const txt = document.getElementById(`opt-text-${lbl}`).value.trim() || `Pilihan ${lbl}`;
      const score = parseFloat(document.getElementById(`opt-score-${lbl}`).value) || 0;
      qObj.options.push({
        label: lbl,
        text: txt,
        score: score,
        is_correct: (lbl === correctLabel)
      });
    });
  } else {
    qObj.expected_answer = document.getElementById('editor-expected').value.trim();
  }

  questionsData.push(qObj);
  renderQuestionsList();

  // Reset inputs
  document.getElementById('editor-narrative').value = '';
  document.getElementById('editor-discussion').value = '';
  document.getElementById('editor-expected').value = '';
  ['A', 'B', 'C', 'D', 'E'].forEach(lbl => {
    document.getElementById(`opt-text-${lbl}`).value = '';
  });
  toggleEditor();
}

function removeQuestion(index) {
  if (confirm('Hapus soal ini dari daftar?')) {
    questionsData.splice(index, 1);
    renderQuestionsList();
  }
}

function renderQuestionsList() {
  const container = document.getElementById('questions-list-container');
  const countBadge = document.getElementById('question-count-badge');
  const reviewTotal = document.getElementById('review-total-q');
  const reviewDist = document.getElementById('review-dist-cat');
  const jsonInput = document.getElementById('questions-json-input');

  jsonInput.value = JSON.stringify(questionsData);
  countBadge.innerText = questionsData.length;
  reviewTotal.innerText = `${questionsData.length} Soal`;

  let counts = { TWK: 0, TIU: 0, TKP: 0 };

  if (questionsData.length === 0) {
    container.innerHTML = `<p id="empty-questions-msg" style="font-size: 12px; color: var(--text-muted); text-align: center; padding: 12px 0;">Belum ada soal ditambahkan. Klik tombol "+ Tambah Soal" di atas.</p>`;
    reviewDist.innerText = 'TWK: 0 | TIU: 0 | TKP: 0';
    return;
  }

  let html = '';
  questionsData.forEach((q, idx) => {
    counts[q.category] = (counts[q.category] || 0) + 1;
    html += `
      <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 12px; display: flex; align-items: flex-start; justify-content: space-between; gap: 8px;">
        <div style="display: flex; flex-direction: column; gap: 2px;">
          <div style="display: flex; align-items: center; gap: 6px;">
            <span style="font-weight: 800; font-size: 12px; color: var(--dark-navy);">#${idx + 1}</span>
            <span class="badge badge-amber" style="font-size: 10px;">${q.category}</span>
            <span style="font-size: 11px; color: var(--text-muted);">${q.type === 'pilihan_ganda' ? 'Pilihan Ganda' : 'Isian'}</span>
          </div>
          <p style="font-size: 12px; color: var(--dark-navy); margin-top: 4px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
            ${q.narrative}
          </p>
        </div>
        <button type="button" onclick="removeQuestion(${idx})" style="background: none; border: none; color: #DC2626; cursor: pointer; font-size: 11px; font-weight: 700;">Hapus</button>
      </div>
    `;
  });

  container.innerHTML = html;
  reviewDist.innerText = `TWK: ${counts.TWK || 0} | TIU: ${counts.TIU || 0} | TKP: ${counts.TKP || 0}`;
}
</script>
<?= $this->endSection() ?>
