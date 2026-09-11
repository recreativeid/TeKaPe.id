<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-4 d-flex align-items-center gap-3">
    <a href="<?= base_url('murid/profil') ?>" class="btn btn-sm btn-white text-navy" style="width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #E2E8F0; text-decoration: none;">
        <i class="bi bi-arrow-left fs-5"></i>
    </a>
    <div>
        <h2 class="mb-0 fs-5 fw-bold text-navy">Ubah Password</h2>
        <p class="mb-0 text-muted" style="font-size: 0.8rem;">Jaga keamanan akun latihan Anda</p>
    </div>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="p-3 mb-3 border-0 alert alert-danger" style="border-radius: 12px; background: #FEE2E2; color: #991B1B; font-size: 0.85rem;">
        <i class="bi bi-exclamation-circle-fill me-2"></i><?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="p-4 border card border-slate-100" style="background: #FFFFFF; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.04);">
    <form action="<?= base_url('murid/profil/password') ?>" method="POST" id="passwordForm">
        <?= csrf_field() ?>

        <!-- Password Lama -->
        <div class="mb-3">
            <label class="form-label text-navy fw-bold" style="font-size: 0.85rem;">Password Lama</label>
            <div class="input-group">
                <input type="password" name="old_password" id="old_password" class="form-control border-end-0" placeholder="Masukkan password saat ini" required style="border-radius: 12px 0 0 12px; border-color: #E2E8F0; height: 48px; font-size: 0.9rem;">
                <button type="button" class="btn btn-light border-start-0 text-muted" style="border-radius: 0 12px 12px 0; border-color: #E2E8F0;" onclick="togglePass('old_password', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <!-- Password Baru -->
        <div class="mb-2">
            <label class="form-label text-navy fw-bold" style="font-size: 0.85rem;">Password Baru</label>
            <div class="input-group">
                <input type="password" name="new_password" id="new_password" class="form-control border-end-0" placeholder="Minimal 6 karakter" required style="border-radius: 12px 0 0 12px; border-color: #E2E8F0; height: 48px; font-size: 0.9rem;" oninput="checkStrength(this.value)">
                <button type="button" class="btn btn-light border-start-0 text-muted" style="border-radius: 0 12px 12px 0; border-color: #E2E8F0;" onclick="togglePass('new_password', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <!-- Strength Indicator Bar -->
        <div class="mb-3">
            <div class="mb-1 d-flex justify-content-between align-items-center" style="font-size: 0.72rem;">
                <span class="text-muted">Kekuatan Sandi:</span>
                <span id="strengthText" class="fw-bold text-muted">-</span>
            </div>
            <div class="progress" style="height: 5px; border-radius: 6px; background-color: #E2E8F0;">
                <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%; border-radius: 6px;"></div>
            </div>
        </div>

        <!-- Konfirmasi Password Baru -->
        <div class="mb-4">
            <label class="form-label text-navy fw-bold" style="font-size: 0.85rem;">Konfirmasi Password Baru</label>
            <div class="input-group">
                <input type="password" name="confirm_password" id="confirm_password" class="form-control border-end-0" placeholder="Ulangi password baru" required style="border-radius: 12px 0 0 12px; border-color: #E2E8F0; height: 48px; font-size: 0.9rem;">
                <button type="button" class="btn btn-light border-start-0 text-muted" style="border-radius: 0 12px 12px 0; border-color: #E2E8F0;" onclick="togglePass('confirm_password', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <!-- Checklist requirements -->
        <div class="p-3 mb-4" style="background: #F8FAFC; border-radius: 12px; border: 1px solid #E2E8F0;">
            <div class="mb-2 fw-semibold text-navy" style="font-size: 0.75rem;">Syarat Keamanan Password:</div>
            <div class="gap-1 d-flex flex-column" style="font-size: 0.75rem;">
                <div class="d-flex align-items-center gap-2" id="reqLength">
                    <i class="bi bi-circle text-muted" id="iconLength"></i>
                    <span class="text-muted" id="textLength">Minimal 6 karakter</span>
                </div>
                <div class="d-flex align-items-center gap-2" id="reqAlnum">
                    <i class="bi bi-circle text-muted" id="iconAlnum"></i>
                    <span class="text-muted" id="textAlnum">Mengandung huruf dan angka</span>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-navy w-100 fw-bold d-flex align-items-center justify-content-center gap-2" style="height: 50px; border-radius: 14px; font-size: 0.95rem;">
            <i class="bi bi-shield-check fs-5"></i> Simpan Password Baru
        </button>
    </form>
</div>

<script>
function togglePass(id, btn) {
    const el = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (el.type === 'password') {
        el.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        el.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

function checkStrength(val) {
    const bar = document.getElementById('strengthBar');
    const text = document.getElementById('strengthText');
    const iconLen = document.getElementById('iconLength');
    const iconAlnum = document.getElementById('iconAlnum');
    const textLen = document.getElementById('textLength');
    const textAlnum = document.getElementById('textAlnum');

    let score = 0;
    const hasLen = val.length >= 6;
    const hasLetter = /[a-zA-Z]/.test(val);
    const hasNum = /[0-9]/.test(val);
    const hasSpecial = /[^a-zA-Z0-9]/.test(val);

    // Checklist length
    if (hasLen) {
        iconLen.className = 'bi bi-check-circle-fill text-success';
        textLen.className = 'text-success fw-medium';
        score += 35;
    } else {
        iconLen.className = 'bi bi-circle text-muted';
        textLen.className = 'text-muted';
    }

    // Checklist alnum
    if (hasLetter && hasNum) {
        iconAlnum.className = 'bi bi-check-circle-fill text-success';
        textAlnum.className = 'text-success fw-medium';
        score += 35;
    } else {
        iconAlnum.className = 'bi bi-circle text-muted';
        textAlnum.className = 'text-muted';
    }

    if (val.length >= 8 && hasSpecial) {
        score += 30;
    }

    if (val.length === 0) {
        bar.style.width = '0%';
        text.innerText = '-';
        text.className = 'fw-bold text-muted';
    } else if (score < 40) {
        bar.style.width = '30%';
        bar.style.backgroundColor = '#EF4444';
        text.innerText = 'Lemah';
        text.className = 'fw-bold text-danger';
    } else if (score < 70) {
        bar.style.width = '65%';
        bar.style.backgroundColor = '#F59E0B';
        text.innerText = 'Sedang';
        text.className = 'fw-bold text-warning';
    } else {
        bar.style.width = '100%';
        bar.style.backgroundColor = '#10B981';
        text.innerText = 'Kuat';
        text.className = 'fw-bold text-success';
    }
}
</script>
<?= $this->endSection() ?>
