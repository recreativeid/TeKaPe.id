<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header -->
  <div class="page-header">
    <h1 class="page-title">Profil & Pengaturan</h1>
    <p class="page-subtitle">Kelola akun dan akses belajar Anda</p>
  </div>

  <!-- Profile Card -->
  <div class="card" style="padding: 18px; flex-direction: row; align-items: center; gap: 14px;">
    <div style="width: 52px; height: 52px; border-radius: 50%; background: var(--dark-navy); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800;">
      <?= strtoupper(substr($student['name'], 0, 1)) ?>
    </div>

    <div style="display: flex; flex-direction: column; gap: 2px; flex: 1;">
      <h2 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 700; color: var(--dark-navy);">
        <?= esc($student['name']) ?>
      </h2>
      <span style="font-size: 12px; color: var(--text-muted);">
        @<?= esc($student['username']) ?>
      </span>
      <div style="margin-top: 4px;">
        <?php if ($student['is_premium']): ?>
          <span class="badge badge-amber" style="font-size: 10px;">Member Premium</span>
        <?php else: ?>
          <span class="badge badge-navy" style="font-size: 10px;">Akun Free</span>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Four Separate Settings Cards (Prompt 33) -->
  <div style="display: flex; flex-direction: column; gap: 12px;">

    <!-- CARD 1: Akun -->
    <div class="card" style="padding: 16px; gap: 10px;">
      <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.6px;">
        Akun
      </span>
      <div style="display: flex; flex-direction: column; gap: 6px;">
        <a href="<?= base_url('murid/profil/username') ?>" class="action-card" style="padding: 12px 14px; box-shadow: none; border-color: var(--border-light);">
          <span style="font-size: 13px; font-weight: 600;">Ubah Username</span>
          <span style="color: var(--warm-amber);">&rarr;</span>
        </a>
        <a href="<?= base_url('murid/profil/password') ?>" class="action-card" style="padding: 12px 14px; box-shadow: none; border-color: var(--border-light);">
          <span style="font-size: 13px; font-weight: 600;">Ubah Password</span>
          <span style="color: var(--warm-amber);">&rarr;</span>
        </a>
      </div>
    </div>

    <!-- CARD 2: Premium -->
    <div class="card" style="padding: 16px; gap: 8px;">
      <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.6px;">
        Langganan Premium
      </span>
      <div style="display: flex; justify-content: space-between; font-size: 13px;">
        <span style="color: var(--text-muted);">Status:</span>
        <strong style="color: <?= $student['is_premium'] ? 'var(--soft-sage-green)' : 'var(--text-muted)' ?>;">
          <?= $student['is_premium'] ? 'Aktif' : 'Free (Belum Aktif)' ?>
        </strong>
      </div>
      <?php if ($student['is_premium']): ?>
        <div style="display: flex; justify-content: space-between; font-size: 13px;">
          <span style="color: var(--text-muted);">Masa Berlaku:</span>
          <strong><?= date('d M Y', strtotime($student['premium_expiry'])) ?></strong>
        </div>
      <?php else: ?>
        <a href="<?= base_url('murid/payment') ?>" class="btn btn-amber btn-sm" style="margin-top: 6px; font-weight: 700;">
          Aktifkan Premium &rarr;
        </a>
      <?php endif; ?>
    </div>

    <!-- CARD 3: Bantuan (Hubungi Tentor) -->
    <a href="<?= base_url('murid/profil/bantuan') ?>" class="action-card">
      <div class="action-card-body">
        <h3 class="action-card-title">Bantuan & Hubungi Tentor</h3>
        <p class="action-card-desc">Konsultasi materi, pertanyaan try out, atau kendala akun.</p>
      </div>
      <span class="action-card-arrow">&rarr;</span>
    </a>

    <!-- CARD 4: Keamanan (Logout) -->
    <a href="<?= base_url('auth/logout') ?>" class="action-card" style="border-color: var(--soft-peach-border);">
      <div class="action-card-body">
        <h3 class="action-card-title" style="color: #DC2626;">Keluar (Logout)</h3>
        <p class="action-card-desc">Keluar dari sesi akun Anda saat ini.</p>
      </div>
      <span style="color: #DC2626; font-size: 18px;">&rarr;</span>
    </a>

  </div>

</div>
<?= $this->endSection() ?>
