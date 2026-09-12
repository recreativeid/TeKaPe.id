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
      <h1 class="page-title">Edit Paket Premium</h1>
      <p class="page-subtitle"><?= $selectedPackage ? 'Workspace: ' . esc($selectedPackage['title']) : 'Pilih paket premium yang ingin diedit' ?></p>
    </div>
  </div>

  <?php if (!$selectedPackage): ?>
    <!-- PACKAGE SELECTOR WORKSPACE -->
    <div class="card" style="padding: 16px;">
      <form action="<?= base_url("{$rolePrefix}/soal/premium/edit") ?>" method="get" style="display: flex; gap: 8px;">
        <input type="text" name="q" class="form-control" placeholder="Cari paket premium..." value="<?= esc($search ?? '') ?>" style="height: 42px;">
        <button type="submit" class="btn btn-amber btn-sm" style="height: 42px; width: 70px;">Cari</button>
      </form>
    </div>

    <div style="display: flex; flex-direction: column; gap: 10px;">
      <?php if (!empty($packages)): ?>
        <?php foreach ($packages as $pkg): ?>
          <div class="card" style="padding: 16px; display: flex; flex-direction: column; gap: 8px; border-color: #FDE68A;">
            <div style="display: flex; align-items: flex-start; justify-content: space-between;">
              <div>
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
                  <?= esc($pkg['title']) ?>
                </h3>
                <span style="font-size: 11px; color: var(--text-muted);">
                  Dibuat oleh: <strong><?= esc($pkg['author_name'] ?? 'Admin') ?></strong>
                </span>
              </div>
              <span class="badge <?= $pkg['status'] === 'active' ? 'badge-sage' : 'badge-peach' ?>">
                <?= ucfirst($pkg['status']) ?>
              </span>
            </div>

            <p style="font-size: 12px; color: var(--text-muted); line-height: 1.4;">
              <?= esc($pkg['description'] ?? 'Tidak ada deskripsi') ?>
            </p>

            <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--border-light); padding-top: 10px; margin-top: 4px;">
              <span style="font-size: 12px; color: var(--warm-amber); font-weight: 700;">
                Rp <?= number_format($pkg['price'], 0, ',', '.') ?> • <?= $pkg['question_count'] ?? 0 ?> Soal
              </span>
              <a href="<?= base_url("{$rolePrefix}/soal/premium/edit/{$pkg['id']}") ?>" class="btn btn-amber btn-sm" style="font-weight: 700;">
                Buka Editor &rarr;
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="font-size: 13px; color: var(--text-muted); text-align: center; padding: 20px;">
          Tidak ada paket premium ditemukan.
        </p>
      <?php endif; ?>
    </div>

  <?php else: ?>
    <!-- SELECTED PACKAGE EDIT WORKSPACE -->
    
    <!-- 1. Top Package Information -->
    <div class="card" style="padding: 18px; border-color: #FDE68A;">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 8px;">
          <span class="badge badge-amber">Paket Premium #<?= $selectedPackage['id'] ?></span>
          <span style="font-size: 12px; color: var(--text-muted);">Dibuat oleh: <strong><?= esc($selectedPackage['author_name'] ?? 'Admin') ?></strong></span>
        </div>
        <div style="display: flex; gap: 8px;">
          <a href="<?= base_url("{$rolePrefix}/soal/premium/edit") ?>" style="font-size: 12px; color: var(--text-muted); text-decoration: none;">
            Ganti Paket
          </a>
          <span style="color: var(--border-color);">|</span>
          <a href="<?= base_url("{$rolePrefix}/soal/delete-package/{$selectedPackage['id']}") ?>" onclick="return confirm('PERINGATAN: Menghapus paket ini akan menghapus seluruh butir soal dan kategorinya. Lanjutkan?')" style="font-size: 12px; color: #DC2626; text-decoration: none; font-weight: 700;">
            Hapus Paket
          </a>
        </div>
      </div>

      <form action="<?= base_url("{$rolePrefix}/soal/update-package/{$selectedPackage['id']}") ?>" method="post" style="display: flex; flex-direction: column; gap: 12px; margin-top: 10px;">
        <?= csrf_field() ?>
        
        <div class="form-group">
          <label class="form-label">Nama Paket Soal</label>
          <input type="text" name="title" class="form-control" value="<?= esc($selectedPackage['title']) ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">Deskripsi</label>
          <textarea name="description" class="form-control" rows="2"><?= esc($selectedPackage['description']) ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
          <div class="form-group">
            <label class="form-label">Tarif Layanan (Rp)</label>
            <input type="number" name="price" class="form-control" value="<?= esc($selectedPackage['price']) ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label">Durasi Akses (Hari)</label>
            <input type="number" name="duration_days" class="form-control" value="<?= esc($selectedPackage['duration_days']) ?>" required>
          </div>
        </div>

        <div style="display: flex; gap: 10px; align-items: center;">
          <div class="form-group" style="flex: 1;">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
              <option value="active" <?= $selectedPackage['status'] === 'active' ? 'selected' : '' ?>>Aktif (Dapat diakses murid Premium)</option>
              <option value="draft" <?= $selectedPackage['status'] === 'draft' ? 'selected' : '' ?>>Draft (Disimpan sementara)</option>
            </select>
          </div>
          <div style="flex: 1; margin-top: 20px;">
            <button type="submit" class="btn btn-secondary" style="height: 48px; font-size: 13px;">
              Simpan Informasi Paket
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- 2. Section Kelola Kategori Paket (TWK, TIU, TKP) -->
    <div class="card" style="padding: 18px;">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
        <div>
          <h2 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
            Kelola Kategori Paket
          </h2>
          <span style="font-size: 12px; color: var(--text-muted);">
            Kategori soal aktif (TWK, TIU, TKP, dsb)
          </span>
        </div>
        <button type="button" class="btn btn-secondary btn-sm" onclick="openModal('add-cat-modal')" style="font-weight: 700;">
          + Tambah Kategori
        </button>
      </div>

      <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 6px;">
        <?php if (!empty($selectedPackage['categories'])): ?>
          <?php foreach ($selectedPackage['categories'] as $cat): ?>
            <?php 
              $qInCat = 0;
              if (!empty($selectedPackage['questions'])) {
                foreach ($selectedPackage['questions'] as $qItem) {
                  if (($qItem['category_id'] ?? 0) == $cat['id']) $qInCat++;
                }
              }
            ?>
            <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 10px 14px; display: flex; align-items: center; justify-content: space-between;">
              <div style="display: flex; align-items: center; gap: 10px;">
                <span class="badge badge-amber" style="font-size: 11px;"><?= esc($cat['code']) ?></span>
                <div>
                  <strong style="font-size: 13px; color: var(--dark-navy);"><?= esc($cat['name']) ?></strong>
                  <span style="font-size: 11px; color: var(--text-muted); display: block;">
                    <?= $qInCat ?> Soal • Aturan: <?= esc($cat['scoring_rule'] ?? 'Standard') ?>
                  </span>
                </div>
              </div>
              <div style="display: flex; align-items: center; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick='openEditCatModal(<?= json_encode($cat) ?>)' style="height: 28px; font-size: 11px;">
                  Rename / Edit
                </button>
                <a href="<?= base_url("{$rolePrefix}/soal/delete-category/{$cat['id']}") ?>" onclick="return confirm('Hapus kategori <?= esc($cat['code']) ?>?')" style="font-size: 11px; color: #DC2626; font-weight: 700; text-decoration: none; padding: 4px;">
                  Hapus
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- 3. Question Management Section -->
    <div class="card" style="padding: 18px;">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
        <div>
          <h2 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
            Daftar Butir Soal Premium
          </h2>
          <span style="font-size: 12px; color: var(--text-muted);">
            Total: <?= count($selectedPackage['questions'] ?? []) ?> Butir Soal
          </span>
        </div>
        <button type="button" class="btn btn-amber btn-sm" onclick="openNewQuestionModal()" style="font-weight: 700;">
          + Tambah Soal
        </button>
      </div>

      <!-- List of Existing Questions -->
      <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 8px;">
        <?php if (!empty($selectedPackage['questions'])): ?>
          <?php foreach ($selectedPackage['questions'] as $q): ?>
            <div style="background: #FFFDF5; border: 1px solid #FDE68A; border-radius: var(--radius-sm); padding: 14px; display: flex; flex-direction: column; gap: 8px;">
              
              <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 6px;">
                  <span style="font-weight: 800; font-size: 12px; color: var(--dark-navy);">#<?= $q['question_number'] ?></span>
                  <span class="badge badge-amber" style="font-size: 10px;"><?= esc($q['category_code'] ?? 'TWK') ?></span>
                  <span style="font-size: 11px; color: var(--text-muted);">
                    <?= $q['type'] === 'pilihan_ganda' ? 'Pilihan Ganda' : 'Isian Singkat' ?>
                  </span>
                </div>

                <!-- Move Category Dropdown Form -->
                <form action="<?= base_url("{$rolePrefix}/soal/move-question-category/{$q['id']}") ?>" method="post" style="display: flex; align-items: center; gap: 4px;">
                  <?= csrf_field() ?>
                  <span style="font-size: 10px; color: var(--text-muted);">Pindah ke:</span>
                  <select name="target_category" style="font-size: 10px; padding: 2px 4px; border-radius: 4px; border: 1px solid var(--border-color);">
                    <?php if (!empty($selectedPackage['categories'])): ?>
                      <?php foreach ($selectedPackage['categories'] as $cOpt): ?>
                        <option value="<?= esc($cOpt['code']) ?>" <?= ($q['category_code'] ?? '') === $cOpt['code'] ? 'selected' : '' ?>>
                          <?= esc($cOpt['code']) ?>
                        </option>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <option value="TWK">TWK</option>
                      <option value="TIU">TIU</option>
                      <option value="TKP">TKP</option>
                    <?php endif; ?>
                  </select>
                  <button type="submit" style="font-size: 10px; padding: 2px 6px; background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 4px; cursor: pointer; font-weight: 600;">Pindah</button>
                </form>
              </div>

              <!-- Narration -->
              <p style="font-size: 13px; color: var(--dark-navy); line-height: 1.45; margin: 0;">
                <?= esc($q['narrative']) ?>
              </p>

              <!-- Image if any -->
              <?php if (!empty($q['image_url'])): ?>
                <div>
                  <img src="<?= esc($q['image_url']) ?>" alt="Gambar Soal" style="max-height: 120px; max-width: 100%; border-radius: 4px; border: 1px solid var(--border-color); object-fit: contain;">
                </div>
              <?php endif; ?>

              <!-- Options -->
              <?php if (!empty($q['options'])): ?>
                <div style="display: flex; flex-direction: column; gap: 3px; font-size: 11px; color: var(--text-muted); background: #FFFFFF; padding: 8px 10px; border-radius: 4px; border: 1px solid var(--border-light);">
                  <?php foreach ($q['options'] as $o): ?>
                    <div style="display: flex; align-items: center; gap: 6px;">
                      <span style="font-weight: 700; color: <?= $o['is_correct'] ? 'var(--soft-sage-green)' : 'inherit' ?>;"><?= $o['option_label'] ?>.</span>
                      <span style="flex: 1;"><?= esc($o['option_text']) ?></span>
                      <span style="font-weight: 600; font-size: 10px; color: var(--warm-amber);">[Skor: <?= $o['score'] ?>]</span>
                      <?php if ($o['is_correct']): ?>
                        <span class="badge badge-sage" style="font-size: 9px; padding: 1px 4px;">Kunci Benar</span>
                      <?php endif; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php elseif ($q['type'] === 'isian' && !empty($q['expected_answer'])): ?>
                <div style="font-size: 11px; background: #FFFFFF; padding: 6px 8px; border-radius: 4px; border: 1px solid var(--border-light);">
                  <span style="color: var(--text-muted);">Kunci Isian:</span> <strong><?= esc($q['expected_answer']) ?></strong>
                </div>
              <?php endif; ?>

              <?php if (!empty($q['discussion'])): ?>
                <div style="font-size: 11px; color: #475569; background: #FFFBEB; padding: 6px 10px; border-radius: 4px; border: 1px solid #FDE68A;">
                  <strong>Pembahasan:</strong> <?= esc($q['discussion']) ?>
                </div>
              <?php endif; ?>

              <!-- Question Action Buttons -->
              <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px; border-top: 1px solid var(--border-light); padding-top: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="editExistingQuestion(<?= $q['id'] ?>)" style="height: 30px; font-size: 11px; font-weight: 700;">
                  ✏️ Edit Soal
                </button>
                <a href="<?= base_url("{$rolePrefix}/soal/delete-question/{$q['id']}") ?>" onclick="return confirm('Yakin ingin menghapus butir soal #<?= $q['question_number'] ?>?')" style="font-size: 11px; font-weight: 700; color: #DC2626; text-decoration: none; padding: 4px 8px;">
                  Hapus
                </a>
              </div>

            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="font-size: 12px; color: var(--text-muted); text-align: center; padding: 16px 0;">
            Belum ada soal pada paket ini. Klik tombol "+ Tambah Soal" di atas.
          </p>
        <?php endif; ?>
      </div>
    </div>

    <!-- MODAL: Tambah / Edit Soal -->
    <div id="q-modal" class="modal-backdrop">
      <div class="modal-dialog" style="max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
          <h3 id="q-modal-title" style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
            Tambah Soal ke Paket
          </h3>
          <button type="button" onclick="closeModal('q-modal')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>

        <form id="q-form" action="<?= base_url("{$rolePrefix}/soal/save-question") ?>" method="post" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 12px;">
          <?= csrf_field() ?>
          <input type="hidden" name="package_id" value="<?= $selectedPackage['id'] ?>">
          <input type="hidden" name="question_id" id="modal-q-id" value="">
          <input type="hidden" name="remove_image" id="modal-remove-image" value="0">

          <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 10px;">
            <!-- 1. Kategori Soal -->
            <div class="form-group">
              <label class="form-label">1. Kategori Soal</label>
              <select name="category_code" id="modal-cat-select" class="form-control">
                <?php if (!empty($selectedPackage['categories'])): ?>
                  <?php foreach ($selectedPackage['categories'] as $cat): ?>
                    <option value="<?= esc($cat['code']) ?>"><?= esc($cat['code']) ?> - <?= esc($cat['name']) ?></option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="TWK">Tes Wawasan Kebangsaan (TWK)</option>
                  <option value="TIU">Tes Inteligensia Umum (TIU)</option>
                  <option value="TKP">Tes Karakteristik Pribadi (TKP)</option>
                <?php endif; ?>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Nomor Soal</label>
              <input type="number" name="question_number" id="modal-q-num" class="form-control" placeholder="Auto">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Jenis Soal</label>
            <select name="type" id="modal-q-type" class="form-control" onchange="toggleModalQType()">
              <option value="pilihan_ganda" selected>Pilihan Ganda (A-E)</option>
              <option value="isian">Isian Singkat</option>
            </select>
          </div>

          <!-- 2. Narasi Soal -->
          <div class="form-group">
            <label class="form-label">2. Narasi Soal</label>
            <textarea name="narrative" id="modal-narrative" class="form-control" rows="3" placeholder="Tuliskan teks pertanyaan lengkap..." required></textarea>
          </div>

          <!-- Upload / Ganti Gambar -->
          <div class="form-group">
            <label class="form-label">Gambar Soal (Opsional)</label>
            <div id="modal-existing-img-wrapper" style="display: none; margin-bottom: 6px; align-items: center; gap: 8px;">
              <img id="modal-existing-img" src="" style="max-height: 90px; border-radius: 4px; border: 1px solid var(--border-color);">
              <button type="button" onclick="markRemoveImage()" class="btn btn-secondary btn-sm" style="color: #DC2626; height: 28px; font-size: 11px;">Hapus Gambar</button>
            </div>
            <input type="file" name="image" id="modal-image-input" accept="image/*" class="form-control" style="padding: 6px;">
          </div>

          <!-- 3. Multiple Choice Options (A-E) -->
          <div id="modal-pg-wrapper" style="display: flex; flex-direction: column; gap: 8px;">
            <label class="form-label">3. Opsi Jawaban & Skor (Tentukan Kunci Benar):</label>
            <p style="font-size: 11px; color: var(--text-muted); margin: 0;">Pilih radio button untuk jawaban benar. Skor dapat disesuaikan (misal TKP 1-5, TWK 5 & 0).</p>
            <?php foreach (['A', 'B', 'C', 'D', 'E'] as $opt): ?>
              <div style="display: flex; align-items: center; gap: 6px; background: #F8FAFC; padding: 6px 8px; border-radius: 4px; border: 1px solid var(--border-light);">
                <input type="radio" name="correct_option" id="radio-opt-<?= $opt ?>" value="<?= $opt ?>" <?= $opt === 'A' ? 'checked' : '' ?> title="Kunci Jawaban Benar">
                <span style="font-weight: 700; font-size: 12px; width: 14px;"><?= $opt ?></span>
                <input type="text" name="option_<?= $opt ?>_text" id="modal-opt-text-<?= $opt ?>" class="form-control" style="height: 34px; font-size: 11px;" placeholder="Teks opsi <?= $opt ?>">
                <input type="number" name="option_<?= $opt ?>_score" id="modal-opt-score-<?= $opt ?>" class="form-control" style="height: 34px; width: 64px; font-size: 11px; text-align: center;" value="<?= $opt === 'A' ? '5' : '0' ?>" title="Skor opsi">
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Essay Section -->
          <div id="modal-isian-wrapper" style="display: none; flex-direction: column; gap: 8px;">
            <div class="form-group">
              <label class="form-label">3. Kunci Jawaban Isian</label>
              <input type="text" name="expected_answer" id="modal-expected" class="form-control" placeholder="Jawaban yang diharapkan">
            </div>
          </div>

          <!-- 4. Pembahasan Lengkap -->
          <div class="form-group">
            <label class="form-label">4. Pembahasan Lengkap</label>
            <textarea name="discussion" id="modal-discussion" class="form-control" rows="2" placeholder="Tuliskan trik, rumus cepat, atau analisis pembahasan..."></textarea>
          </div>

          <button type="submit" id="modal-submit-btn" class="btn btn-amber" style="margin-top: 6px;">
            Simpan Soal
          </button>
        </form>
      </div>
    </div>

    <!-- MODAL: Tambah / Edit Kategori -->
    <div id="add-cat-modal" class="modal-backdrop">
      <div class="modal-dialog">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
          <h3 id="cat-modal-title" style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
            Tambah Kategori Baru
          </h3>
          <button type="button" onclick="closeModal('add-cat-modal')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>

        <form id="cat-form" action="<?= base_url("{$rolePrefix}/soal/save-category") ?>" method="post" style="display: flex; flex-direction: column; gap: 12px;">
          <?= csrf_field() ?>
          <input type="hidden" name="package_id" value="<?= $selectedPackage['id'] ?>">
          <input type="hidden" name="category_id" id="form-cat-id" value="">

          <div class="form-group">
            <label class="form-label">Kode Kategori (Singkat)</label>
            <input type="text" name="code" id="form-cat-code" class="form-control" placeholder="Contoh: TWK, TIU, TKP, TPA" required>
          </div>

          <div class="form-group">
            <label class="form-label">Nama Lengkap Kategori</label>
            <input type="text" name="name" id="form-cat-name" class="form-control" placeholder="Contoh: Tes Wawasan Kebangsaan" required>
          </div>

          <div class="form-group">
            <label class="form-label">Aturan Penilaian / Keterangan</label>
            <input type="text" name="scoring_rule" id="form-cat-rule" class="form-control" placeholder="Contoh: 5 per benar, 0 salah / Skala 1 - 5">
          </div>

          <button type="submit" id="form-cat-btn" class="btn btn-primary" style="margin-top: 6px;">
            Simpan Kategori
          </button>
        </form>
      </div>
    </div>

  <?php endif; ?>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function openModal(id) {
  document.getElementById(id).classList.add('open');
}

function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}

function toggleModalQType() {
  const type = document.getElementById('modal-q-type').value;
  const pgWrapper = document.getElementById('modal-pg-wrapper');
  const isianWrapper = document.getElementById('modal-isian-wrapper');

  if (type === 'isian') {
    pgWrapper.style.display = 'none';
    isianWrapper.style.display = 'flex';
  } else {
    pgWrapper.style.display = 'flex';
    isianWrapper.style.display = 'none';
  }
}

function openNewQuestionModal() {
  document.getElementById('q-modal-title').innerText = 'Tambah Soal Baru';
  document.getElementById('modal-q-id').value = '';
  document.getElementById('modal-q-num').value = '';
  document.getElementById('modal-narrative').value = '';
  document.getElementById('modal-discussion').value = '';
  document.getElementById('modal-expected').value = '';
  document.getElementById('modal-remove-image').value = '0';
  document.getElementById('modal-existing-img-wrapper').style.display = 'none';
  document.getElementById('modal-image-input').value = '';
  document.getElementById('modal-q-type').value = 'pilihan_ganda';
  toggleModalQType();

  ['A', 'B', 'C', 'D', 'E'].forEach(lbl => {
    document.getElementById(`modal-opt-text-${lbl}`).value = '';
    document.getElementById(`modal-opt-score-${lbl}`).value = (lbl === 'A') ? '5' : '0';
  });
  const rA = document.getElementById('radio-opt-A');
  if (rA) rA.checked = true;

  document.getElementById('modal-submit-btn').innerText = 'Simpan Soal Baru';
  openModal('q-modal');
}

function editExistingQuestion(qId) {
  fetch('<?= base_url("{$rolePrefix}/soal/get-question") ?>/' + qId)
    .then(r => r.json())
    .then(res => {
      if (res.status === 'success') {
        const q = res.question;
        const opts = res.options;

        document.getElementById('q-modal-title').innerText = `Edit Butir Soal #${q.question_number}`;
        document.getElementById('modal-q-id').value = q.id;
        document.getElementById('modal-q-num').value = q.question_number;
        document.getElementById('modal-narrative').value = q.narrative;
        document.getElementById('modal-discussion').value = q.discussion || '';
        document.getElementById('modal-expected').value = q.expected_answer || '';
        document.getElementById('modal-q-type').value = q.type;
        toggleModalQType();

        // Category
        if (res.categories) {
          const matchedCat = res.categories.find(c => c.id == q.category_id);
          if (matchedCat) {
            document.getElementById('modal-cat-select').value = matchedCat.code;
          }
        }

        // Image
        document.getElementById('modal-remove-image').value = '0';
        document.getElementById('modal-image-input').value = '';
        if (q.image_url) {
          document.getElementById('modal-existing-img').src = q.image_url;
          document.getElementById('modal-existing-img-wrapper').style.display = 'flex';
        } else {
          document.getElementById('modal-existing-img-wrapper').style.display = 'none';
        }

        // Options
        ['A', 'B', 'C', 'D', 'E'].forEach(lbl => {
          document.getElementById(`modal-opt-text-${lbl}`).value = '';
          document.getElementById(`modal-opt-score-${lbl}`).value = '0';
        });

        if (opts && opts.length > 0) {
          opts.forEach(o => {
            const txt = document.getElementById(`modal-opt-text-${o.option_label}`);
            const scr = document.getElementById(`modal-opt-score-${o.option_label}`);
            if (txt) txt.value = o.option_text;
            if (scr) scr.value = o.score;
            if (parseInt(o.is_correct) === 1) {
              const r = document.getElementById(`radio-opt-${o.option_label}`);
              if (r) r.checked = true;
            }
          });
        }

        document.getElementById('modal-submit-btn').innerText = 'Perbarui Soal';
        openModal('q-modal');
      } else {
        alert(res.message || 'Gagal mengambil data soal');
      }
    })
    .catch(err => {
      alert('Terjadi kesalahan saat memuat soal.');
    });
}

function markRemoveImage() {
  document.getElementById('modal-remove-image').value = '1';
  document.getElementById('modal-existing-img-wrapper').style.display = 'none';
}

function openEditCatModal(cat) {
  document.getElementById('cat-modal-title').innerText = 'Edit Kategori ' + cat.code;
  document.getElementById('form-cat-id').value = cat.id;
  document.getElementById('form-cat-code').value = cat.code;
  document.getElementById('form-cat-name').value = cat.name;
  document.getElementById('form-cat-rule').value = cat.scoring_rule || '';
  document.getElementById('form-cat-btn').innerText = 'Perbarui Kategori';
  openModal('add-cat-modal');
}
</script>
<?= $this->endSection() ?>
