<style>
    .cta-bantuan-wrapper {
        margin-top: 60px;
        margin-bottom: 60px;
    }

    .cta-bantuan-card {
        position: relative;
        /* Gambar background gedung kampus sebagai pemanis, bisa diganti dengan path gambar lokal Anda */
        background-image: url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=2070&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        border-radius: 12px;
        padding: 40px 50px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(234, 179, 8, 0.2);
    }

    /* Lapisan warna kuning transparan (overlay) */
    .cta-bantuan-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(135deg, rgba(250, 204, 21, 0.9) 0%, rgba(217, 119, 6, 0.9) 100%);
        z-index: 1;
    }

    .cta-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        color: #ffffff;
    }

    .cta-text {
        flex: 1;
        min-width: 300px;
    }

    .cta-text h2 {
        font-family: var(--heading-font, sans-serif);
        font-weight: 800;
        font-size: 1.8rem;
        margin-bottom: 12px;
        color: #ffffff;
    }

    .cta-text p {
        font-size: 1rem;
        margin-bottom: 0;
        opacity: 0.95;
        line-height: 1.6;
        max-width: 600px;
    }

    .cta-buttons {
        display: flex;
        gap: 15px;
    }

    .btn-cta-outline {
        border: 1px solid #ffffff;
        color: #ffffff;
        background: transparent;
        padding: 10px 24px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-cta-outline:hover {
        background: #ffffff;
        color: #d97706; /* Warna teks berubah oranye saat di-hover */
        text-decoration: none;
    }

    /* Responsif untuk layar HP */
    @media (max-width: 768px) {
        .cta-content {
            flex-direction: column;
            align-items: flex-start;
        }
        .cta-buttons {
            flex-direction: column;
            width: 100%;
        }
        .btn-cta-outline {
            justify-content: center;
            width: 100%;
        }
        .cta-bantuan-card {
            padding: 30px 20px;
        }
    }
</style>

<div class="cta-bantuan-wrapper container">
    <div class="cta-bantuan-card">
        <div class="cta-content">
            <div class="cta-text">
                <h2>Kami Siap Membantu Anda</h2>
                <p>Apabila kamu memiliki kendala atau pertanyaan. Silakan hubungi kami atau dapat juga membaca Petunjuk Pendaftaran terlebih dahulu</p>
            </div>
            <div class="cta-buttons">
                <a href="https://wa.me/62895705354767" target="_blank" class="btn-cta-outline">
                    <i class="fab fa-whatsapp"></i> Whatsapp
                </a>
                {{-- <a href="#" class="btn-cta-outline">
                    Petunjuk Pendaftaran
                </a> --}}
            </div>
        </div>
    </div>
</div>
