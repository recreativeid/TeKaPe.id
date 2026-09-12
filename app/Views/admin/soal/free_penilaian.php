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
      <h1 class="page-title">Sistem Penilaian</h1>
      <p class="page-subtitle">Atur skor soal dan cara menghitung nilai akhir murid.</p>
    </div>
  </div>

  <!-- Package Selector if multiple exist -->
  <?php if (!empty($packages) && count($packages) > 1): ?>
    <div class="card" style="padding: 14px;">
      <label class="form-label" style="font-size: 11px;">Pilih Paket Soal Target:</label>
      <select onchange="location.href='<?= base_url("{$rolePrefix}/soal/free/penilaian/") ?>/' + this.value" class="form-control" style="height: 40px; font-size: 13px;">
        <?php foreach ($packages as $p): ?>
          <option value="<?= $p['id'] ?>" <?= ($activePackage && $activePackage['id'] == $p['id']) ? 'selected' : '' ?>>
            <?= esc($p['title']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  <?php endif; ?>

  <form action="<?= base_url("{$rolePrefix}/soal/save-scoring") ?>" method="post" style="display: flex; flex-direction: column; gap: 16px;">
    <?= csrf_field() ?>
    <input type="hidden" name="package_id" value="<?= $activePackage['id'] ?? 1 ?>">

    <!-- Category Scoring Settings -->
    <div class="card" style="padding: 18px; gap: 14px;">
      <h2 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy);">
        Pengaturan Skor per Kategori
      </h2>

      <!-- TWK Category Card -->
      <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 14px; display: flex; flex-direction: column; gap: 10px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <div style="display: flex; align-items: center; gap: 8px;">
            <span class="badge badge-navy">TWK</span>
            <span style="font-weight: 700; font-size: 13px; color: var(--dark-navy);">Tes Wawasan Kebangsaan</span>
          </div>
          <label style="display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
            <input type="checkbox" name="twk_enabled" id="twk-enable-cb" value="1" <?= ($scoring['twk_enabled'] ?? 1) ? 'checked' : '' ?> onchange="toggleCategoryScoring('twk')">
            Aktif
          </label>
        </div>

        <div class="form-group" id="twk-settings-group">
          <label class="form-label" style="font-size: 11px;">Aturan Penilaian</label>
          <input type="text" name="twk_rule" class="form-control" style="height: 38px; font-size: 12px;" value="<?= esc($scoring['twk_score_rule'] ?? '5 per jawaban benar') ?>" placeholder="Contoh: 5 per jawaban benar">
        </div>
      </div>

      <!-- TIU Category Card -->
      <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 14px; display: flex; flex-direction: column; gap: 10px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <div style="display: flex; align-items: center; gap: 8px;">
            <span class="badge badge-navy">TIU</span>
            <span style="font-weight: 700; font-size: 13px; color: var(--dark-navy);">Tes Inteligensia Umum</span>
          </div>
          <label style="display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
            <input type="checkbox" name="tiu_enabled" id="tiu-enable-cb" value="1" <?= ($scoring['tiu_enabled'] ?? 1) ? 'checked' : '' ?> onchange="toggleCategoryScoring('tiu')">
            Aktif
          </label>
        </div>

        <div class="form-group" id="tiu-settings-group">
          <label class="form-label" style="font-size: 11px;">Aturan Penilaian</label>
          <input type="text" name="tiu_rule" class="form-control" style="height: 38px; font-size: 12px;" value="<?= esc($scoring['tiu_score_rule'] ?? '5 per jawaban benar') ?>" placeholder="Contoh: 5 per jawaban benar">
        </div>
      </div>

      <!-- TKP Category Card -->
      <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 14px; display: flex; flex-direction: column; gap: 10px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <div style="display: flex; align-items: center; gap: 8px;">
            <span class="badge badge-navy">TKP</span>
            <span style="font-weight: 700; font-size: 13px; color: var(--dark-navy);">Tes Karakteristik Pribadi</span>
          </div>
          <label style="display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
            <input type="checkbox" name="tkp_enabled" id="tkp-enable-cb" value="1" <?= ($scoring['tkp_enabled'] ?? 1) ? 'checked' : '' ?> onchange="toggleCategoryScoring('tkp')">
            Aktif
          </label>
        </div>

        <div class="form-group" id="tkp-settings-group">
          <label class="form-label" style="font-size: 11px;">Aturan Penilaian</label>
          <input type="text" name="tkp_rule" class="form-control" style="height: 38px; font-size: 12px;" value="<?= esc($scoring['tkp_score_rule'] ?? 'Skala 1 - 5 per opsi jawaban') ?>" placeholder="Contoh: Skala 1 - 5 per opsi jawaban">
        </div>
      </div>
    </div>

    <!-- Visually Prominent Formula Card -->
    <div class="formula-card">
      <span class="formula-title">Rumus Nilai Akhir</span>
      
      <div class="formula-equation">
        Nilai Akhir = <br>
        (Jumlah Nilai Seluruh Kategori) ÷ <span id="formula-divisor-text">3</span>
      </div>

      <div style="display: flex; flex-direction: column; gap: 6px; margin-top: 4px;">
        <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #94A3B8; font-weight: 700;">
          Simulasi Perhitungan Langsung
        </span>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px;">
          <div>
            <label style="font-size: 10px; color: #CBD5E1;">Nilai TWK</label>
            <input type="number" id="calc-twk" class="form-control" style="height: 34px; font-size: 12px; text-align: center; background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); color: #fff;" value="80">
          </div>
          <div>
            <label style="font-size: 10px; color: #CBD5E1;">Nilai TIU</label>
            <input type="number" id="calc-tiu" class="form-control" style="height: 34px; font-size: 12px; text-align: center; background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); color: #fff;" value="85">
          </div>
          <div>
            <label style="font-size: 10px; color: #CBD5E1;">Nilai TKP</label>
            <input type="number" id="calc-tkp" class="form-control" style="height: 34px; font-size: 12px; text-align: center; background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); color: #fff;" value="82">
          </div>
        </div>

        <div class="formula-preview" style="margin-top: 8px;">
          <span>Hasil Perhitungan:</span>
          <span id="calc-final-result" style="font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 800; color: var(--warm-amber);">
            82.33
          </span>
        </div>
      </div>
    </div>

    <div style="font-size: 12px; color: var(--text-muted); text-align: center;">
      ℹ️ Perubahan sistem penilaian berlaku otomatis untuk paket ini.
    </div>

    <button type="submit" class="btn btn-primary" style="margin-top: 4px;">
      Simpan Pengaturan
    </button>
  </form>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleCategoryScoring(cat) {
  const cb = document.getElementById(`${cat}-enable-cb`);
  const inp = document.getElementById(`calc-${cat}`);
  if (inp) {
    inp.disabled = !cb.checked;
    inp.style.opacity = cb.checked ? '1' : '0.3';
  }
  updateDivisor();
}

function updateDivisor() {
  let count = 0;
  if (document.getElementById('twk-enable-cb').checked) count++;
  if (document.getElementById('tiu-enable-cb').checked) count++;
  if (document.getElementById('tkp-enable-cb').checked) count++;

  document.getElementById('formula-divisor-text').innerText = count > 0 ? count : 1;
  
  // Trigger update preview
  const twk = parseFloat(document.getElementById('calc-twk').value) || 0;
  const tiu = parseFloat(document.getElementById('calc-tiu').value) || 0;
  const tkp = parseFloat(document.getElementById('calc-tkp').value) || 0;
  
  let sum = 0;
  if (document.getElementById('twk-enable-cb').checked) sum += twk;
  if (document.getElementById('tiu-enable-cb').checked) sum += tiu;
  if (document.getElementById('tkp-enable-cb').checked) sum += tkp;

  const res = count > 0 ? (sum / count).toFixed(2) : '0.00';
  document.getElementById('calc-final-result').innerText = res;
}
</script>
<?= $this->endSection() ?>
