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
              <div style="display: flex; align-items: center; gap: 6px;">
                <span class="badge badge-amber" style="font-size: 10px; padding: 2px 6px;">All-Access Kedinasan</span>
                <span style="font-size: 12px; color: var(--text-muted);">• 📝 <?= $pkg['question_count'] ?? 0 ?> Soal CAT</span>
              </div>
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
    
    <!-- Compact Package Header Strip (No Redundant Form) -->
    <div class="card" style="padding: 14px 16px; background: #FFFFFF; border-color: #FDE68A;">
      <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
        <div style="display: flex; flex-direction: column; gap: 3px;">
          <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
            <span class="badge badge-amber" style="font-size: 10px;">Paket Premium #<?= $selectedPackage['id'] ?></span>
            <span class="badge <?= $selectedPackage['status'] === 'active' ? 'badge-sage' : 'badge-peach' ?>" style="font-size: 10px;">
              <?= $selectedPackage['status'] === 'active' ? 'Aktif' : 'Draft' ?>
            </span>
            <span class="badge badge-navy" style="font-size: 10px;">
              <?= count($selectedPackage['questions'] ?? []) ?> Soal
            </span>
            <span style="font-size: 11px; color: var(--text-muted);">
              Durasi: <strong><?= esc($selectedPackage['duration_days'] ?? 100) ?> Menit</strong> • Tentor: <strong><?= esc($selectedPackage['author_name'] ?? 'Admin') ?></strong>
            </span>
          </div>
          <h2 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy); margin: 0;">
            <?= esc($selectedPackage['title']) ?>
          </h2>
          <?php if (!empty($selectedPackage['description'])): ?>
            <p style="font-size: 12px; color: var(--text-muted); margin: 0; line-height: 1.4;">
              <?= esc($selectedPackage['description']) ?>
            </p>
          <?php endif; ?>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
          <button type="button" class="btn btn-secondary btn-sm" onclick="togglePackageDetails()" style="font-size: 11px; height: 32px; padding: 0 10px;">
            ⚙️ Edit Info Paket
          </button>
          <a href="<?= base_url("{$rolePrefix}/soal/premium/edit") ?>" class="btn btn-secondary btn-sm" style="font-size: 11px; height: 32px; padding: 0 10px;">
            Ganti Paket
          </a>
          <a href="<?= base_url("{$rolePrefix}/soal/delete-package/{$selectedPackage['id']}") ?>" onclick="return confirm('PERINGATAN: Menghapus paket ini akan menghapus seluruh butir soal. Lanjutkan?')" class="btn btn-outline-danger btn-sm" style="font-size: 11px; height: 32px; padding: 0 10px; color: #DC2626; border-color: #FCA5A5;">
            Hapus
          </a>
        </div>
      </div>

      <!-- Collapsible Package Edit Form (Only opens on explicit click) -->
      <div id="package-details-collapse" style="display: none; margin-top: 12px; padding-top: 12px; border-top: 1px dashed var(--border-color);">
        <form action="<?= base_url("{$rolePrefix}/soal/update-package/{$selectedPackage['id']}") ?>" method="post" style="display: flex; flex-direction: column; gap: 10px;">
          <?= csrf_field() ?>
          <input type="hidden" name="price" value="0">
          <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 10px;">
            <div class="form-group">
              <label class="form-label" style="font-size: 11px;">Nama Paket Soal</label>
              <input type="text" name="title" class="form-control" value="<?= esc($selectedPackage['title']) ?>" style="height: 38px; font-size: 13px;" required>
            </div>
            <div class="form-group">
              <label class="form-label" style="font-size: 11px;">Durasi (Menit)</label>
              <input type="number" name="duration_days" class="form-control" value="<?= esc($selectedPackage['duration_days'] ?? 100) ?>" style="height: 38px; font-size: 13px;" required>
            </div>
            <div class="form-group">
              <label class="form-label" style="font-size: 11px;">Status</label>
              <select name="status" class="form-control filter-guru-select" style="height: 38px;">
                <option value="active" <?= $selectedPackage['status'] === 'active' ? 'selected' : '' ?>>Aktif</option>
                <option value="draft" <?= $selectedPackage['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label" style="font-size: 11px;">Deskripsi</label>
            <input type="text" name="description" class="form-control" value="<?= esc($selectedPackage['description']) ?>" style="height: 38px; font-size: 13px;">
          </div>
          <div style="display: flex; justify-content: flex-end; gap: 8px;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="togglePackageDetails()" style="font-size: 11px;">Batal</button>
            <button type="submit" class="btn btn-primary btn-sm" style="font-size: 11px;">Simpan Perubahan Info</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Compact Categories Pill Bar -->
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; background: #FFFDF5; padding: 8px 12px; border-radius: var(--radius-sm); border: 1px solid #FDE68A;">
      <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
        <span style="font-size: 11px; font-weight: 700; color: var(--dark-navy);">Kategori:</span>
        <?php if (!empty($selectedPackage['categories'])): ?>
          <?php foreach ($selectedPackage['categories'] as $cat): ?>
            <span class="badge badge-amber" style="font-size: 10px; cursor: pointer;" onclick='openEditCatModal(<?= json_encode($cat) ?>)' title="Klik untuk rename/atur kategori">
              <?= esc($cat['code']) ?> (<?= esc($cat['name']) ?>) ✏️
            </span>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <button type="button" class="btn btn-secondary btn-sm" onclick="openModal('add-cat-modal')" style="font-size: 11px; height: 28px; padding: 0 8px;">
        + Tambah Kategori
      </button>
    </div>

    <!-- 3. Question Management Section: QUESTION LIST VIEW -->
    <div id="section-question-list">
      <div class="card" style="padding: 18px; border-radius: 12px; background: #FFFFFF; border-color: #FDE68A;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
          <div>
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy); margin: 0;">
              Daftar Butir Soal Premium (<span id="count-soal-header"><?= count($selectedPackage['questions'] ?? []) ?></span>)
            </h2>
            <span style="font-size: 11px; color: var(--text-muted);">
              Kelola butir pertanyaan pada paket ini. Klik tombol di kanan untuk membuka editor soal full halaman.
            </span>
          </div>
          <button type="button" class="btn btn-amber btn-sm" onclick="openNewQuestionEditor()" style="font-weight: 700; height: 36px; padding: 0 16px; display: inline-flex; align-items: center; gap: 6px;">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            + Tambah Soal
          </button>
        </div>

        <!-- Compact Question List -->
        <div style="display: flex; flex-direction: column; gap: 8px;">
          <?php if (!empty($selectedPackage['questions'])): ?>
            <?php foreach ($selectedPackage['questions'] as $q): ?>
              <?php
                $kunci = '-';
                if (!empty($q['options'])) {
                  foreach ($q['options'] as $o) {
                    if ($o['is_correct']) { $kunci = $o['option_label']; break; }
                  }
                } elseif ($q['type'] === 'isian') {
                  $kunci = $q['expected_answer'] ?? '-';
                }
              ?>
              <div class="q-card-compact" style="border-color: #FDE68A;">
                <!-- Top Row: Number, Category, Type, Key, Actions -->
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px;">
                  <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="font-weight: 800; font-size: 12px; color: var(--dark-navy); background: #FFFBEB; padding: 2px 7px; border-radius: 4px; border: 1px solid #FDE68A;">#<?= $q['question_number'] ?></span>
                    <span class="badge badge-amber" style="font-size: 10px;"><?= esc($q['category_code'] ?? 'TWK') ?></span>
                    <span class="badge badge-light" style="font-size: 10px; border: 1px solid var(--border-color);">
                      <?= $q['type'] === 'pilihan_ganda' ? 'PG' : 'Isian' ?>
                    </span>
                    <span class="badge badge-sage" style="font-size: 10px;">Kunci: <?= esc($kunci) ?></span>
                  </div>

                  <div style="display: flex; align-items: center; gap: 6px;">
                    <!-- Move Category inline -->
                    <form action="<?= base_url("{$rolePrefix}/soal/move-question-category/{$q['id']}") ?>" method="post" style="display: inline-flex; align-items: center; gap: 3px; margin: 0;">
                      <?= csrf_field() ?>
                      <select name="target_category" class="filter-guru-select" style="height: 28px !important; min-height: 28px !important; padding: 2px 20px 2px 6px !important; font-size: 10px !important; width: 75px;" onchange="this.form.submit()" title="Pindah Kategori">
                        <?php if (!empty($selectedPackage['categories'])): ?>
                          <?php foreach ($selectedPackage['categories'] as $cOpt): ?>
                            <option value="<?= esc($cOpt['code']) ?>" <?= ($q['category_code'] ?? '') === $cOpt['code'] ? 'selected' : '' ?>>
                              <?= esc($cOpt['code']) ?>
                            </option>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                    </form>

                    <button type="button" class="btn btn-secondary btn-sm" onclick="editExistingQuestion(<?= $q['id'] ?>)" style="height: 28px; font-size: 11px; padding: 0 10px; font-weight: 700;">
                      ✏️ Edit
                    </button>
                    <a href="<?= base_url("{$rolePrefix}/soal/delete-question/{$q['id']}") ?>" onclick="return confirm('Yakin ingin menghapus butir soal #<?= $q['question_number'] ?>?')" style="font-size: 11px; font-weight: 700; color: #DC2626; text-decoration: none; padding: 4px 6px;" title="Hapus Soal">
                      🗑️
                    </a>
                  </div>
                </div>

                <!-- Narration (Compact 1-2 lines) -->
                <div style="font-size: 13px; color: var(--dark-navy); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                  <?= esc($q['narrative']) ?>
                </div>

                <!-- Options Compact Chip Preview -->
                <?php if (!empty($q['options'])): ?>
                  <div style="display: flex; flex-wrap: wrap; gap: 4px; font-size: 11px; color: var(--text-muted);">
                    <?php foreach ($q['options'] as $o): ?>
                      <span style="background: <?= $o['is_correct'] ? '#ECFDF5' : '#FFFDF5' ?>; border: 1px solid <?= $o['is_correct'] ? '#A7F3D0' : '#FEF3C7' ?>; color: <?= $o['is_correct'] ? '#065F46' : 'inherit' ?>; padding: 2px 6px; border-radius: 4px; font-weight: <?= $o['is_correct'] ? '700' : '400' ?>;">
                        <?= $o['option_label'] ?>. <?= esc(mb_strimwidth($o['option_text'], 0, 26, '..')) ?>
                      </span>
                    <?php endforeach; ?>
                  </div>
                <?php elseif ($q['type'] === 'isian'): ?>
                  <div style="font-size: 11px; color: var(--text-muted);">
                    Kunci Isian: <strong><?= esc($q['expected_answer'] ?? '-') ?></strong>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="font-size: 12px; color: var(--text-muted); text-align: center; padding: 24px 0; background: #FFFDF5; border-radius: var(--radius-sm); border: 1px dashed #FDE68A;">
              Belum ada butir soal pada paket ini. Klik tombol <strong>"+ Tambah Soal"</strong> untuk membuka editor soal full halaman.
            </p>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- ============================================================== -->
    <!-- FULL PAGE QUESTION EDITOR (Bukan Pop Up, Full Halaman & Ringkas)-->
    <!-- Ukuran kolom 1 baris awal & dinamis berubah mengikuti panjang huruf-->
    <!-- ============================================================== -->
    <div id="section-question-editor" style="display: none; flex-direction: column; gap: 14px;">
      
      <!-- Top Action Bar -->
      <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; background: #FFFFFF; border: 1px solid #FDE68A; border-radius: 12px; padding: 14px 18px; box-shadow: 0 1px 3px rgba(30, 34, 56, 0.04);">
        <div style="display: flex; align-items: center; gap: 12px;">
          <button type="button" onclick="closeQuestionEditor()" class="back-btn" title="Kembali ke Daftar Soal" style="width: 36px; height: 36px; cursor: pointer;">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
          </button>
          <div>
            <h2 id="q-editor-title" style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 700; color: var(--dark-navy); margin: 0;">
              Tambah Butir Soal Premium
            </h2>
            <span style="font-size: 12px; color: var(--text-muted);">
              Paket: <strong><?= esc($selectedPackage['title']) ?></strong> &bull; Halaman Editor Soal
            </span>
          </div>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
          <button type="button" onclick="closeQuestionEditor()" class="btn btn-secondary btn-sm" style="height: 38px; padding: 0 16px; font-weight: 600;">
            Batal
          </button>
          <button type="button" onclick="document.getElementById('q-form').requestSubmit();" id="editor-top-submit-btn" class="btn btn-amber btn-sm" style="height: 38px; padding: 0 20px; font-weight: 700;">
            💾 Simpan Soal
          </button>
        </div>
      </div>

      <!-- Main Form Card Full Width -->
      <div class="card" style="padding: 22px; border-radius: 14px; background: #FFFFFF; border: 1px solid #FDE68A; box-shadow: 0 1px 4px rgba(30, 34, 56, 0.04);">
        <form id="q-form" action="<?= base_url("{$rolePrefix}/soal/save-question") ?>" method="post" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 16px;">
          <?= csrf_field() ?>
          <input type="hidden" name="package_id" value="<?= $selectedPackage['id'] ?>">
          <input type="hidden" name="question_id" id="modal-q-id" value="">
          <input type="hidden" name="remove_image" id="modal-remove-image" value="0">

          <!-- Row 1: Kategori, Nomor, Jenis Soal (3 Kolom Sejajar yang Ringkas) -->
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; background: #FFFDF5; padding: 14px; border-radius: 10px; border: 1px solid #FEF3C7;">
            <!-- Kategori -->
            <div class="form-group">
              <label class="form-label" style="font-size: 12px; font-weight: 700; color: var(--dark-navy);">1. Kategori Soal</label>
              <select name="category_code" id="modal-cat-select" class="form-control filter-guru-select" style="background: #FFFFFF;">
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

            <!-- Nomor Soal -->
            <div class="form-group">
              <label class="form-label" style="font-size: 12px; font-weight: 700; color: var(--dark-navy);">Nomor Soal</label>
              <input type="number" name="question_number" id="modal-q-num" class="form-control" style="height: 42px; font-size: 13px; background: #FFFFFF;" placeholder="Otomatis (Nomor urut)">
            </div>

            <!-- Jenis Soal -->
            <div class="form-group">
              <label class="form-label" style="font-size: 12px; font-weight: 700; color: var(--dark-navy);">Jenis Soal</label>
              <select name="type" id="modal-q-type" class="form-control filter-guru-select" onchange="toggleModalQType()" style="background: #FFFFFF;">
                <option value="pilihan_ganda" selected>Pilihan Ganda (A-E)</option>
                <option value="isian">Isian Singkat</option>
              </select>
            </div>
          </div>

          <!-- Row 2: Narasi Soal (1 Baris Awal, Auto-Expand) -->
          <div class="form-group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
              <label class="form-label" style="font-size: 12px; font-weight: 700; color: var(--dark-navy);">2. Narasi / Teks Pertanyaan Soal</label>
              <small style="font-size: 11px; color: var(--text-muted);">Tinggi kolom 1 baris awal & otomatis bertambah hanya saat teks melebihi batas baris</small>
            </div>
            <textarea name="narrative" id="modal-narrative" class="form-control form-control-auto" rows="1" oninput="autoExpand(this)" placeholder="Ketik teks pertanyaan soal di sini..." required></textarea>
          </div>

          <!-- Row 3: Gambar Soal (Opsional) -->
          <div class="form-group">
            <label class="form-label" style="font-size: 12px; font-weight: 700; color: var(--dark-navy);">Gambar Soal (Opsional)</label>
            <div id="modal-existing-img-wrapper" style="display: none; margin-bottom: 6px; align-items: center; gap: 10px;">
              <img id="modal-existing-img" src="" style="max-height: 70px; border-radius: 6px; border: 1px solid var(--border-color);">
              <button type="button" onclick="markRemoveImage()" class="btn btn-secondary btn-sm" style="color: #DC2626; height: 30px; font-size: 11px;">Hapus Gambar</button>
            </div>
            <input type="file" name="image" id="modal-image-input" accept="image/*" class="form-control" style="height: 38px; padding: 6px 12px; font-size: 12px;">
          </div>

          <!-- Row 4: Pilihan Ganda (A-E) -->
          <div id="modal-pg-wrapper" style="display: flex; flex-direction: column; gap: 8px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
              <label class="form-label" style="font-size: 12px; font-weight: 700; color: var(--dark-navy); margin: 0;">
                3. Pilihan Opsi Jawaban (Pilih radio untuk Kunci Benar):
              </label>
              <small style="font-size: 11px; color: var(--text-muted);">Masing-masing 1 baris, memanjang dinamis saat teks panjang</small>
            </div>

            <?php foreach (['A', 'B', 'C', 'D', 'E'] as $opt): ?>
              <div style="display: flex; align-items: center; gap: 8px; background: #FFFFFF; padding: 6px 10px; border-radius: 8px; border: 1px solid #FEF3C7;">
                <input type="radio" name="correct_option" id="radio-opt-<?= $opt ?>" value="<?= $opt ?>" <?= $opt === 'A' ? 'checked' : '' ?> title="Pilih sebagai Kunci Jawaban Benar" style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--warm-amber);">
                <span style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 13px; width: 16px; color: var(--dark-navy); text-align: center;"><?= $opt ?></span>
                <textarea name="option_<?= $opt ?>_text" id="modal-opt-text-<?= $opt ?>" class="form-control form-control-auto" rows="1" oninput="autoExpand(this)" style="flex: 1;" placeholder="Ketik teks pilihan <?= $opt ?>..."></textarea>
                <div style="display: flex; align-items: center; gap: 4px;" title="Skor bobot untuk opsi ini">
                  <span style="font-size: 11px; color: var(--text-muted);">Skor:</span>
                  <input type="number" name="option_<?= $opt ?>_score" id="modal-opt-score-<?= $opt ?>" class="form-control" style="height: 38px; width: 56px; font-size: 12px; text-align: center; padding: 4px; font-weight: 600;" value="<?= $opt === 'A' ? '5' : '0' ?>">
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Row 5: Kunci Jawaban Isian -->
          <div id="modal-isian-wrapper" style="display: none; flex-direction: column; gap: 6px;">
            <div class="form-group">
              <label class="form-label" style="font-size: 12px; font-weight: 700; color: var(--dark-navy);">3. Kunci Jawaban Isian</label>
              <input type="text" name="expected_answer" id="modal-expected" class="form-control" style="height: 38px; font-size: 13px;" placeholder="Jawaban isian yang diharapkan">
            </div>
          </div>

          <!-- Row 6: Pembahasan Lengkap -->
          <div class="form-group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
              <label class="form-label" style="font-size: 12px; font-weight: 700; color: var(--dark-navy);">4. Pembahasan / Penjelasan Kunci Jawaban</label>
              <small style="font-size: 11px; color: var(--text-muted);">Tinggi bertambah otomatis saat teks panjang</small>
            </div>
            <textarea name="discussion" id="modal-discussion" class="form-control form-control-auto" rows="1" oninput="autoExpand(this)" placeholder="Tuliskan pembahasan, trik cepat, atau materi pendukung (opsional)..."></textarea>
          </div>

          <!-- Bottom Actions -->
          <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 8px; padding-top: 14px; border-top: 1px solid #E2E8F0;">
            <button type="button" onclick="closeQuestionEditor()" class="btn btn-secondary" style="width: auto; height: 42px; padding: 0 20px; font-weight: 600;">
              Batal & Kembali
            </button>
            <button type="submit" id="modal-submit-btn" class="btn btn-amber" style="width: auto; height: 42px; padding: 0 24px; font-weight: 700;">
              Simpan Soal
            </button>
          </div>
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
function togglePackageDetails() {
  const panel = document.getElementById('package-details-collapse');
  if (panel) {
    panel.style.display = (panel.style.display === 'none' || panel.style.display === '') ? 'block' : 'none';
  }
}

// Auto-expand textarea: starts at 38px (1 line) and only expands when text exceeds 1 line
function autoExpand(el) {
  if (!el) return;
  el.style.height = '38px';
  if (el.scrollHeight > 38) {
    el.style.height = el.scrollHeight + 'px';
  }
}

function resetAllAutoExpands() {
  document.querySelectorAll('.form-control-auto').forEach(el => {
    autoExpand(el);
  });
}

// Open / Close Full Page Question Editor (No Popups!)
function openNewQuestionEditor() {
  document.getElementById('q-editor-title').innerText = 'Tambah Butir Soal Premium Baru';
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

  document.getElementById('modal-submit-btn').innerText = 'Simpan Soal';
  document.getElementById('editor-top-submit-btn').innerText = '💾 Simpan Soal';

  // Switch views: hide list, show full-page editor
  document.getElementById('section-question-list').style.display = 'none';
  const editor = document.getElementById('section-question-editor');
  editor.style.display = 'flex';
  editor.scrollIntoView({ behavior: 'smooth', block: 'start' });

  setTimeout(resetAllAutoExpands, 50);
}

function closeQuestionEditor() {
  document.getElementById('section-question-editor').style.display = 'none';
  const list = document.getElementById('section-question-list');
  list.style.display = 'block';
  list.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function editExistingQuestion(qId) {
  fetch('<?= base_url("{$rolePrefix}/soal/get-question") ?>/' + qId)
    .then(r => r.json())
    .then(res => {
      if (res.status === 'success') {
        const q = res.question;
        const opts = res.options;

        document.getElementById('q-editor-title').innerText = `Edit Butir Soal #${q.question_number}`;
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
        document.getElementById('editor-top-submit-btn').innerText = '💾 Perbarui Soal';

        // Switch views: hide list, show full-page editor
        document.getElementById('section-question-list').style.display = 'none';
        const editor = document.getElementById('section-question-editor');
        editor.style.display = 'flex';
        editor.scrollIntoView({ behavior: 'smooth', block: 'start' });

        setTimeout(resetAllAutoExpands, 60);
      } else {
        alert(res.message || 'Gagal mengambil data soal');
      }
    })
    .catch(err => {
      alert('Terjadi kesalahan saat memuat soal.');
    });
}

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
  setTimeout(resetAllAutoExpands, 50);
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
