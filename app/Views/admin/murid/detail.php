<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button -->
  <div class="page-header-nav">
    <a href="<?= base_url('admin/murid/database') ?>" class="back-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
      <h1 class="page-title">Detail Murid</h1>
      <p class="page-subtitle"><?= esc($student['name']) ?> (@<?= esc($student['username']) ?>)</p>
    </div>
  </div>

  <!-- Profile Header Card -->
  <div class="card" style="padding: 18px; gap: 8px;">
    <div style="display: flex; align-items: center; justify-content: space-between;">
      <div style="display: flex; align-items: center; gap: 12px;">
        <div style="width: 46px; height: 46px; border-radius: 50%; background: var(--dark-navy); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 700;">
          <?= strtoupper(substr($student['name'], 0, 1)) ?>
        </div>
        <div>
          <h2 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 700; color: var(--dark-navy);">
            <?= esc($student['name']) ?>
          </h2>
          <span style="font-size: 12px; color: var(--text-muted);">
            @<?= esc($student['username']) ?> • WA: <?= esc($student['phone_whatsapp'] ?? '-') ?>
          </span>
        </div>
      </div>

      <?php if ($student['is_premium']): ?>
        <span class="badge badge-amber">Premium</span>
      <?php else: ?>
        <span class="badge badge-navy">Free</span>
      <?php endif; ?>
    </div>
  </div>

  <!-- Navigation Tabs: Profil | Nilai | Pengerjaan | Absensi | Premium | Login -->
  <div style="display: flex; gap: 6px; overflow-x: auto; padding-bottom: 4px;">
    <button type="button" class="day-pill active" onclick="switchDetailTab('profil', this)" style="font-size: 12px; padding: 6px 14px;">Profil</button>
    <button type="button" class="day-pill" onclick="switchDetailTab('nilai', this)" style="font-size: 12px; padding: 6px 14px;">Nilai</button>
    <button type="button" class="day-pill" onclick="switchDetailTab('pengerjaan', this)" style="font-size: 12px; padding: 6px 14px;">Pengerjaan</button>
    <button type="button" class="day-pill" onclick="switchDetailTab('absensi', this)" style="font-size: 12px; padding: 6px 14px;">Absensi</button>
    <button type="button" class="day-pill" onclick="switchDetailTab('premium', this)" style="font-size: 12px; padding: 6px 14px;">Premium</button>
    <button type="button" class="day-pill" onclick="switchDetailTab('login', this)" style="font-size: 12px; padding: 6px 14px;">Login</button>
  </div>

  <!-- TAB CONTENT 1: Profil -->
  <div id="tab-profil" class="detail-tab-content">
    <div class="card" style="padding: 18px; gap: 12px;">
      <h3 style="font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 700; color: var(--dark-navy);">Data Akun Dasar</h3>
      <div style="display: flex; flex-direction: column; gap: 8px; font-size: 13px;">
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 6px;">
          <span style="color: var(--text-muted);">Nama Lengkap</span>
          <strong><?= esc($student['name']) ?></strong>
        </div>
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 6px;">
          <span style="color: var(--text-muted);">Username</span>
          <strong>@<?= esc($student['username']) ?></strong>
        </div>
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 6px;">
          <span style="color: var(--text-muted);">No. WhatsApp</span>
          <strong><?= esc($student['phone_whatsapp'] ?? '-') ?></strong>
        </div>
        <div style="display: flex; justify-content: space-between;">
          <span style="color: var(--text-muted);">Terdaftar Sejak</span>
          <strong><?= date('d M Y', strtotime($student['created_at'] ?? 'now')) ?></strong>
        </div>
      </div>
    </div>
  </div>

  <!-- TAB CONTENT 2: Nilai -->
  <div id="tab-nilai" class="detail-tab-content" style="display: none;">
    <div class="card" style="padding: 18px; gap: 12px;">
      <h3 style="font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 700; color: var(--dark-navy);">Statistik Nilai Siswa</h3>
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;">
        <div class="stat-box" style="padding: 10px;">
          <span class="stat-label">Terakhir</span>
          <span class="stat-value" style="font-size: 18px;"><?= $latestScore ? number_format($latestScore, 1) : '-' ?></span>
        </div>
        <div class="stat-box" style="padding: 10px;">
          <span class="stat-label">Tertinggi</span>
          <span class="stat-value" style="font-size: 18px; color: var(--soft-sage-green);"><?= $highestScore ? number_format($highestScore, 1) : '-' ?></span>
        </div>
        <div class="stat-box" style="padding: 10px;">
          <span class="stat-label">Rata-rata</span>
          <span class="stat-value" style="font-size: 18px; color: var(--warm-amber);"><?= $avgScore ? number_format($avgScore, 1) : '-' ?></span>
        </div>
      </div>
    </div>
  </div>

  <!-- TAB CONTENT 3: Pengerjaan -->
  <div id="tab-pengerjaan" class="detail-tab-content" style="display: none;">
    <div class="card" style="padding: 18px; gap: 10px;">
      <h3 style="font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 700; color: var(--dark-navy);">Riwayat Pengerjaan Try Out</h3>
      <?php if (!empty($tryoutHistory)): ?>
        <?php foreach ($tryoutHistory as $th): ?>
          <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border-light); font-size: 12px;">
            <div>
              <strong style="color: var(--dark-navy); display: block;"><?= esc($th['package_title']) ?></strong>
              <span style="color: var(--text-muted);"><?= date('d M Y H:i', strtotime($th['created_at'])) ?></span>
            </div>
            <span style="font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 800; color: var(--dark-navy);">
              <?= number_format($th['final_score'], 1) ?>
            </span>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="font-size: 12px; color: var(--text-muted); text-align: center; padding: 12px 0;">Belum ada pengerjaan try out.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- TAB CONTENT 4: Absensi -->
  <div id="tab-absensi" class="detail-tab-content" style="display: none;">
    <div class="card" style="padding: 18px; gap: 12px;">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 700; color: var(--dark-navy);">Keaktifan Belajar</h3>
        <span class="badge badge-sage"><?= $student['attendance_rate'] ?? 92 ?>% Hadir</span>
      </div>
      <div style="display: flex; flex-direction: column; gap: 6px;">
        <?php if (!empty($attendances)): ?>
          <?php foreach ($attendances as $att): ?>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 6px 8px; background: #F8FAFC; border-radius: var(--radius-sm); font-size: 12px;">
              <span><?= esc($att['subject']) ?> • <?= date('d M Y', strtotime($att['date'])) ?></span>
              <span class="badge <?= $att['status'] === 'hadir' ? 'badge-sage' : 'badge-peach' ?>" style="font-size: 10px;">
                <?= ucfirst($att['status']) ?>
              </span>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="font-size: 12px; color: var(--text-muted); text-align: center;">Belum ada catatan absensi.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- TAB CONTENT 5: Premium -->
  <div id="tab-premium" class="detail-tab-content" style="display: none;">
    <div class="card" style="padding: 18px; gap: 12px; border-color: <?= $student['is_premium'] ? '#FDE68A' : 'var(--border-color)' ?>;">
      <h3 style="font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 700; color: var(--dark-navy);">Status Langganan</h3>
      <div style="display: flex; flex-direction: column; gap: 8px; font-size: 13px;">
        <div style="display: flex; justify-content: space-between;">
          <span style="color: var(--text-muted);">Status Akses:</span>
          <strong><?= $student['is_premium'] ? 'Aktif Premium' : 'Free (Belum Berlangganan)' ?></strong>
        </div>
        <?php if ($student['is_premium']): ?>
          <div style="display: flex; justify-content: space-between;">
            <span style="color: var(--text-muted);">Mulai Aktif:</span>
            <strong><?= date('d M Y', strtotime($student['premium_start'])) ?></strong>
          </div>
          <div style="display: flex; justify-content: space-between;">
            <span style="color: var(--text-muted);">Berakhir Pada:</span>
            <strong style="color: var(--warm-amber);"><?= date('d M Y', strtotime($student['premium_expiry'])) ?></strong>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- TAB CONTENT 6: Login Management -->
  <div id="tab-login" class="detail-tab-content" style="display: none;">
    <div class="card" style="padding: 18px; gap: 14px;">
      <h3 style="font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 700; color: var(--dark-navy);">Kelola Login Siswa</h3>
      
      <!-- Ubah Username Form -->
      <form action="<?= base_url("admin/murid/updateStudentUsername/{$student['id']}") ?>" method="post" style="display: flex; flex-direction: column; gap: 8px;">
        <?= csrf_field() ?>
        <label class="form-label" style="font-size: 12px;">Ubah Username Siswa</label>
        <div style="display: flex; gap: 6px;">
          <input type="text" name="new_username" class="form-control" style="height: 40px; font-size: 13px;" value="<?= esc($student['username']) ?>" required>
          <button type="submit" class="btn btn-secondary btn-sm" style="height: 40px; width: 80px;">Simpan</button>
        </div>
      </form>

      <!-- Reset Password Form -->
      <form action="<?= base_url("admin/murid/resetStudentPassword/{$student['id']}") ?>" method="post" style="display: flex; flex-direction: column; gap: 8px; border-top: 1px solid var(--border-light); padding-top: 12px;">
        <?= csrf_field() ?>
        <label class="form-label" style="font-size: 12px;">Reset Password Baru</label>
        <div style="display: flex; gap: 6px;">
          <input type="password" name="new_password" class="form-control" style="height: 40px; font-size: 13px;" placeholder="Password baru" required>
          <button type="submit" class="btn btn-primary btn-sm" style="height: 40px; width: 80px;">Reset</button>
        </div>
        <span style="font-size: 11px; color: var(--text-muted);">
          🔒 Perubahan akun dicatat untuk keamanan sistem. Password tidak akan ditampilkan secara bebas setelah disimpan.
        </span>
      </form>
    </div>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function switchDetailTab(tabId, btn) {
  document.querySelectorAll('.detail-tab-content').forEach(el => el.style.display = 'none');
  document.querySelectorAll('.day-pill').forEach(el => el.classList.remove('active'));
  document.getElementById(`tab-${tabId}`).style.display = 'block';
  btn.classList.add('active');
}
</script>
<?= $this->endSection() ?>
