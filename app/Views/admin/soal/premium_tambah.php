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

        <!-- 2. Narasi Soal -->
        <div class="form-group">
          <label class="form-label">2. Narasi Soal</label>
          <textarea id="editor-narrative" class="form-control" rows="3" placeholder="Tuliskan narasi soal lengkap..."></textarea>
        </div>

        <!-- Upload Gambar Soal -->
        <div class="form-group">
          <label class="form-label">Upload Gambar Soal (Opsional)</label>
          <input type="file" id="editor-image-file" accept="image/*" class="form-control" style="padding: 6px;" onchange="previewEditorImage(this)">
          <div id="editor-image-preview-wrapper" style="display: none; margin-top: 6px; align-items: center; gap: 8px;">
            <img id="editor-img-preview" src="" style="max-height: 100px; max-width: 100%; border-radius: 4px; border: 1px solid var(--border-color);">
            <button type="button" onclick="removeEditorImage()" class="btn btn-secondary btn-sm" style="color: #DC2626; height: 28px; font-size: 11px;">Hapus Gambar</button>
          </div>
        </div>

        <!-- 3. Pilihan Ganda Section -->
        <div id="pg-options-wrapper" style="display: flex; flex-direction: column; gap: 8px;">
          <label class="form-label">3. Opsi Jawaban & Pembobotan Skor:</label>
          <p style="font-size: 11px; color: var(--text-muted); margin: 0;">Pilih radio button untuk kunci jawaban benar. Tentukan skor tiap opsi.</p>
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
            <label class="form-label">3. Kunci Jawaban Isian</label>
            <input type="text" id="editor-expected" class="form-control" placeholder="Jawaban yang benar">
          </div>
        </div>

        <!-- 4. Pembahasan Mendalam -->
        <div class="form-group">
          <label class="form-label">4. Pembahasan Mendalam</label>
          <textarea id="editor-discussion" class="form-control" rows="2" placeholder="Tuliskan trik, rumus cepat, dan pembahasan komprehensif..."></textarea>
        </div>

        <button type="button" id="btn-submit-q" class="btn btn-primary btn-sm" onclick="saveQuestionToList()" style="height: 40px; font-size: 13px;">
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
let currentBase64Image = null;

function toggleEditor() {
  const card = document.getElementById('question-editor-card');
  card.style.display = (card.style.display === 'none' || card.style.display === '') ? 'flex' : 'none';
}

function openNewEditor() {
  document.getElementById('editing-index').value = '-1';
  document.getElementById('editor-badge-title').innerText = 'Editor Butir Soal Premium (Baru)';
  document.getElementById('btn-submit-q').innerText = '+ Masukkan ke Daftar Soal';
  resetEditorInputs();
  const card = document.getElementById('question-editor-card');
  card.style.display = 'flex';
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

  let html = '';
  questionsData.forEach((q, idx) => {
    counts[q.category] = (counts[q.category] || 0) + 1;
    html += `
      <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 12px; display: flex; flex-direction: column; gap: 8px;">
        <div style="display: flex; align-items: flex-start; justify-content: space-between;">
          <div style="display: flex; align-items: center; gap: 6px;">
            <span style="font-weight: 800; font-size: 12px; color: var(--dark-navy);">#${idx + 1}</span>
            <span class="badge badge-amber" style="font-size: 10px;">${q.category}</span>
            <span style="font-size: 11px; color: var(--text-muted);">${q.type === 'pilihan_ganda' ? 'Pilihan Ganda' : 'Isian'}</span>
          </div>
          <div style="display: flex; gap: 8px;">
            <button type="button" onclick="editDraftQuestion(${idx})" style="background: none; border: none; color: var(--dark-navy); cursor: pointer; font-size: 11px; font-weight: 700;">Edit</button>
            <button type="button" onclick="removeQuestion(${idx})" style="background: none; border: none; color: #DC2626; cursor: pointer; font-size: 11px; font-weight: 700;">Hapus</button>
          </div>
        </div>
        <p style="font-size: 12px; color: var(--dark-navy); margin: 0; line-height: 1.45;">
          ${q.narrative}
        </p>
        ${q.image_url ? `<img src="${q.image_url}" style="max-height: 80px; max-width: 140px; border-radius: 4px; border: 1px solid var(--border-light); object-fit: contain;">` : ''}
      </div>
    `;
  });

  container.innerHTML = html;
  reviewDist.innerText = `TWK: ${counts.TWK || 0} | TIU: ${counts.TIU || 0} | TKP: ${counts.TKP || 0}`;
}
</script>
<?= $this->endSection() ?>
