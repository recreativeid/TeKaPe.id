<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-4 d-flex align-items-center gap-3">
    <a href="<?= base_url('murid/profil') ?>" class="btn btn-sm btn-white text-navy" style="width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #E2E8F0; text-decoration: none;">
        <i class="bi bi-arrow-left fs-5"></i>
    </a>
    <div>
        <h2 class="mb-0 fs-5 fw-bold text-navy">Ubah Username</h2>
        <p class="mb-0 text-muted" style="font-size: 0.8rem;">Kelola nama unik untuk masuk ke akun Anda</p>
    </div>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="p-3 mb-3 border-0 alert alert-danger" style="border-radius: 12px; background: #FEE2E2; color: #991B1B; font-size: 0.85rem;">
        <i class="bi bi-exclamation-circle-fill me-2"></i><?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<!-- Card 1: Username Saat Ini -->
<div class="p-3 mb-3 border card border-slate-100" style="background: #FFFFFF; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
    <label class="form-label text-muted fw-semibold" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Username Aktif Saat Ini</label>
    <div class="p-2 px-3 d-flex align-items-center justify-content-between" style="background: #F8FAFC; border-radius: 10px; border: 1px dashed #CBD5E1;">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-person-badge text-navy"></i>
            <span class="fw-bold text-navy" style="font-size: 0.95rem;">@<?= esc($student['username']) ?></span>
        </div>
        <span class="badge" style="background: #E2E8F0; color: #475569; font-size: 0.7rem; font-weight: 600;">Aktif</span>
    </div>
</div>

<!-- Card 2: Form Ubah Username -->
<div class="p-4 border card border-slate-100" style="background: #FFFFFF; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.04);">
    <form action="<?= base_url('murid/profil/username') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label text-navy fw-bold" style="font-size: 0.85rem;">Username Baru</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border-color: #E2E8F0;">@</span>
                <input type="text" name="new_username" class="form-control border-start-0" placeholder="contoh: budi_pratama" required style="border-radius: 0 12px 12px 0; border-color: #E2E8F0; height: 48px; font-size: 0.9rem;" value="<?= old('new_username') ?>">
            </div>
            <div class="mt-1 d-flex align-items-center gap-1 text-muted" style="font-size: 0.75rem;">
                <i class="bi bi-info-circle"></i>
                <span>Gunakan minimal 4 karakter, huruf kecil, angka, atau garis bawah</span>
            </div>
        </div>

        <div class="p-3 mb-4 d-flex align-items-start gap-2" style="background: #FEF3C7; border-radius: 12px; border: 1px solid #FDE68A;">
            <i class="bi bi-shield-lock-fill text-amber mt-0.5" style="color: #D97706;"></i>
            <div style="font-size: 0.78rem; color: #92400E; line-height: 1.4;">
                <strong>Perhatian:</strong> Setelah username diubah, gunakan username baru ini saat melakukan login kembali di masa mendatang.
            </div>
        </div>

        <button type="submit" class="btn btn-navy w-100 fw-bold d-flex align-items-center justify-content-center gap-2" style="height: 50px; border-radius: 14px; font-size: 0.95rem;">
            <i class="bi bi-check2-circle fs-5"></i> Simpan Username Baru
        </button>
    </form>
</div>
<?= $this->endSection() ?>
