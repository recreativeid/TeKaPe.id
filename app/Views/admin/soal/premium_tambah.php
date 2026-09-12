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

  <form id="tambah-premium-form" action="<?= base_url("{$rolePrefix}/soal/savePackage") ?>" method="post">
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

      <!-- All-Access Subscription Model Notice -->
      <div style="background: #FFFBEB; border: 1.5px solid #FDE68A; border-radius: var(--radius-sm); padding: 12px 14px; margin-bottom: 12px; display: flex; gap: 10px; align-items: flex-start;">
        <div style="color: var(--warm-amber); font-size: 18px; line-height: 1;">⭐</div>
        <div style="font-size: 12px; color: #92400E; line-height: 1.5;">
          <strong>Model All-Access Try Out Kedinasan:</strong> Paket soal premium ini tidak dikenakan biaya eceran per paket. Seluruh siswa yang memiliki status <strong>Langganan Premium Aktif</strong> otomatis mendapatkan akses penuh untuk mengerjakan paket ini.
        </div>
      </div>

      <input type="hidden" name="price" value="0">
      <div class="form-group">
        <label class="form-label">Durasi Ujian Simulasi CAT (Menit)</label>
        <input type="number" name="duration_days" class="form-control" value="100" placeholder="100 menit standar CAT BKN" required>
        <small style="font-size: 11px; color: var(--text-muted);">Alokasi waktu pengerjaan simulasi CAT (standar SKD Kedinasan: 100 menit).</small>
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
        <button type="button" class="btn btn-amber btn-sm" onclick="openNewEditor()" style="font-weight: 700;">
          + Tambah Soal
        </button>
      </div>

      <!-- In-Page Question Creation Interface -->
      <div id="question-editor-card" style="display: none; background: #FFFBEB; border: 1.5px solid #FDE68A; border-radius: var(--radius-md); padding: 16px; margin-bottom: 14px; flex-direction: column; gap: 12px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <span id="editor-badge-title" style="font-size: 13px; font-weight: 700; color: var(--dark-navy);">Editor Butir Soal Premium</span>
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
          <textarea id="editor-narrative" class="form-control form-control-auto" rows="1" oninput="autoExpand(this)" placeholder="Tuliskan narasi soal lengkap..."></textarea>
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

        <!-- 3. Pilihan Ganda Section -->
        <div id="pg-options-wrapper" style="display: flex; flex-direction: column; gap: 6px;">
          <label class="form-label" style="font-size: 11px; margin-bottom: 2px;">3. Opsi Jawaban & Skor (Pilih radio untuk kunci benar):</label>
          <?php foreach (['A', 'B', 'C', 'D', 'E'] as $opt): ?>
            <div style="display: flex; align-items: center; gap: 6px; background: #FFFDF5; padding: 4px 8px; border-radius: 4px; border: 1px solid #FEF3C7;">
              <input type="radio" name="correct_opt_temp" value="<?= $opt ?>" <?= $opt === 'A' ? 'checked' : '' ?> title="Kunci Jawaban" style="accent-color: var(--dark-navy);">
              <span style="font-weight: 700; font-size: 12px; width: 14px;"><?= $opt ?></span>
              <textarea id="opt-text-<?= $opt ?>" class="form-control form-control-auto" rows="1" oninput="autoExpand(this)" style="flex: 1;" placeholder="Teks opsi <?= $opt ?>"></textarea>
              <input type="number" id="opt-score-<?= $opt ?>" class="form-control" style="height: 38px; width: 54px; font-size: 11px; text-align: center; padding: 4px;" value="<?= $opt === 'A' ? '5' : '0' ?>" title="Skor opsi">
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Isian Section -->
        <div id="isian-wrapper" style="display: none; flex-direction: column; gap: 6px;">
          <div class="form-group">
            <label class="form-label" style="font-size: 11px;">3. Kunci Jawaban Isian</label>
            <input type="text" id="editor-expected" class="form-control" style="height: 38px; font-size: 13px;" placeholder="Jawaban yang benar">
          </div>
        </div>

        <!-- 4. Pembahasan Mendalam -->
        <div class="form-group">
          <label class="form-label" style="font-size: 11px;">4. Pembahasan Mendalam</label>
          <textarea id="editor-discussion" class="form-control form-control-auto" rows="1" oninput="autoExpand(this)" placeholder="Tuliskan trik, rumus cepat, atau kunci pembahasan (opsional)..."></textarea>
        </div>

        <button type="button" id="btn-submit-q" class="btn btn-amber btn-sm" onclick="saveQuestionToList()" style="height: 38px; font-size: 13px;">
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
          <span>Model Akses:</span>
          <strong style="color: var(--warm-amber);">All-Access Kedinasan (Langganan Bulanan)</strong>
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
  document.getElementById('editor-badge-title').innerText = 'Editor Butir Soal Premium (Baru)';
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
      <div class="q-card-compact" style="border-color: #FDE68A;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px;">
          <div style="display: flex; align-items: center; gap: 6px;">
            <span style="font-weight: 800; font-size: 12px; color: var(--dark-navy); background: #FFFBEB; padding: 2px 7px; border-radius: 4px; border: 1px solid #FDE68A;">#${idx + 1}</span>
            <span class="badge badge-amber" style="font-size: 10px;">${q.category}</span>
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
