<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php $rolePrefix = ($role ?? 'admin') === 'tentor' ? 'tentor' : 'admin'; ?>

<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button -->
  <div class="page-header-nav">
    <a href="<?= base_url("{$rolePrefix}/soal/free") ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Tambah Paket Free</h1>
      <p class="page-subtitle">Buat paket soal dan masukkan butir pertanyaan</p>
    </div>
  </div>

  <!-- Step Indicator -->
  <div style="display: flex; align-items: center; justify-content: space-between; background: #FFFFFF; padding: 12px 16px; border-radius: var(--radius-md); border: 1px solid var(--border-color); font-size: 11px; font-weight: 700; color: var(--text-muted);">
    <span style="color: var(--dark-navy); display: flex; align-items: center; gap: 4px;">
      <span style="width: 18px; height: 18px; border-radius: 50%; background: var(--dark-navy); color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 10px;">1</span> Info
    </span>
    <span>&rarr;</span>
    <span style="color: var(--dark-navy); display: flex; align-items: center; gap: 4px;">
      <span style="width: 18px; height: 18px; border-radius: 50%; background: var(--dark-navy); color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 10px;">2</span> Soal
    </span>
    <span>&rarr;</span>
    <span style="display: flex; align-items: center; gap: 4px;">
      <span style="width: 18px; height: 18px; border-radius: 50%; background: var(--border-color); color: var(--text-muted); display: inline-flex; align-items: center; justify-content: center; font-size: 10px;">3</span> Review
    </span>
  </div>

  <form id="tambah-paket-form" action="<?= base_url("{$rolePrefix}/soal/savePackage") ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="type" value="free">
    <input type="hidden" name="price" value="0">
    <input type="hidden" name="duration_days" value="30">
    <input type="hidden" name="questions_json" id="questions-json-input" value="[]">

    <!-- SECTION 1: Informasi Paket -->
    <div class="card" style="padding: 18px;">
      <h2 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy); margin-bottom: 8px;">
        1. Informasi Paket
      </h2>

      <div class="form-group">
        <label class="form-label" for="title">Nama Paket Soal</label>
        <input type="text" name="title" id="title" class="form-control" placeholder="Contoh: Try Out TWK Nasionalisme #2" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="description">Deskripsi Paket</label>
        <textarea name="description" id="description" class="form-control" rows="2" placeholder="Tuliskan petunjuk atau cakupan materi..."></textarea>
      </div>

      <div class="form-group">
        <label class="form-label" for="status">Status Paket</label>
        <select name="status" id="status" class="form-control">
          <option value="active" selected>Aktif (Dapat dikerjakan murid)</option>
          <option value="draft">Draft (Disimpan sementara)</option>
        </select>
      </div>
    </div>

    <!-- SECTION 2: Daftar Soal -->
    <div class="card" style="padding: 18px; margin-top: 14px;">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
          2. Daftar Soal (<span id="question-count-badge">0</span>)
        </h2>
        <button type="button" class="btn btn-secondary btn-sm" onclick="openNewEditor()" style="font-weight: 700;">
          + Tambah Soal
        </button>
      </div>

      <!-- In-Page Expandable Question Creation Interface -->
      <div id="question-editor-card" style="display: none; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: var(--radius-md); padding: 16px; margin-bottom: 14px; flex-direction: column; gap: 12px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <span id="editor-badge-title" style="font-size: 13px; font-weight: 700; color: var(--dark-navy);">Editor Butir Pertanyaan</span>
          <button type="button" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 12px; font-weight: 600;" onclick="toggleEditor()">Tutup</button>
        </div>
        <input type="hidden" id="editing-index" value="-1">

        <!-- 1. Kategori Soal -->
        <div class="form-group">
          <label class="form-label">1. Kategori Soal</label>
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

        <!-- 2. Narasi Soal (1-Line Auto Expand) -->
        <div class="form-group">
          <label class="form-label" style="font-size: 11px;">2. Narasi Soal (1 Baris Awal, Auto-Expand)</label>
          <textarea id="editor-narrative" class="form-control form-control-auto" rows="1" oninput="autoExpand(this)" placeholder="Ketik narasi pertanyaan soal..."></textarea>
        </div>

        <!-- Upload Gambar Soal -->
        <div class="form-group">
          <label class="form-label" style="font-size: 11px;">Upload Gambar Soal (Opsional)</label>
          <input type="file" id="editor-image-file" accept="image/*" class="form-control" style="height: 38px; padding: 6px 10px; font-size: 12px;" onchange="previewEditorImage(this)">
          <div id="editor-image-preview-wrapper" style="display: none; margin-top: 4px; align-items: center; gap: 8px;">
            <img id="editor-img-preview" src="" style="max-height: 60px; max-width: 100%; border-radius: 4px; border: 1px solid var(--border-color);">
            <button type="button" onclick="removeEditorImage()" class="btn btn-secondary btn-sm" style="color: #DC2626; height: 26px; font-size: 10px;">Hapus Gambar</button>
          </div>
        </div>

        <!-- 3. Pilihan Ganda / Isian Section -->
        <div id="pg-options-wrapper" style="display: flex; flex-direction: column; gap: 6px;">
          <label class="form-label" style="font-size: 11px; margin-bottom: 2px;">3. Pilihan Jawaban (Pilih radio untuk kunci benar):</label>
          <?php foreach (['A', 'B', 'C', 'D', 'E'] as $opt): ?>
            <div style="display: flex; align-items: center; gap: 6px; background: #FFFFFF; padding: 4px 8px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
              <input type="radio" name="correct_opt_temp" value="<?= $opt ?>" <?= $opt === 'A' ? 'checked' : '' ?> title="Kunci Benar" style="accent-color: var(--dark-navy);">
              <span style="font-weight: 700; font-size: 12px; width: 14px;"><?= $opt ?></span>
              <textarea id="opt-text-<?= $opt ?>" class="form-control form-control-auto" rows="1" oninput="autoExpand(this)" style="flex: 1;" placeholder="Teks opsi <?= $opt ?>"></textarea>
              <input type="number" id="opt-score-<?= $opt ?>" class="form-control" style="height: 38px; width: 54px; font-size: 11px; text-align: center; padding: 4px;" value="<?= $opt === 'A' ? '5' : '0' ?>" placeholder="Skor" title="Skor opsi">
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Isian Singkat Section -->
        <div id="isian-wrapper" style="display: none; flex-direction: column; gap: 6px;">
          <div class="form-group">
            <label class="form-label" style="font-size: 11px;">3. Kunci Jawaban Isian</label>
            <input type="text" id="editor-expected" class="form-control" style="height: 38px; font-size: 13px;" placeholder="Jawaban yang diharapkan">
          </div>
        </div>

        <!-- 4. Pembahasan Lengkap -->
        <div class="form-group">
          <label class="form-label" style="font-size: 11px;">4. Pembahasan Lengkap</label>
          <textarea id="editor-discussion" class="form-control form-control-auto" rows="1" oninput="autoExpand(this)" placeholder="Tuliskan analisis, trik, atau kunci pembahasan (opsional)..."></textarea>
        </div>

        <button type="button" id="btn-submit-q" class="btn btn-primary btn-sm" onclick="saveQuestionToList()" style="height: 38px; font-size: 13px;">
          + Masukkan ke Daftar Soal
        </button>
      </div>

      <!-- Created Questions Container -->
      <div id="questions-list-container" style="display: flex; flex-direction: column; gap: 8px;">
        <p id="empty-questions-msg" style="font-size: 12px; color: var(--text-muted); text-align: center; padding: 12px 0;">
          Belum ada soal ditambahkan. Klik tombol "+ Tambah Soal" di atas.
        </p>
      </div>
    </div>

    <!-- SECTION 3: Review -->
    <div class="card" style="padding: 18px; margin-top: 14px;">
      <h2 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy); margin-bottom: 8px;">
        3. Review Paket
      </h2>
      <div style="font-size: 13px; color: var(--text-muted); display: flex; flex-direction: column; gap: 4px;">
        <div style="display: flex; justify-content: space-between;">
          <span>Jumlah Soal:</span>
          <strong id="review-total-q" style="color: var(--dark-navy);">0 Soal</strong>
        </div>
        <div style="display: flex; justify-content: space-between;">
          <span>Distribusi Kategori:</span>
          <span id="review-dist-cat" style="color: var(--dark-navy); font-weight: 600;">TWK: 0 | TIU: 0 | TKP: 0</span>
        </div>
        <div style="display: flex; justify-content: space-between;">
          <span>Akses:</span>
          <strong style="color: var(--soft-sage-green);">Gratis (Free)</strong>
        </div>
      </div>
    </div>

    <!-- Sticky Bottom CTA -->
    <div class="sticky-bottom-bar">
      <button type="submit" class="btn btn-primary">
        Simpan Paket Free
      </button>
    </div>
  </form>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let questionsData = [];
let currentBase64Image = null;

function autoExpand(el) {
  if (!el) return;
  el.style.height = 'auto';
  const newH = Math.max(38, el.scrollHeight);
  el.style.height = newH + 'px';
}

function resetAllAutoExpands() {
  document.querySelectorAll('.form-control-auto').forEach(el => {
    autoExpand(el);
  });
}

function toggleEditor() {
  const card = document.getElementById('question-editor-card');
  card.style.display = (card.style.display === 'none' || card.style.display === '') ? 'flex' : 'none';
  if (card.style.display === 'flex') {
    setTimeout(resetAllAutoExpands, 50);
  }
}

function openNewEditor() {
  document.getElementById('editing-index').value = '-1';
  document.getElementById('editor-badge-title').innerText = 'Editor Butir Pertanyaan (Baru)';
  document.getElementById('btn-submit-q').innerText = '+ Masukkan ke Daftar Soal';
  resetEditorInputs();
  const card = document.getElementById('question-editor-card');
  card.style.display = 'flex';
  setTimeout(resetAllAutoExpands, 50);
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
  setTimeout(resetAllAutoExpands, 50);
}

function previewEditorImage(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      currentBase64Image = e.target.result;
      document.getElementById('editor-img-preview').src = currentBase64Image;
      document.getElementById('editor-image-preview-wrapper').style.display = 'flex';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function removeEditorImage() {
  currentBase64Image = null;
  document.getElementById('editor-image-file').value = '';
  document.getElementById('editor-img-preview').src = '';
  document.getElementById('editor-image-preview-wrapper').style.display = 'none';
}

function resetEditorInputs() {
  document.getElementById('editor-narrative').value = '';
  document.getElementById('editor-discussion').value = '';
  document.getElementById('editor-expected').value = '';
  removeEditorImage();
  ['A', 'B', 'C', 'D', 'E'].forEach(lbl => {
    document.getElementById(`opt-text-${lbl}`).value = '';
    document.getElementById(`opt-score-${lbl}`).value = (lbl === 'A') ? '5' : '0';
  });
  const radioA = document.querySelector('input[name="correct_opt_temp"][value="A"]');
  if (radioA) radioA.checked = true;
}

function saveQuestionToList() {
  const narrative = document.getElementById('editor-narrative').value.trim();
  if (!narrative) {
    alert('Narasi soal tidak boleh kosong.');
    return;
  }

  const category = document.getElementById('editor-cat').value;
  const type = document.getElementById('editor-type').value;
  const discussion = document.getElementById('editor-discussion').value.trim();
  const editIdx = parseInt(document.getElementById('editing-index').value);

  let qObj = {
    category: category,
    type: type,
    narrative: narrative,
    discussion: discussion,
    image_url: currentBase64Image,
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

  if (editIdx >= 0 && editIdx < questionsData.length) {
    questionsData[editIdx] = qObj;
  } else {
    questionsData.push(qObj);
  }

  renderQuestionsList();
  resetEditorInputs();
  toggleEditor();
}

function editDraftQuestion(index) {
  const q = questionsData[index];
  if (!q) return;

  document.getElementById('editing-index').value = index;
  document.getElementById('editor-badge-title').innerText = `Edit Butir Soal #${index + 1}`;
  document.getElementById('btn-submit-q').innerText = 'Perbarui Butir Soal';

  document.getElementById('editor-cat').value = q.category;
  document.getElementById('editor-type').value = q.type;
  toggleQuestionTypeFields();
  document.getElementById('editor-narrative').value = q.narrative;
  document.getElementById('editor-discussion').value = q.discussion || '';

  if (q.image_url) {
    currentBase64Image = q.image_url;
    document.getElementById('editor-img-preview').src = currentBase64Image;
    document.getElementById('editor-image-preview-wrapper').style.display = 'flex';
  } else {
    removeEditorImage();
  }

  if (q.type === 'pilihan_ganda' && q.options) {
    q.options.forEach(opt => {
      const txtInp = document.getElementById(`opt-text-${opt.label}`);
      const scoreInp = document.getElementById(`opt-score-${opt.label}`);
      if (txtInp) txtInp.value = opt.text;
      if (scoreInp) scoreInp.value = opt.score;
      if (opt.is_correct) {
        const r = document.querySelector(`input[name="correct_opt_temp"][value="${opt.label}"]`);
        if (r) r.checked = true;
      }
    });
  } else {
    document.getElementById('editor-expected').value = q.expected_answer || '';
  }

  const card = document.getElementById('question-editor-card');
  card.style.display = 'flex';
  card.scrollIntoView({ behavior: 'smooth' });
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

    let kunci = '-';
    if (q.type === 'pilihan_ganda' && q.options) {
      const correct = q.options.find(o => o.is_correct);
      if (correct) kunci = correct.label;
    } else if (q.type === 'isian') {
      kunci = q.expected_answer || '-';
    }

    html += `
      <div class="q-card-compact">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px;">
          <div style="display: flex; align-items: center; gap: 6px;">
            <span style="font-weight: 800; font-size: 12px; color: var(--dark-navy); background: #F1F5F9; padding: 2px 7px; border-radius: 4px;">#${idx + 1}</span>
            <span class="badge badge-navy" style="font-size: 10px;">${q.category}</span>
            <span class="badge badge-light" style="font-size: 10px; border: 1px solid var(--border-color);">${q.type === 'pilihan_ganda' ? 'PG' : 'Isian'}</span>
            <span class="badge badge-sage" style="font-size: 10px;">Kunci: ${kunci}</span>
          </div>
          <div style="display: flex; gap: 6px;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="editDraftQuestion(${idx})" style="height: 26px; font-size: 10px; padding: 0 8px; font-weight: 700;">✏️ Edit</button>
            <button type="button" onclick="removeQuestion(${idx})" style="background: none; border: none; color: #DC2626; cursor: pointer; font-size: 12px; font-weight: 700; padding: 2px 6px;" title="Hapus">🗑️</button>
          </div>
        </div>
        <div style="font-size: 12px; color: var(--dark-navy); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
          ${q.narrative}
        </div>
        ${q.image_url ? `<img src="${q.image_url}" style="max-height: 50px; max-width: 100px; border-radius: 4px; border: 1px solid var(--border-light); object-fit: contain;">` : ''}
      </div>
    `;
  });

  container.innerHTML = html;
  reviewDist.innerText = `TWK: ${counts.TWK || 0} | TIU: ${counts.TIU || 0} | TKP: ${counts.TKP || 0}`;
}
</script>
<?= $this->endSection() ?>
