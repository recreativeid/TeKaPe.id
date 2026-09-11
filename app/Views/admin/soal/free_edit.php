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
      <h1 class="page-title">Edit Paket Free</h1>
      <p class="page-subtitle"><?= $selectedPackage ? 'Workspace edit: ' . esc($selectedPackage['title']) : 'Pilih paket yang ingin diedit' ?></p>
    </div>
  </div>

  <?php if (!$selectedPackage): ?>
    <!-- PACKAGE SELECTOR WORKSPACE -->
    <div class="card" style="padding: 16px;">
      <form action="<?= base_url("{$rolePrefix}/soal/free/edit") ?>" method="get" style="display: flex; gap: 8px;">
        <input type="text" name="q" class="form-control" placeholder="Cari nama paket..." value="<?= esc($search ?? '') ?>" style="height: 42px;">
        <button type="submit" class="btn btn-primary btn-sm" style="height: 42px; width: 70px;">Cari</button>
      </form>
    </div>

    <div style="display: flex; flex-direction: column; gap: 10px;">
      <?php if (!empty($packages)): ?>
        <?php foreach ($packages as $pkg): ?>
          <div class="card" style="padding: 16px; display: flex; flex-direction: column; gap: 8px;">
            <div style="display: flex; align-items: flex-start; justify-content: space-between;">
              <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
                <?= esc($pkg['title']) ?>
              </h3>
              <span class="badge <?= $pkg['status'] === 'active' ? 'badge-sage' : 'badge-peach' ?>">
                <?= ucfirst($pkg['status']) ?>
              </span>
            </div>

            <p style="font-size: 12px; color: var(--text-muted); line-height: 1.4;">
              <?= esc($pkg['description'] ?? 'Tidak ada deskripsi') ?>
            </p>

            <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--border-light); padding-top: 10px; margin-top: 4px;">
              <span style="font-size: 12px; color: var(--text-muted);">
                <?= $pkg['question_count'] ?? 0 ?> Soal • Free
              </span>
              <a href="<?= base_url("{$rolePrefix}/soal/free/edit/{$pkg['id']}") ?>" class="btn btn-primary btn-sm" style="font-weight: 700;">
                Buka Editor &rarr;
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="font-size: 13px; color: var(--text-muted); text-align: center; padding: 20px;">
          Tidak ada paket soal ditemukan.
        </p>
      <?php endif; ?>
    </div>

  <?php else: ?>
    <!-- SELECTED PACKAGE EDIT WORKSPACE -->
    
    <!-- Top Package Information -->
    <div class="card" style="padding: 18px;">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <span class="badge badge-navy">Paket Free #<?= $selectedPackage['id'] ?></span>
        <a href="<?= base_url("{$rolePrefix}/soal/free/edit") ?>" style="font-size: 12px; color: var(--text-muted); text-decoration: none;">
          Ganti Paket
        </a>
      </div>

      <form action="<?= base_url("admin/soal/updatePackage/{$selectedPackage['id']}") ?>" method="post" style="display: flex; flex-direction: column; gap: 12px; margin-top: 6px;">
        <?= csrf_field() ?>
        
        <div class="form-group">
          <label class="form-label">Nama Paket</label>
          <input type="text" name="title" class="form-control" value="<?= esc($selectedPackage['title']) ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">Deskripsi</label>
          <textarea name="description" class="form-control" rows="2"><?= esc($selectedPackage['description']) ?></textarea>
        </div>

        <div style="display: flex; gap: 10px; align-items: center;">
          <div class="form-group" style="flex: 1;">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
              <option value="active" <?= $selectedPackage['status'] === 'active' ? 'selected' : '' ?>>Aktif</option>
              <option value="draft" <?= $selectedPackage['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
            </select>
          </div>
          <div style="flex: 1; margin-top: 20px;">
            <button type="submit" class="btn btn-secondary" style="height: 48px; font-size: 13px;">
              Simpan Info
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- Question Management Section -->
    <div class="card" style="padding: 18px;">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
        <div>
          <h2 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
            Daftar Soal
          </h2>
          <span style="font-size: 12px; color: var(--text-muted);">
            Total: <?= count($selectedPackage['questions'] ?? []) ?> Soal
          </span>
        </div>
        <button type="button" class="btn btn-primary btn-sm" onclick="openNewQuestionModal()" style="font-weight: 700;">
          + Tambah Soal
        </button>
      </div>

      <!-- List of Existing Questions -->
      <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 8px;">
        <?php if (!empty($selectedPackage['questions'])): ?>
          <?php foreach ($selectedPackage['questions'] as $q): ?>
            <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 14px; display: flex; flex-direction: column; gap: 8px;">
              
              <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 6px;">
                  <span style="font-weight: 800; font-size: 12px; color: var(--dark-navy);">#<?= $q['question_number'] ?></span>
                  <span class="badge badge-navy" style="font-size: 10px;"><?= esc($q['category_code'] ?? 'TWK') ?></span>
                  <span style="font-size: 11px; color: var(--text-muted);">
                    <?= $q['type'] === 'pilihan_ganda' ? 'Pilihan Ganda' : 'Isian' ?>
                  </span>
                </div>

                <!-- Move Category Dropdown Form -->
                <form action="<?= base_url("admin/soal/moveQuestionCategory/{$q['id']}") ?>" method="post" style="display: flex; align-items: center; gap: 4px;">
                  <?= csrf_field() ?>
                  <select name="target_category" style="font-size: 10px; padding: 2px 4px; border-radius: 4px; border: 1px solid var(--border-color);">
                    <option value="TWK" <?= ($q['category_code'] ?? '') === 'TWK' ? 'selected' : '' ?>>Ke TWK</option>
                    <option value="TIU" <?= ($q['category_code'] ?? '') === 'TIU' ? 'selected' : '' ?>>Ke TIU</option>
                    <option value="TKP" <?= ($q['category_code'] ?? '') === 'TKP' ? 'selected' : '' ?>>Ke TKP</option>
                  </select>
                  <button type="submit" style="font-size: 10px; padding: 2px 6px; background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 4px; cursor: pointer;">Pindah</button>
                </form>
              </div>

              <p style="font-size: 13px; color: var(--dark-navy); line-height: 1.45;">
                <?= esc($q['narrative']) ?>
              </p>

              <?php if (!empty($q['options'])): ?>
                <div style="display: flex; flex-direction: column; gap: 3px; font-size: 11px; color: var(--text-muted); background: #FFFFFF; padding: 8px 10px; border-radius: 4px; border: 1px solid var(--border-light);">
                  <?php foreach ($q['options'] as $o): ?>
                    <div style="display: flex; align-items: center; gap: 6px;">
                      <span style="font-weight: 700; color: <?= $o['is_correct'] ? 'var(--soft-sage-green)' : 'inherit' ?>;"><?= $o['option_label'] ?>.</span>
                      <span style="flex: 1;"><?= esc($o['option_text']) ?></span>
                      <span style="font-weight: 600; font-size: 10px; color: var(--warm-amber);">[Skor: <?= $o['score'] ?>]</span>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>

              <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px; border-top: 1px solid var(--border-light); padding-top: 8px;">
                <a href="<?= base_url("admin/soal/deleteQuestion/{$q['id']}") ?>" onclick="return confirm('Yakin ingin menghapus butir soal ini?')" style="font-size: 11px; font-weight: 700; color: #DC2626; text-decoration: none;">
                  Hapus Soal
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

    <!-- Modal Form: Tambah Soal Baru ke Paket Ini -->
    <div id="new-q-modal" class="modal-backdrop">
      <div class="modal-dialog" style="max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy);">
            Tambah Soal ke Paket
          </h3>
          <button type="button" data-modal-close style="background: none; border: none; font-size: 18px; cursor: pointer;">&times;</button>
        </div>

        <form action="<?= base_url("admin/soal/saveQuestion") ?>" method="post" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 12px;">
          <?= csrf_field() ?>
          <input type="hidden" name="package_id" value="<?= $selectedPackage['id'] ?>">

          <div class="form-group">
            <label class="form-label">Kategori Soal</label>
            <select name="category_code" class="form-control">
              <option value="TWK">Tes Wawasan Kebangsaan (TWK)</option>
              <option value="TIU">Tes Inteligensia Umum (TIU)</option>
              <option value="TKP">Tes Karakteristik Pribadi (TKP)</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Jenis Soal</label>
            <select name="type" id="modal-q-type" class="form-control" onchange="toggleModalQType()">
              <option value="pilihan_ganda" selected>Pilihan Ganda (A-E)</option>
              <option value="isian">Isian Singkat</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Narasi Soal</label>
            <textarea name="narrative" class="form-control" rows="3" placeholder="Tuliskan teks soal..." required></textarea>
          </div>

          <div class="form-group">
            <label class="form-label">Upload Gambar (Opsional)</label>
            <input type="file" name="image" class="form-control" style="padding: 8px;">
          </div>

          <!-- Multiple Choice Options -->
          <div id="modal-pg-wrapper" style="display: flex; flex-direction: column; gap: 8px;">
            <label class="form-label">Pilihan Jawaban & Skor:</label>
            <?php foreach (['A', 'B', 'C', 'D', 'E'] as $opt): ?>
              <div style="display: flex; align-items: center; gap: 6px; background: #F8FAFC; padding: 6px 8px; border-radius: 4px;">
                <input type="radio" name="correct_option" value="<?= $opt ?>" <?= $opt === 'A' ? 'checked' : '' ?> title="Kunci Jawaban">
                <span style="font-weight: 700; font-size: 12px; width: 14px;"><?= $opt ?></span>
                <input type="text" name="option_<?= $opt ?>_text" class="form-control" style="height: 34px; font-size: 11px;" placeholder="Teks opsi <?= $opt ?>" required>
                <input type="number" name="option_<?= $opt ?>_score" class="form-control" style="height: 34px; width: 62px; font-size: 11px; text-align: center;" value="<?= $opt === 'A' ? '5' : '0' ?>" title="Skor opsi">
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Essay Section -->
          <div id="modal-isian-wrapper" style="display: none; flex-direction: column; gap: 8px;">
            <div class="form-group">
              <label class="form-label">Kunci Jawaban Isian</label>
              <input type="text" name="expected_answer" class="form-control" placeholder="Jawaban yang benar">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Pembahasan Lengkap</label>
            <textarea name="discussion" class="form-control" rows="2" placeholder="Analisis pembahasan..."></textarea>
          </div>

          <button type="submit" class="btn btn-primary" style="margin-top: 6px;">
            Simpan Soal
          </button>
        </form>
      </div>
    </div>

  <?php endif; ?>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function openNewQuestionModal() {
  document.getElementById('new-q-modal').classList.add('open');
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
</script>
<?= $this->endSection() ?>
