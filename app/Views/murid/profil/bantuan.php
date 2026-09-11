<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-4 d-flex align-items-center gap-3">
    <a href="<?= base_url('murid/profil') ?>" class="btn btn-sm btn-white text-navy" style="width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #E2E8F0; text-decoration: none;">
        <i class="bi bi-arrow-left fs-5"></i>
    </a>
    <div>
        <h2 class="mb-0 fs-5 fw-bold text-navy">Bantuan Belajar</h2>
        <p class="mb-0 text-muted" style="font-size: 0.8rem;">Konsultasi langsung dengan Tim Tentor kami</p>
    </div>
</div>

<!-- Card 1: Profil Tentor Bertugas -->
<div class="p-3 mb-3 border card border-slate-100" style="background: #FFFFFF; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center justify-content-center fw-bold text-amber" style="width: 52px; height: 52px; border-radius: 16px; background: #FEF3C7; font-size: 1.3rem;">
            <i class="bi bi-person-workspace"></i>
        </div>
        <div class="flex-grow-1">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold text-navy" style="font-size: 0.95rem;"><?= esc($tentorName) ?></h6>
                <span class="badge" style="background: #D1FAE5; color: #065F46; font-size: 0.68rem; font-weight: 600;">Fast Response</span>
            </div>
            <p class="mb-0 text-muted" style="font-size: 0.78rem;">Tim Akademik & Konsultasi TeKaPe.id</p>
            <div class="mt-1 d-flex align-items-center gap-2" style="font-size: 0.72rem; color: #10B981;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #10B981; display: inline-block;"></span>
                <span>Online (08.00 - 21.00 WIB)</span>
            </div>
        </div>
    </div>
</div>

<!-- Card 2: Pilih Topik Bantuan -->
<div class="p-3 mb-3 border card border-slate-100" style="background: #FFFFFF; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
    <label class="mb-2 form-label text-navy fw-bold" style="font-size: 0.85rem;">Pilih Topik Konsultasi</label>
    <div class="d-flex flex-wrap gap-2" id="topicPills">
        <button type="button" class="btn btn-sm topic-btn active" data-topic="Pertanyaan Soal TWK" style="border-radius: 20px; font-size: 0.78rem; padding: 6px 14px; border: 1px solid #E2E8F0;">
            Pertanyaan Soal TWK
        </button>
        <button type="button" class="btn btn-sm topic-btn" data-topic="Pembahasan Soal TIU" style="border-radius: 20px; font-size: 0.78rem; padding: 6px 14px; border: 1px solid #E2E8F0;">
            Pembahasan Soal TIU
        </button>
        <button type="button" class="btn btn-sm topic-btn" data-topic="Tips Ujian TKP" style="border-radius: 20px; font-size: 0.78rem; padding: 6px 14px; border: 1px solid #E2E8F0;">
            Tips Ujian TKP
        </button>
        <button type="button" class="btn btn-sm topic-btn" data-topic="Jadwal Kelas Online" style="border-radius: 20px; font-size: 0.78rem; padding: 6px 14px; border: 1px solid #E2E8F0;">
            Jadwal Kelas Online
        </button>
        <button type="button" class="btn btn-sm topic-btn" data-topic="Kendala Teknis Aplikasi" style="border-radius: 20px; font-size: 0.78rem; padding: 6px 14px; border: 1px solid #E2E8F0;">
            Kendala Teknis
        </button>
    </div>
</div>

<!-- Card 3: Pesan / Catatan Singkat -->
<div class="p-4 mb-4 border card border-slate-100" style="background: #FFFFFF; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.04);">
    <div class="mb-3">
        <label class="form-label text-navy fw-bold" style="font-size: 0.85rem;">Pesan / Pertanyaan Tambahan</label>
        <textarea id="pesanTambahan" rows="3" class="form-control" placeholder="Tuliskan nomor soal atau hal spesifik yang ingin kamu tanyakan..." style="border-radius: 12px; border-color: #E2E8F0; font-size: 0.88rem; resize: none;"></textarea>
    </div>

    <button type="button" onclick="kirimWhatsApp()" class="btn w-100 fw-bold d-flex align-items-center justify-content-center gap-2" style="background: #25D366; color: #FFFFFF; height: 50px; border-radius: 14px; font-size: 0.95rem; border: none; box-shadow: 0 4px 12px rgba(37,211,102,0.3);">
        <i class="bi bi-whatsapp fs-5"></i> Buka WhatsApp Sekarang
    </button>
    <p class="mt-2 text-center text-muted" style="font-size: 0.72rem;">Kamu akan diarahkan langsung ke chat WhatsApp resmi tentor</p>
</div>

<style>
.topic-btn {
    background: #F8FAFC;
    color: #475569;
    transition: all 0.2s ease;
}
.topic-btn.active {
    background: #0F172A !important;
    color: #FFFFFF !important;
    border-color: #0F172A !important;
}
</style>

<script>
let selectedTopic = 'Pertanyaan Soal TWK';

document.querySelectorAll('.topic-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.topic-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        selectedTopic = this.getAttribute('data-topic');
    });
});

function kirimWhatsApp() {
    const waNumber = '<?= esc($tentorWa) ?>';
    const catatan = document.getElementById('pesanTambahan').value.trim();
    const namaSiswa = '<?= esc(session()->get('user_name') ?? 'Siswa') ?>';

    let msg = `Halo Tentor TeKaPe.id, perkenalkan saya *${namaSiswa}*.\n\n`;
    msg += `Saya ingin berkonsultasi mengenai topik: *${selectedTopic}*.\n`;
    if (catatan) {
        msg += `\nPertanyaan saya:\n"${catatan}"\n`;
    }
    msg += `\nMohon bimbingannya. Terima kasih!`;

    const url = `https://wa.me/${waNumber}?text=${encodeURIComponent(msg)}`;
    window.open(url, '_blank');
}
</script>
<?= $this->endSection() ?>
