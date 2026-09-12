<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-direction: column; gap: 20px;">

  <!-- Header with Back Button & Action Button -->
  <div class="page-header-nav" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
    <div style="display: flex; align-items: center; gap: 12px;">
      <a href="<?= base_url('admin/murid') ?>" class="back-btn" title="Kembali ke Data Murid">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
      </a>
      <div>
        <h1 class="page-title" style="margin: 0; font-size: 22px;">Kelola Akun Siswa</h1>
        <p class="page-subtitle" style="margin: 2px 0 0;">Buat akun siswa baru, ubah username, reset password yang lupa, dan salin rincian login untuk WhatsApp</p>
      </div>
    </div>

    <!-- Top Action: Buat Akun Baru -->
    <button type="button" class="btn btn-primary" onclick="openTambahAkunModal()" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700; padding: 10px 18px; box-shadow: 0 4px 12px rgba(30, 34, 56, 0.15);">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
      + Buat Akun Siswa Baru
    </button>
  </div>

  <!-- Flash Notification: Akun Baru Berhasil Dibuat -->
  <?php if ($newStudent = session()->getFlashdata('new_student_created')): ?>
    <div class="card" style="border: 2px solid #10B981; background: linear-gradient(135deg, #ECFDF5 0%, #FFFFFF 100%); padding: 18px; border-radius: 12px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.12);">
      <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 14px; flex-wrap: wrap;">
        <div style="display: flex; gap: 12px;">
          <div style="width: 42px; height: 42px; border-radius: 10px; background: #10B981; color: white; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
            ✓
          </div>
          <div>
            <h3 style="font-size: 16px; font-weight: 700; color: #065F46; margin: 0 0 4px;">Akun Siswa Berhasil Dibuat!</h3>
            <p style="font-size: 13px; color: #047857; margin: 0 0 8px;">
              Akun untuk <strong><?= esc($newStudent['name']) ?></strong> telah aktif. Rincian login siswa:
            </p>
            <div style="display: flex; flex-wrap: wrap; gap: 12px; background: #FFFFFF; padding: 8px 14px; border-radius: 8px; border: 1px solid #A7F3D0; font-size: 13px;">
              <span>Username: <strong style="font-family: monospace; color: #1E2238;">@<?= esc($newStudent['username']) ?></strong></span>
              <span style="color: #CBD5E1;">|</span>
              <span>Password: <strong style="font-family: monospace; color: #D97706;"><?= esc($newStudent['password']) ?></strong></span>
              <?php if (!empty($newStudent['phone'])): ?>
                <span style="color: #CBD5E1;">|</span>
                <span>WA: <strong><?= esc($newStudent['phone']) ?></strong></span>
              <?php endif; ?>
              <span style="color: #CBD5E1;">|</span>
              <span>Status: <strong style="color: <?= $newStudent['is_premium'] ? '#059669' : '#475569' ?>;"><?= $newStudent['is_premium'] ? 'Premium 30 Hari' : 'Free' ?></strong></span>
            </div>
          </div>
        </div>

        <!-- Copy & WA buttons -->
        <div style="display: flex; gap: 8px; align-items: center; align-self: center;">
          <button type="button" class="btn btn-sm btn-primary" onclick="copyWaText('<?= esc(addslashes($newStudent['name'])) ?>', '<?= esc(addslashes($newStudent['username'])) ?>', '<?= esc(addslashes($newStudent['password'])) ?>', '<?= $newStudent['is_premium'] ? 'Paket Premium' : 'Paket Free' ?>', '<?= esc($newStudent['phone'] ?? '') ?>')" style="background: #059669; border-color: #059669; font-weight: 600;">
            📋 Salin Pesan WA
          </button>
          <?php if (!empty($newStudent['phone'])): ?>
            <a href="https://wa.me/<?= esc($newStudent['phone']) ?>?text=<?= urlencode("Halo {$newStudent['name']},\n\nBerikut adalah rincian akun Anda untuk masuk ke platform TeKaPe.id:\n\n🌐 Website: " . base_url('login') . "\n👤 Username: @{$newStudent['username']}\n🔑 Password: {$newStudent['password']}\n📦 Status: " . ($newStudent['is_premium'] ? 'Paket Premium (30 Hari)' : 'Paket Free') . "\n\nSilakan segera login dan mulai belajar. Sukses selalu untuk persiapan kedinasan Anda!") ?>" target="_blank" class="btn btn-sm btn-secondary" style="background: #25D366; color: white; border-color: #25D366; font-weight: 600;">
              💬 Buka WhatsApp
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Flash Notification: Password Reset Success -->
  <?php if ($resetInfo = session()->getFlashdata('password_reset_success')): ?>
    <div class="card" style="border: 2px solid #F59E0B; background: linear-gradient(135deg, #FEF3C7 0%, #FFFFFF 100%); padding: 18px; border-radius: 12px;">
      <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
        <div>
          <h3 style="font-size: 15px; font-weight: 700; color: #92400E; margin: 0 0 4px;">🔑 Password Berhasil Direset!</h3>
          <p style="font-size: 13px; color: #B45309; margin: 0;">
            Siswa: <strong><?= esc($resetInfo['name']) ?> (@<?= esc($resetInfo['username']) ?>)</strong> | Password Baru Sementara: <strong style="font-family: monospace; font-size: 14px; background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #FDE68A;"><?= esc($resetInfo['password']) ?></strong>
          </p>
        </div>
        <div style="display: flex; gap: 8px;">
          <button type="button" class="btn btn-sm btn-primary" onclick="copyWaText('<?= esc(addslashes($resetInfo['name'])) ?>', '<?= esc(addslashes($resetInfo['username'])) ?>', '<?= esc(addslashes($resetInfo['password'])) ?>', 'Akun Aktif', '<?= esc($resetInfo['phone'] ?? '') ?>')">
            📋 Salin Pesan WA
          </button>
          <?php if (!empty($resetInfo['phone'])): ?>
            <a href="https://wa.me/<?= esc($resetInfo['phone']) ?>?text=<?= urlencode("Halo {$resetInfo['name']},\n\nPassword akun TeKaPe.id Anda telah berhasil direset oleh Admin:\n\n🌐 Website: " . base_url('login') . "\n👤 Username: @{$resetInfo['username']}\n🔑 Password Baru: {$resetInfo['password']}\n\nSilakan segera login dan ganti password Anda di menu Profil.") ?>" target="_blank" class="btn btn-sm btn-secondary" style="background: #25D366; color: white; border-color: #25D366; font-weight: 600;">
              💬 Buka WA
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Flash Notification: Username Changed Success -->
  <?php if ($userChanged = session()->getFlashdata('username_changed_success')): ?>
    <div class="card" style="border: 2px solid #6366F1; background: linear-gradient(135deg, #EEF2FF 0%, #FFFFFF 100%); padding: 18px; border-radius: 12px;">
      <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
        <div>
          <h3 style="font-size: 15px; font-weight: 700; color: #3730A3; margin: 0 0 4px;">✏️ Username Berhasil Diperbarui!</h3>
          <p style="font-size: 13px; color: #4338CA; margin: 0;">
            Siswa <strong><?= esc($userChanged['name']) ?></strong> sekarang menggunakan username: <strong style="font-family: monospace; font-size: 14px; background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #C7D2FE;">@<?= esc($userChanged['new_username']) ?></strong> (Sebelumnya: @<?= esc($userChanged['old_username']) ?>)
          </p>
        </div>
        <div>
          <button type="button" class="btn btn-sm btn-primary" onclick="copyWaUsernameNotice('<?= esc(addslashes($userChanged['name'])) ?>', '<?= esc(addslashes($userChanged['new_username'])) ?>', '<?= esc($userChanged['phone'] ?? '') ?>')">
            📋 Salin Info WA
          </button>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Search & Filter Card -->
  <div class="card" style="padding: 16px;">
    <form action="<?= base_url('admin/murid/akunSiswa') ?>" method="get" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
      <!-- Search Input -->
      <div style="flex: 1; min-width: 220px; position: relative;">
        <input type="text" name="q" class="form-control" placeholder="Cari nama, @username, atau WhatsApp..." value="<?= esc($search ?? '') ?>" style="height: 42px; font-size: 13px; padding-left: 36px;">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="position: absolute; left: 11px; top: 12px; color: var(--text-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
      </div>

      <!-- Filter Status -->
      <div style="min-width: 150px;">
        <select name="status" class="form-control" style="height: 42px; font-size: 13px; padding: 8px 12px;">
          <option value="semua" <?= ($status ?? '') === 'semua' ? 'selected' : '' ?>>Semua Status</option>
          <option value="free" <?= ($status ?? '') === 'free' ? 'selected' : '' ?>>Paket Free</option>
          <option value="premium" <?= ($status ?? '') === 'premium' ? 'selected' : '' ?>>Paket Premium</option>
        </select>
      </div>

      <!-- Filter Tentor Pembimbing -->
      <div style="min-width: 190px;">
        <select name="tentor_id" class="form-control filter-guru-select">
          <option value="">-- Semua Guru / Tentor --</option>
          <?php if (!empty($tentors)): ?>
            <?php foreach ($tentors as $t): ?>
              <option value="<?= $t['id'] ?>" <?= ($selectedTentorId ?? null) == $t['id'] ? 'selected' : '' ?>>
                <?= esc($t['name']) ?>
              </option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>

      <!-- Submit & Reset -->
      <div style="display: flex; gap: 6px;">
        <button type="submit" class="btn btn-primary" style="height: 42px; padding: 0 18px; font-size: 13px; font-weight: 600;">
          Filter
        </button>
        <?php if (!empty($search) || !empty($status && $status !== 'semua') || !empty($selectedTentorId)): ?>
          <a href="<?= base_url('admin/murid/akunSiswa') ?>" class="btn btn-secondary" style="height: 42px; padding: 0 14px; font-size: 13px; display: inline-flex; align-items: center;" title="Reset Filter">
            Reset
          </a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <!-- Student Accounts Grid / List -->
  <div style="display: flex; flex-direction: column; gap: 12px;">
    <?php if (!empty($students)): ?>
      <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 4px;">
        <span style="font-size: 13px; color: var(--text-muted);">
          Menampilkan <strong><?= count($students) ?></strong> akun siswa terdaftar
        </span>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 14px;">
        <?php foreach ($students as $st): ?>
          <?php 
            $isPrem = !empty($st['is_premium']) && $st['is_premium'] == 1;
            $rawPhone = $st['phone_whatsapp'] ?? '';
            $waPhone = preg_replace('/[^0-9]/', '', $rawPhone);
            if (substr($waPhone, 0, 1) === '0') {
                $waPhone = '62' . substr($waPhone, 1);
            }
          ?>
          <div class="card" style="padding: 16px; border-radius: 12px; border: 1px solid var(--border-light); background: #FFFFFF; display: flex; flex-direction: column; justify-content: space-between; gap: 14px; transition: transform 0.15s ease, box-shadow 0.15s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 18px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-sm)';">
            
            <!-- Top Row: Student Name & Status Badge -->
            <div>
              <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; margin-bottom: 8px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                  <div style="width: 40px; height: 40px; border-radius: 10px; background: <?= $isPrem ? '#ECFDF5' : '#F1F5F9' ?>; color: <?= $isPrem ? '#065F46' : '#475569' ?>; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px; border: 1px solid <?= $isPrem ? '#A7F3D0' : '#E2E8F0' ?>;">
                    <?= strtoupper(substr($st['name'], 0, 2)) ?>
                  </div>
                  <div>
                    <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700; color: var(--dark-navy); margin: 0; line-height: 1.2;">
                      <?= esc($st['name']) ?>
                    </h3>
                    <span style="font-size: 11px; color: var(--text-muted);">
                      ID: #<?= $st['id'] ?> &bull; <?= !empty($st['created_at']) ? date('d M Y', strtotime($st['created_at'])) : 'Murid' ?>
                    </span>
                  </div>
                </div>

                <?php if ($isPrem): ?>
                  <span class="badge badge-sage" style="font-size: 10px; font-weight: 700; padding: 4px 8px; border-radius: 6px;">
                    ⭐ Premium
                  </span>
                <?php else: ?>
                  <span class="badge badge-amber" style="font-size: 10px; font-weight: 600; padding: 4px 8px; border-radius: 6px;">
                    Free
                  </span>
                <?php endif; ?>
              </div>

              <!-- Credential Box: Username & WhatsApp -->
              <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 10px 12px; display: flex; flex-direction: column; gap: 6px; margin-top: 6px;">
                <!-- Clear Username Display -->
                <div style="display: flex; align-items: center; justify-content: space-between;">
                  <span style="font-size: 12px; color: var(--text-muted);">Username:</span>
                  <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="font-family: monospace; font-size: 13px; font-weight: 700; color: #1E2238; background: #FFFFFF; padding: 2px 8px; border-radius: 6px; border: 1px solid #CBD5E1;">
                      @<?= esc($st['username']) ?>
                    </span>
                    <button type="button" onclick="navigator.clipboard.writeText('<?= esc($st['username']) ?>'); showToast('Username tersalin!');" title="Salin Username" style="background: none; border: none; cursor: pointer; color: var(--text-muted); padding: 2px;">
                      <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </button>
                  </div>
                </div>

                <!-- WhatsApp -->
                <div style="display: flex; align-items: center; justify-content: space-between;">
                  <span style="font-size: 12px; color: var(--text-muted);">WhatsApp:</span>
                  <?php if (!empty($waPhone)): ?>
                    <a href="https://wa.me/<?= esc($waPhone) ?>" target="_blank" style="font-size: 12px; font-weight: 600; color: #059669; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                      <span>💬</span> <?= esc($st['phone_whatsapp']) ?>
                    </a>
                  <?php else: ?>
                    <span style="font-size: 11px; color: #94A3B8; font-style: italic;">Belum tersimpan</span>
                  <?php endif; ?>
                </div>

                <!-- Tentor Pembimbing -->
                <div style="display: flex; align-items: center; justify-content: space-between;">
                  <span style="font-size: 12px; color: var(--text-muted);">Tentor:</span>
                  <span style="font-size: 12px; font-weight: 600; color: #1E2238;">
                    <?= !empty($st['assigned_tentor_name']) ? esc($st['assigned_tentor_name']) : '<em style="color:#94A3B8; font-weight:normal;">Umum / Bebas</em>' ?>
                  </span>
                </div>
              </div>
            </div>

            <!-- Action Buttons: 3 Terpisah Sesuai Permintaan -->
            <div style="border-top: 1px solid var(--border-light); padding-top: 10px; display: flex; flex-direction: column; gap: 8px;">
              <!-- Quick Copy WhatsApp Info Button -->
              <button type="button" class="btn btn-sm" onclick="copyWaText('<?= esc(addslashes($st['name'])) ?>', '<?= esc(addslashes($st['username'])) ?>', '', '<?= $isPrem ? 'Paket Premium' : 'Paket Free' ?>', '<?= esc($waPhone) ?>')" style="background: #F0FDF4; border: 1px solid #BBF7D0; color: #166534; font-weight: 600; font-size: 12px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                Salin Rincian Akun (WA)
              </button>

              <!-- Edit Username & Reset Password Buttons -->
              <div style="display: flex; gap: 6px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="openUbahUsernameModal(<?= $st['id'] ?>, '<?= esc($st['username']) ?>', '<?= esc(addslashes($st['name'])) ?>')" style="flex: 1; font-weight: 600; font-size: 12px; padding: 7px 8px;">
                  ✏️ Ubah Username
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="openResetPasswordModal(<?= $st['id'] ?>, '<?= esc(addslashes($st['name'])) ?>', '<?= esc($st['username']) ?>', '<?= esc($waPhone) ?>')" style="flex: 1; font-weight: 600; font-size: 12px; padding: 7px 8px; background: #1E2238;">
                  🔑 Reset Password
                </button>
              </div>
            </div>

          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="card" style="padding: 48px 24px; text-align: center;">
        <div style="font-size: 40px; margin-bottom: 12px;">🔍</div>
        <h3 style="font-size: 16px; font-weight: 700; color: var(--dark-navy); margin-bottom: 6px;">Tidak Ada Data Akun Siswa</h3>
        <p style="font-size: 13px; color: var(--text-muted); max-width: 400px; margin: 0 auto 16px;">
          Tidak ditemukan akun siswa dengan filter atau kata kunci tersebut. Coba reset filter atau buat akun siswa baru.
        </p>
        <button type="button" class="btn btn-primary" onclick="openTambahAkunModal()" style="display: inline-flex; align-items: center; gap: 6px;">
          + Buat Akun Siswa Baru
        </button>
      </div>
    <?php endif; ?>
  </div>

  <!-- ============================================================== -->
  <!-- MODAL 1: BUAT AKUN SISWA BARU                                 -->
  <!-- ============================================================== -->
  <div id="modal-tambah-akun" class="modal-backdrop">
    <div class="modal-dialog" style="max-width: 500px;">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; border-bottom: 1px solid var(--border-light); padding-bottom: 10px;">
        <div style="display: flex; align-items: center; gap: 8px;">
          <div style="width: 32px; height: 32px; border-radius: 8px; background: #EEF2FF; color: #4F46E5; display: flex; align-items: center; justify-content: center; font-size: 16px;">
            👤
          </div>
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 700; color: var(--dark-navy); margin: 0;">
            Buat Akun Siswa Baru
          </h3>
        </div>
        <button type="button" data-modal-close style="background: none; border: none; font-size: 22px; cursor: pointer; color: var(--text-muted);">&times;</button>
      </div>

      <form action="<?= base_url('admin/murid/createStudent') ?>" method="post" style="display: flex; flex-direction: column; gap: 14px;">
        <?= csrf_field() ?>

        <!-- Nama Siswa -->
        <div class="form-group">
          <label class="form-label" style="font-weight: 600; font-size: 13px;">Nama Lengkap Siswa <span style="color: red;">*</span></label>
          <input type="text" name="name" class="form-control" placeholder="Contoh: Muhammad Farhan" required style="height: 42px; font-size: 13px;">
        </div>

        <!-- Username & Phone in 2 Columns -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
          <div class="form-group">
            <label class="form-label" style="font-weight: 600; font-size: 13px;">Username <span style="color: red;">*</span></label>
            <input type="text" name="username" id="create-username" class="form-control" placeholder="farhan123" required style="height: 42px; font-size: 13px; font-family: monospace;">
            <small style="font-size: 11px; color: var(--text-muted);">Tanpa spasi, min 3 karakter</small>
          </div>

          <div class="form-group">
            <label class="form-label" style="font-weight: 600; font-size: 13px;">Nomor WhatsApp</label>
            <input type="text" name="phone_whatsapp" class="form-control" placeholder="081234567890" style="height: 42px; font-size: 13px;">
            <small style="font-size: 11px; color: var(--text-muted);">Untuk kirim rincian login</small>
          </div>
        </div>

        <!-- Password with Generator & Toggle -->
        <div class="form-group">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
            <label class="form-label" style="font-weight: 600; font-size: 13px; margin: 0;">Password Baru <span style="color: red;">*</span></label>
            <button type="button" onclick="generateRandomPassword('create-password')" style="background: none; border: none; color: #4F46E5; font-size: 11px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 3px;">
              🎲 Acak Password
            </button>
          </div>
          <div class="input-password-wrapper" style="position: relative;">
            <input type="password" name="password" id="create-password" class="form-control" placeholder="Minimal 6 karakter" required style="height: 42px; font-size: 13px; padding-right: 42px;">
            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('create-password', this)" title="Lihat/Sembunyikan" style="position: absolute; right: 10px; top: 11px; background: none; border: none; cursor: pointer; color: var(--text-muted);">
              <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </button>
          </div>
        </div>

        <!-- Status Paket & Tentor in 2 Columns -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
          <div class="form-group">
            <label class="form-label" style="font-weight: 600; font-size: 13px;">Status Akun</label>
            <select name="is_premium" class="form-control" style="height: 42px; font-size: 13px;">
              <option value="0">Paket Free</option>
              <option value="1">Paket Premium (30 Hari)</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label" style="font-weight: 600; font-size: 13px;">Tentor Pembimbing</label>
            <select name="assigned_tentor_id" class="form-control filter-guru-select">
              <option value="">-- Umum / Belum Ada --</option>
              <?php if (!empty($tentors)): ?>
                <?php foreach ($tentors as $t): ?>
                  <option value="<?= $t['id'] ?>"><?= esc($t['name']) ?></option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
        </div>

        <!-- Notice -->
        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 10px 12px; border-radius: 8px; font-size: 11px; color: var(--text-muted); line-height: 1.4;">
          💡 <strong>Tips:</strong> Setelah disimpan, sistem akan langsung menampilkan tombol untuk menyalin pesan WhatsApp berisi username & password agar dapat langsung diteruskan ke murid.
        </div>

        <div style="display: flex; gap: 8px; justify-content: flex-end; margin-top: 6px;">
          <button type="button" data-modal-close class="btn btn-secondary" style="height: 42px; padding: 0 16px;">
            Batal
          </button>
          <button type="submit" class="btn btn-primary" style="height: 42px; padding: 0 20px; font-weight: 700;">
            Simpan & Buat Akun
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ============================================================== -->
  <!-- MODAL 2: UBAH USERNAME SISWA                                  -->
  <!-- ============================================================== -->
  <div id="modal-ubah-username" class="modal-backdrop">
    <div class="modal-dialog" style="max-width: 440px;">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid var(--border-light); padding-bottom: 10px;">
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy); margin: 0;">
          Ubah Username Siswa
        </h3>
        <button type="button" data-modal-close style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-muted);">&times;</button>
      </div>

      <form id="form-ubah-username" action="" method="post" style="display: flex; flex-direction: column; gap: 12px;">
        <?= csrf_field() ?>
        <div style="background: #F8FAFC; padding: 10px 12px; border-radius: 8px; border: 1px solid #E2E8F0;">
          <span style="font-size: 12px; color: var(--text-muted);">Nama Siswa:</span>
          <strong id="u-student-name" style="display: block; font-size: 14px; color: var(--dark-navy);"></strong>
        </div>

        <div class="form-group">
          <label class="form-label" style="font-size: 12px; font-weight: 600;">Username Saat Ini</label>
          <input type="text" id="u-current-username" class="form-control" disabled style="background: #F1F5F9; font-family: monospace; font-size: 13px; height: 40px;">
        </div>

        <div class="form-group">
          <label class="form-label" style="font-size: 12px; font-weight: 600;">Username Baru <span style="color: red;">*</span></label>
          <input type="text" name="new_username" id="u-new-username" class="form-control" placeholder="Ketik username baru tanpa spasi" required style="font-family: monospace; font-size: 13px; height: 42px;">
          <small style="font-size: 11px; color: var(--text-muted);">Hanya huruf, angka, titik, strip, atau underscore.</small>
        </div>

        <div style="display: flex; gap: 8px; justify-content: flex-end; margin-top: 4px;">
          <button type="button" data-modal-close class="btn btn-secondary" style="height: 40px;">Batal</button>
          <button type="submit" class="btn btn-primary" style="height: 40px; font-weight: 600;">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ============================================================== -->
  <!-- MODAL 3: RESET PASSWORD SISWA                                 -->
  <!-- ============================================================== -->
  <div id="modal-reset-password" class="modal-backdrop">
    <div class="modal-dialog" style="max-width: 460px;">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid var(--border-light); padding-bottom: 10px;">
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 17px; font-weight: 700; color: var(--dark-navy); margin: 0;">
          🔑 Reset Password Siswa
        </h3>
        <button type="button" data-modal-close style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-muted);">&times;</button>
      </div>

      <form id="form-reset-password" action="" method="post" style="display: flex; flex-direction: column; gap: 12px;">
        <?= csrf_field() ?>
        
        <div style="background: #F8FAFC; padding: 10px 12px; border-radius: 8px; border: 1px solid #E2E8F0;">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 12px; color: var(--text-muted);">Murid: <strong id="p-student-name" style="color: var(--dark-navy);"></strong></span>
            <span style="font-size: 12px; font-family: monospace; color: #4338CA; background: #EEF2FF; padding: 2px 6px; border-radius: 4px;">@<span id="p-student-username"></span></span>
          </div>
        </div>

        <div class="form-group">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
            <label class="form-label" style="font-size: 12px; font-weight: 600; margin: 0;">Password Baru Sementara <span style="color: red;">*</span></label>
            <button type="button" onclick="generateRandomPassword('p-new-password')" style="background: none; border: none; color: #4F46E5; font-size: 11px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 3px;">
              🎲 Acak Password
            </button>
          </div>
          
          <div class="input-password-wrapper" style="position: relative;">
            <input type="password" name="new_password" id="p-new-password" class="form-control" placeholder="Minimal 6 karakter" required style="height: 42px; font-size: 13px; padding-right: 42px;">
            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('p-new-password', this)" title="Lihat/Sembunyikan" style="position: absolute; right: 10px; top: 11px; background: none; border: none; cursor: pointer; color: var(--text-muted);">
              <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </button>
          </div>
          <small style="font-size: 11px; color: var(--text-muted);">Password akan langsung dienkripsi bcrypt di sistem.</small>
        </div>

        <div style="display: flex; gap: 8px; justify-content: flex-end; margin-top: 4px;">
          <button type="button" data-modal-close class="btn btn-secondary" style="height: 40px;">Batal</button>
          <button type="submit" class="btn btn-primary" style="height: 40px; font-weight: 600; background: #D9822B; border-color: #D9822B;">
            Simpan Password Baru
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Toast Notification Container -->
  <div id="tekape-toast" style="position: fixed; bottom: 24px; right: 24px; background: #1E2238; color: white; padding: 12px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; display: none; align-items: center; gap: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.25); z-index: 9999; border-left: 4px solid #10B981;">
    <span>✓</span>
    <span id="toast-message">Pesan tersalin!</span>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Open Tambah Akun Modal
function openTambahAkunModal() {
  document.getElementById('modal-tambah-akun').classList.add('open');
}

// Open Ubah Username Modal
function openUbahUsernameModal(id, currentUsername, studentName) {
  document.getElementById('form-ubah-username').action = `<?= base_url('admin/murid/updateStudentUsername') ?>/${id}`;
  document.getElementById('u-student-name').innerText = studentName;
  document.getElementById('u-current-username').value = currentUsername;
  document.getElementById('u-new-username').value = '';
  document.getElementById('modal-ubah-username').classList.add('open');
}

// Open Reset Password Modal
function openResetPasswordModal(id, studentName, username, phone) {
  document.getElementById('form-reset-password').action = `<?= base_url('admin/murid/resetStudentPassword') ?>/${id}`;
  document.getElementById('p-student-name').innerText = studentName;
  document.getElementById('p-student-username').innerText = username;
  document.getElementById('p-new-password').value = '';
  document.getElementById('modal-reset-password').classList.add('open');
}

// Toggle Show/Hide Password
function togglePasswordVisibility(inputId, btn) {
  const input = document.getElementById(inputId);
  if (input.type === 'password') {
    input.type = 'text';
    btn.style.color = '#4F46E5';
  } else {
    input.type = 'password';
    btn.style.color = 'var(--text-muted)';
  }
}

// Generate Random Strong Password
function generateRandomPassword(targetInputId) {
  const chars = 'abcdefghijkmnpqrstuvwxyz23456789';
  let result = 'tkp';
  for (let i = 0; i < 5; i++) {
    result += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  const input = document.getElementById(targetInputId);
  input.value = result;
  input.type = 'text'; // Show generated password immediately to admin
  showToast('Password acak dibuat: ' + result);
}

// Toast helper
function showToast(msg) {
  const toast = document.getElementById('tekape-toast');
  const textEl = document.getElementById('toast-message');
  textEl.innerText = msg;
  toast.style.display = 'flex';
  setTimeout(() => {
    toast.style.display = 'none';
  }, 3000);
}

// Salin Pesan WhatsApp Lengkap
function copyWaText(name, username, password, statusLabel, phone) {
  let pwText = password ? password : '(Silakan hubungi Admin bila belum menerima password)';
  let msg = `Halo ${name},\n\nBerikut adalah rincian akun Anda untuk masuk ke platform belajar TeKaPe.id:\n\n🌐 Website: <?= base_url('login') ?>\n👤 Username: @${username}\n🔑 Password: ${pwText}\n📦 Status: ${statusLabel}\n\nSilakan login ke platform dan persiapkan diri Anda menghadapi tes kedinasan bersama TeKaPe.id.\n\nSalam hangat,\nTim TeKaPe.id`;

  navigator.clipboard.writeText(msg).then(() => {
    showToast('Pesan rincian akun WA untuk ' + name + ' berhasil disalin!');
  }).catch(() => {
    // Fallback prompt if clipboard API blocked
    prompt('Salin teks WhatsApp berikut:', msg);
  });
}

// Salin Pemberitahuan Ubah Username
function copyWaUsernameNotice(name, newUsername, phone) {
  let msg = `Halo ${name},\n\nUsername akun TeKaPe.id Anda telah berhasil diperbarui oleh Admin:\n\n🌐 Website: <?= base_url('login') ?>\n👤 Username Baru: @${newUsername}\n\nSilakan gunakan username baru ini saat melakukan login. Terima kasih!\n\nTim TeKaPe.id`;

  navigator.clipboard.writeText(msg).then(() => {
    showToast('Pemberitahuan perubahan username berhasil disalin!');
  }).catch(() => {
    prompt('Salin teks WhatsApp berikut:', msg);
  });
}
</script>
<?= $this->endSection() ?>
