@extends('user::layouts/halamandepan/master')
@section('title', $title)

@section('link_href')
@endsection
@section('content')
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

        <div class="hero-container">
            <video autoplay="" muted="" loop="" playsinline="" class="video-background">
                <source src="{{ asset('public/assets/user/img/education/tsu.mp4') }}" type="video/mp4">
            </video>
            <div class="overlay"></div>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-7" data-aos="zoom-out" data-aos-delay="100">
                        <div class="hero-content">
                            <h2>Seleksi Penerimaan Mahasiswa Baru</h2>
                            <h4>Tahun Ajaran 2025/2026</h4>
                            <p> <a href="#">Info Pendaftaran Mahasiswa Baru Gelombang 2 Th. 2025</a> </p>
                            <p> <a href="#">Info Pendaftaran Mahasiswa Baru Gelombang 3 Th. 2025</a> </p>
                            <div>
                                <a href="{{ route('register') }}" class="btn btn-warning btn-lg text-bold">Daftar
                                    Sekarang</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5" data-aos="zoom-out" data-aos-delay="200" style="display: none;">
                        <div class="stats-card">
                            <div class="stats-header">
                                <h3>Why Choose Us</h3>
                                <div class="decoration-line"></div>
                            </div>
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="bi bi-trophy-fill"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h4>98%</h4>
                                        <p>Graduate Employment</p>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="bi bi-globe"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h4>45+</h4>
                                        <p>International Partners</p>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="bi bi-mortarboard"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h4>15:1</h4>
                                        <p>Student-Faculty Ratio</p>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h4>120+</h4>
                                        <p>Degree Programs</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="event-ticker">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-md-6 col-xl-4 col-12 ticker-item">
                        <span class="date">NOV 15</span>
                        <span class="title">Open House Day</span>
                        <a href="#" class="btn-register">Register</a>
                    </div>
                    <div class="col-md-6 col-12 col-xl-4  ticker-item">
                        <span class="date">DEC 5</span>
                        <span class="title">Application Workshop</span>
                        <a href="#" class="btn-register">Register</a>
                    </div>
                    <div class="col-md-6 col-12 col-xl-4 ticker-item">
                        <span class="date">JAN 10</span>
                        <span class="title">International Student Orientation</span>
                        <a href="#" class="btn-register">Register</a>
                    </div>
                </div>
            </div>
        </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row mb-5">
                <div class="col-lg-6 pe-lg-5" data-aos="fade-right" data-aos-delay="200">
                    <h2 class="display-6 fw-bold mb-4">Kampus Berbasis <span>Kolaborasi Industri.</span></h2>
                    <p class="lead mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus,
                        luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>
                    <div class="d-flex flex-wrap gap-4 mb-4">
                        <div class="stat-box">
                            <span class="stat-number"><span data-purecounter-start="0" data-purecounter-end="28"
                                    data-purecounter-duration="1" class="purecounter"></span>+</span>
                            <span class="stat-label">Years</span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-number"><span data-purecounter-start="0" data-purecounter-end="650"
                                    data-purecounter-duration="1" class="purecounter"></span>+</span>
                            <span class="stat-label">Student</span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-number"><span data-purecounter-start="0" data-purecounter-end="100"
                                    data-purecounter-duration="1" class="purecounter"></span>+</span>
                            <span class="stat-label">Dosen</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-4 signature-block">
                        <img src="{{ asset('public/assets/user/img/misc/signature-1.webp') }}" alt="Principal's Signature"
                            width="120">
                        <div class="ms-3">
                            <p class="mb-0 fw-bold">Dr. Eny Rahma Zaenah, S.E., M.M.</p>
                            <p class="mb-0 text-muted">Rektor
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
                    <div class="image-stack">
                        <div class="image-stack-item image-stack-item-top" data-aos="zoom-in" data-aos-delay="400">
                            <img src="{{ asset('public/assets/user/img/education/campus-4.webp') }}" alt="Campus Life"
                                class="img-fluid rounded-4 shadow-lg">
                        </div>
                        <div class="image-stack-item image-stack-item-bottom" data-aos="zoom-in" data-aos-delay="500">
                            <img src="{{ asset('public/assets/user/img/education/students-2.webp') }}" alt="Students"
                                class="img-fluid rounded-4 shadow-lg">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mission-vision-row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="value-card h-100">
                        <div class="card-icon">
                            <i class="bi bi-rocket-takeoff"></i>
                        </div>
                        <h3>Our Mission</h3>
                        <p>Menjadi pusat pengetahuan teknologi dan kebudayaan yang inovatif, relevan, serta berstandar
                            nasional dan internasional.
                        </p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="value-card h-100">
                        <div class="card-icon">
                            <i class="bi bi-eye"></i>
                        </div>
                        <h3>Our Vision</h3>
                        <p>Menjadi kampus yang dapat berinovasi,bersinergi,berkualitas,dan berkarakter</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="value-card h-100">
                        <div class="card-icon">
                            <i class="bi bi-star"></i>
                        </div>
                        <h3>Our Values</h3>
                        <p>Nilai Nilai yang dipegang oleh mahasiswa kami adalah hebat,Humility (Rendah Hati),Empathy (Empati),Brave (Berani),Agile (Lincah/Adaptif),Trustworthy (Terpercaya)</p>
                    </div>
                </div>
            </div>

        </div>

    </section><!-- /About Section -->

    <!-- Featured Programs Section -->
    <section id="featured-programs" class="featured-programs section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Program Studi</h2>
            <p>Daftar Program Studi Tiga Serangkai University</p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">
                <ul class="program-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
                    <li data-filter="*" class="filter-active">Semua Jenjang</li>
                    <li data-filter=".filter-bachelor">Sarjana</li>
                    <li data-filter=".filter-master">Diploma</li>
                    <li data-filter=".filter-certificate">Certificates</li>
                </ul>

                <div class="row g-4 isotope-container">
                    <div class="col-lg-6 isotope-item filter-bachelor" data-aos="zoom-in" data-aos-delay="100">
                        <div class="program-item">
                            <div class="program-badge">Gelar Sarjana</div>
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <div class="program-image-wrapper">
                                        <img src="{{ asset('public/assets/user/img/education/education-1.webp') }}"
                                            class="img-fluid" alt="Program">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="program-content">
                                        <h3>Informatika</h3>
                                        <div class="program-highlights">
                                            <span><i class="bi bi-clock"></i> 4 Years</span>
                                            <span><i class="bi bi-people-fill"></i> 50 Mahasiswa</span>
                                            <span><i class="bi bi-calendar3"></i> Fall &amp; Spring</span>
                                        </div>
                                        <p>
                                            Dengan mengintegrasikan kurikulum berbasis industri untuk mencetak tenaga ahli
                                            teknologi yang adaptif dan memiliki daya saing tinggi.
                                            Melalui pendekatan praktis yang mendalam, setiap lulusan dipersiapkan dengan
                                            portofolio nyata guna menjawab tantangan karier di era digital.</p>
                                        <a href="#" class="program-btn"><span>Learn More</span> <i
                                                class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Program Item -->

                    <div class="col-lg-6 isotope-item filter-bachelor" data-aos="zoom-in" data-aos-delay="200">
                        <div class="program-item">
                            <div class="program-badge">Gelar Sarjana</div>
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <div class="program-image-wrapper">
                                        <img src="{{ asset('public/assets/user/img/education/education-3.webp') }}"
                                            class="img-fluid" alt="Program">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="program-content">
                                        <h3>Manajemen</h3>
                                        <div class="program-highlights">
                                            <span><i class="bi bi-clock"></i> 4 Years</span>
                                            <span><i class="bi bi-people-fill"></i> 50 Mahasiswa</span>
                                            <span><i class="bi bi-calendar3"></i> Fall Only</span>
                                        </div>
                                        <p>Dirancang untuk mencetak pemimpin bisnis masa depan melalui kurikulum dan
                                            ekosistem industri modern.
                                            Fokus utamanya adalah membentuk kemampuan manajerial strategis dan
                                            kewirausahaan, memastikan setiap lulusan siap mengelola organisasi atau merintis
                                            bisnis yang kompetitif.</p>
                                        <a href="#" class="program-btn"><span>Learn More</span> <i
                                                class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Program Item -->

                    <div class="col-lg-6 isotope-item filter-bachelor" data-aos="zoom-in" data-aos-delay="300">
                        <div class="program-item">
                            <div class="program-badge">Gelar Sarjana</div>
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <div class="program-image-wrapper">
                                        <img src="{{ asset('public/assets/user/img/education/education-5.webp') }}"
                                            class="img-fluid" alt="Program">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="program-content">
                                        <h3>Pendidikan Guru Sekolah Dasar</h3>
                                        <div class="program-highlights">
                                            <span><i class="bi bi-clock"></i> 4 Years</span>
                                            <span><i class="bi bi-people-fill"></i> 50 Mahasiswa</span>
                                            <span><i class="bi bi-calendar3"></i> Fall Only</span>
                                        </div>
                                        <p>mencetak tenaga pendidik profesional yang unggul dalam inovasi pembelajaran dan
                                            pembentukan karakter siswa.
                                            Melalui pendekatan pedagogi modern yang terintegrasi, setiap lulusan
                                            dipersiapkan menjadi guru sekolah dasar yang kompeten dan adaptif terhadap
                                            perkembangan dunia pendidikan.</p>
                                        <a href="#" class="program-btn"><span>Learn More</span> <i
                                                class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Program Item -->

                    <div class="col-lg-6 isotope-item filter-master" data-aos="zoom-in" data-aos-delay="100">
                        <div class="program-item">
                            <div class="program-badge">Gelar Diploma</div>
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <div class="program-image-wrapper">
                                        <img src="{{ asset('public/assets/user/img/education/education-7.webp') }}"
                                            class="img-fluid" alt="Program">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="program-content">
                                        <h3>Sistem Informasi</h3>
                                        <div class="program-highlights">
                                            <span><i class="bi bi-clock"></i> 4 Years</span>
                                            <span><i class="bi bi-people-fill"></i> 50 Mahasiswa</span>
                                            <span><i class="bi bi-calendar3"></i> Spring Only</span>
                                        </div>
                                        <p>Sistem Informasi menjembatani teknologi dan kebutuhan bisnis yang fokus pada
                                            analisis sistem serta manajemen aset digital strategis.
                                            Dengan penekanan pada integrasi proses bisnis, lulusan dibentuk menjadi
                                            profesional yang mampu mengoptimalkan efisiensi organisasi berbasis teknologi.
                                        </p>
                                        <a href="#" class="program-btn"><span>Learn More</span> <i
                                                class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Program Item -->

                    <div class="col-lg-6 isotope-item filter-bachelor" data-aos="zoom-in" data-aos-delay="200">
                        <div class="program-item">
                            <div class="program-badge">Gelar Sarjana</div>
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <div class="program-image-wrapper">
                                        <img src="{{ asset('public/assets/user/img/education/education-9.webp') }}"
                                            class="img-fluid" alt="Program">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="program-content">
                                        <h3>Rekayasa Komputer</h3>
                                        <div class="program-highlights">
                                            <span><i class="bi bi-clock"></i> 4 Years</span>
                                            <span><i class="bi bi-people-fill"></i> 50 Mahasiswa</span>
                                            <span><i class="bi bi-calendar3"></i> Fall &amp; Spring</span>
                                        </div>
                                        <p>memadukan perangkat keras dan perangkat lunak untuk merancang sistem cerdas serta
                                            otomatisasi industri terpadu.
                                            Melalui kurikulum berbasis praktik langsung, lulusan disiapkan menjadi inovator
                                            teknologi yang andal dalam mengembangkan solusi Internet of Things (IoT) dan
                                            arsitektur jaringan masa depan</p>
                                        <a href="#" class="program-btn"><span>Learn More</span> <i
                                                class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Program Item -->

                    <div class="col-lg-6 isotope-item filter-bachelor" data-aos="zoom-in" data-aos-delay="100">
                        <div class="program-item">
                            <div class="program-badge">Gelar Sarjana</div>
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <div class="program-image-wrapper">
                                        <img src="{{ asset('public/assets/user/img/education/education-2.webp') }}"
                                            class="img-fluid" alt="Program">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="program-content">
                                        <h3>Psikologi</h3>
                                        <div class="program-highlights">
                                            <span><i class="bi bi-clock"></i> 4 Tahun</span>
                                            <span><i class="bi bi-people-fill"></i> 50 Mahasiswa</span>
                                            <span><i class="bi bi-calendar3"></i> Year-round</span>
                                        </div>
                                        <p>
                                            Mempelajari perilaku manusia untuk mencetak profesional andal di bidang
                                            pengembangan sumber daya manusia dan kesehatan mental.
                                            Melalui pendekatan yang humanis dan aplikatif, lulusan dipersiapkan memberikan
                                            solusi psikologis strategis bagi individu maupun organisasi modern.</p>
                                        <a href="#" class="program-btn"><span>Learn More</span> <i
                                                class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Program Item -->

                    <div class="col-lg-6 isotope-item filter-master" data-aos="zoom-in" data-aos-delay="100">
                        <div class="program-item">
                            <div class="program-badge">Gelar Diploma</div>
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <div class="program-image-wrapper">
                                        <img src="{{ asset('public/assets/user/img/education/education-2.webp') }}"
                                            class="img-fluid" alt="Program">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="program-content">
                                        <h3>Teknologi Informasi</h3>
                                        <div class="program-highlights">
                                            <span><i class="bi bi-clock"></i> 3 Tahun</span>
                                            <span><i class="bi bi-people-fill"></i> 50 Mahasiswa</span>
                                            <span><i class="bi bi-calendar3"></i> Year-round</span>
                                        </div>
                                        <p>
                                            dirancang untuk mencetak tenaga ahli dengan keterampilan teknis aplikatif yang
                                            siap terjun langsung menangani kebutuhan infrastruktur teknologi.
                                            lulusan dibentuk menjadi profesional andal dalam administrasi jaringan, dukungan
                                            teknis, dan pengembangan solusi digital terapan.</p>
                                        <a href="#" class="program-btn"><span>Learn More</span> <i
                                                class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Program Item -->

                    <div class="col-lg-6 isotope-item filter-master" data-aos="zoom-in" data-aos-delay="100">
                        <div class="program-item">
                            <div class="program-badge">Gelar Diploma</div>
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <div class="program-image-wrapper">
                                        <img src="{{ asset('public/assets/user/img/education/education-2.webp') }}"
                                            class="img-fluid" alt="Program">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="program-content">
                                        <h3>Desain Produk Tekstil</h3>
                                        <div class="program-highlights">
                                            <span><i class="bi bi-clock"></i> 3 Tahun</span>
                                            <span><i class="bi bi-people-fill"></i> 50 Mahasiswa</span>
                                            <span><i class="bi bi-calendar3"></i> Year-round</span>
                                        </div>
                                        <p>
                                            Desain Produk Tekstil dirancang untuk mencetak tenaga ahli desain tekstil
                                            inovatif yang sesuai dengan tren industri mode global.
                                            Melalui penguasaan teknologi manufaktur modern, lulusan dipersiapkan menjadi
                                            desainer profesional yang mampu menghasilkan karya seni terapan bernilai
                                            komersial tinggi</p>
                                        <a href="#" class="program-btn"><span>Learn More</span> <i
                                                class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Program Item -->

                    <div class="col-lg-6 isotope-item filter-master" data-aos="zoom-in" data-aos-delay="100">
                        <div class="program-item">
                            <div class="program-badge">Gelar Diploma</div>
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <div class="program-image-wrapper">
                                        <img src="{{ asset('public/assets/user/img/education/education-2.webp') }}"
                                            class="img-fluid" alt="Program">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="program-content">
                                        <h3>Desain Komunikasi Visual</h3>
                                        <div class="program-highlights">
                                            <span><i class="bi bi-clock"></i> 3 Tahun</span>
                                            <span><i class="bi bi-people-fill"></i> 50 Mahasiswa</span>
                                            <span><i class="bi bi-calendar3"></i> Year-round</span>
                                        </div>
                                        <p>
                                            Dirancang mengembangkan keterampilan visual kreatif dan teknologi media untuk
                                            menghasilkan solusi komunikasi strategis yang efektif.
                                            Melalui kurikulum berbasis proyek nyata, lulusan dipersiapkan menjadi desainer
                                            profesional yang siap bersaing di industri kreatif dan digital.</p>
                                        <a href="#" class="program-btn"><span>Learn More</span> <i
                                                class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Program Item -->

                    <div class="col-lg-6 isotope-item filter-master" data-aos="zoom-in" data-aos-delay="100">
                        <div class="program-item">
                            <div class="program-badge">Gelar Diploma</div>
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <div class="program-image-wrapper">
                                        <img src="{{ asset('public/assets/user/img/education/education-2.webp') }}"
                                            class="img-fluid" alt="Program">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="program-content">
                                        <h3>Sistem Informasi Akuntansi</h3>
                                        <div class="program-highlights">
                                            <span><i class="bi bi-clock"></i> 3 Tahun</span>
                                            <span><i class="bi bi-people-fill"></i> 50 Mahasiswa</span>
                                            <span><i class="bi bi-calendar3"></i> Year-round</span>
                                        </div>
                                        <p>
                                            mengintegrasikan keahlian akuntansi keuangan dengan teknologi sistem informasi
                                            guna mencetak tenaga profesional andal dalam pengelolaan data bisnis.
                                            Melalui penguasaan aplikasi keuangan terkini, lulusan dipersiapkan untuk
                                            menjamin akurasi dan efisiensi pelaporan administrasi organisasi</p>
                                        <a href="#" class="program-btn"><span>Learn More</span> <i
                                                class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Program Item -->
                </div>
            </div>

        </div>

    </section><!-- /Featured Programs Section -->

    <!-- Students Life Block Section -->
    <section id="students-life-block" class="students-life-block section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Students Life</h2>
            <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row align-items-center gy-4">
                <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
                    <div class="students-life-img position-relative">
                        <img src="{{ asset('public/assets/user/img/education/education-square-11.webp') }}"
                            class="img-fluid rounded-4 shadow-sm" alt="Students Life">
                        <div class="img-overlay">
                            <h3>Discover Campus Life</h3>
                            <a href="students-life.html" class="explore-btn">Explore More <i
                                    class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
                    <div class="students-life-content">

                        <div class="row g-4 mb-4">
                            <div class="col-md-6" data-aos="zoom-in" data-aos-delay="200">
                                <div class="student-activity-item">
                                    <div class="icon-box">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <h4>Student Clubs</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam purus.</p>
                                </div>
                            </div>

                            <div class="col-md-6" data-aos="zoom-in" data-aos-delay="300">
                                <div class="student-activity-item">
                                    <div class="icon-box">
                                        <i class="bi bi-trophy"></i>
                                    </div>
                                    <h4>Sports Events</h4>
                                    <p>Quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat.</p>
                                </div>
                            </div>

                            <div class="col-md-6" data-aos="zoom-in" data-aos-delay="400">
                                <div class="student-activity-item">
                                    <div class="icon-box">
                                        <i class="bi bi-music-note-beamed"></i>
                                    </div>
                                    <h4>Arts &amp; Culture</h4>
                                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6" data-aos="zoom-in" data-aos-delay="500">
                                <div class="student-activity-item">
                                    <div class="icon-box">
                                        <i class="bi bi-globe-americas"></i>
                                    </div>
                                    <h4>Global Experiences</h4>
                                    <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="students-life-cta" data-aos="fade-up" data-aos-delay="600">
                            <a href="students-life.html" class="btn btn-primary">View All Student Activities</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section><!-- /Students Life Block Section -->

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Testimonials</h2>
            <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
        </div><!-- End Section Title -->

        <div class="container">
            <div class="testimonial-masonry">

                <div class="testimonial-item" data-aos="fade-up">
                    <div class="testimonial-content">
                        <div class="quote-pattern">
                            <i class="bi bi-quote"></i>
                        </div>
                        <p>Implementing innovative strategies has revolutionized our approach to market challenges
                            and competitive positioning.</p>
                        <div class="client-info">
                            <div class="client-image">
                                <img src="{{ asset('public/assets/user/img/person/person-f-7.webp') }}" alt="Client">
                            </div>
                            <div class="client-details">
                                <h3>Rachel Bennett</h3>
                                <span class="position">Strategy Director</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-item highlight" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-content">
                        <div class="quote-pattern">
                            <i class="bi bi-quote"></i>
                        </div>
                        <p>Exceptional service delivery and innovative solutions have transformed our business
                            operations, leading to remarkable growth and enhanced customer satisfaction across all
                            touchpoints.</p>
                        <div class="client-info">
                            <div class="client-image">
                                <img src="{{ asset('public/assets/user/img/person/person-m-7.webp') }}" alt="Client">
                            </div>
                            <div class="client-details">
                                <h3>Daniel Morgan</h3>
                                <span class="position">Chief Innovation Officer</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-content">
                        <div class="quote-pattern">
                            <i class="bi bi-quote"></i>
                        </div>
                        <p>Strategic partnership has enabled seamless digital transformation and operational
                            excellence.</p>
                        <div class="client-info">
                            <div class="client-image">
                                <img src="{{ asset('public/assets/user/img/person/person-f-8.webp') }}" alt="Client">
                            </div>
                            <div class="client-details">
                                <h3>Emma Thompson</h3>
                                <span class="position">Digital Lead</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-content">
                        <div class="quote-pattern">
                            <i class="bi bi-quote"></i>
                        </div>
                        <p>Professional expertise and dedication have significantly improved our project delivery
                            timelines and quality metrics.</p>
                        <div class="client-info">
                            <div class="client-image">
                                <img src="{{ asset('public/assets/user/img/person/person-m-8.webp') }}" alt="Client">
                            </div>
                            <div class="client-details">
                                <h3>Christopher Lee</h3>
                                <span class="position">Technical Director</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-item highlight" data-aos="fade-up" data-aos-delay="400">
                    <div class="testimonial-content">
                        <div class="quote-pattern">
                            <i class="bi bi-quote"></i>
                        </div>
                        <p>Collaborative approach and industry expertise have revolutionized our product development
                            cycle, resulting in faster time-to-market and increased customer engagement levels.</p>
                        <div class="client-info">
                            <div class="client-image">
                                <img src="{{ asset('public/assets/user/img/person/person-f-9.webp') }}" alt="Client">
                            </div>
                            <div class="client-details">
                                <h3>Olivia Carter</h3>
                                <span class="position">Product Manager</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-item" data-aos="fade-up" data-aos-delay="500">
                    <div class="testimonial-content">
                        <div class="quote-pattern">
                            <i class="bi bi-quote"></i>
                        </div>
                        <p>Innovative approach to user experience design has significantly enhanced our platform's
                            engagement metrics and customer retention rates.</p>
                        <div class="client-info">
                            <div class="client-image">
                                <img src="{{ asset('public/assets/user/img/person/person-m-13.webp') }}" alt="Client">
                            </div>
                            <div class="client-details">
                                <h3>Nathan Brooks</h3>
                                <span class="position">UX Director</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section><!-- /Testimonials Section -->

    <!-- Stats Section -->
    <section id="stats" class="stats section" style="display: none">

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row">
                <div class="col-lg-6">
                    <div class="stats-overview" data-aos="fade-right" data-aos-delay="200">
                        <h2 class="stats-title">Excellence in Education for Over 50 Years</h2>
                        <p class="stats-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Mauris vel ultricies magna. Maecenas finibus convallis turpis, non facilisis justo
                            egestas in. Nulla facilisi. Fusce consectetur, enim eget aliquet volutpat, lacus nulla
                            semper velit.</p>
                        <div class="stats-cta">
                            <a href="#" class="btn btn-primary">Learn More</a>
                            <a href="#" class="btn btn-outline">Virtual Tour</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="stats-card" data-aos="zoom-in" data-aos-delay="300">
                                <div class="stats-icon">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div class="stats-number">
                                    <span data-purecounter-start="0" data-purecounter-end="94"
                                        data-purecounter-duration="1" class="purecounter"></span>%
                                </div>
                                <div class="stats-label">Graduation Rate</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stats-card" data-aos="zoom-in" data-aos-delay="400">
                                <div class="stats-icon">
                                    <i class="bi bi-person-workspace"></i>
                                </div>
                                <div class="stats-number">
                                    <span data-purecounter-start="0" data-purecounter-end="15"
                                        data-purecounter-duration="1" class="purecounter"></span>:1
                                </div>
                                <div class="stats-label">Student-Faculty Ratio</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stats-card" data-aos="zoom-in" data-aos-delay="500">
                                <div class="stats-icon">
                                    <i class="bi bi-award"></i>
                                </div>
                                <div class="stats-number">
                                    <span data-purecounter-start="0" data-purecounter-end="125"
                                        data-purecounter-duration="1" class="purecounter"></span>+
                                </div>
                                <div class="stats-label">Academic Programs</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stats-card" data-aos="zoom-in" data-aos-delay="600">
                                <div class="stats-icon">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="stats-number">$<span data-purecounter-start="0" data-purecounter-end="42"
                                        data-purecounter-duration="1" class="purecounter"></span>M
                                </div>
                                <div class="stats-label">Research Funding</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="achievements-gallery" data-aos="fade-up" data-aos-delay="700">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="achievement-item">
                                    <img src="{{ asset('public/assets/user/img/education/education-1.webp') }}"
                                        alt="Achievement" class="img-fluid">
                                    <div class="achievement-content">
                                        <h4>Top-Ranked Programs</h4>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris vel
                                            ultricies magna.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="achievement-item">
                                    <img src="{{ asset('public/assets/user/img/education/education-2.webp') }}"
                                        alt="Achievement" class="img-fluid">
                                    <div class="achievement-content">
                                        <h4>State-of-the-Art Facilities</h4>
                                        <p>Maecenas finibus convallis turpis, non facilisis justo egestas in. Nulla
                                            facilisi.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="achievement-item">
                                    <img src="{{ asset('public/assets/user/img/education/education-3.webp') }}"
                                        alt="Achievement" class="img-fluid">
                                    <div class="achievement-content">
                                        <h4>Global Alumni Network</h4>
                                        <p>Fusce consectetur, enim eget aliquet volutpat, lacus nulla semper velit,
                                            et luctus.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section><!-- /Stats Section -->

    <!-- Recent News Section -->
    <section id="recent-news" class="recent-news section">
        <div class="container section-title" data-aos="fade-up">
            <h2>Recent News</h2>
            <p>Berita terbaru seputar kegiatan dan pencapaian universitas</p>
        </div>

        <div class="container">
            <div class="row gy-4 justify-content-center">
                <div class="col-lg-5 col-md-12 h-100" data-aos="fade-up" data-aos-delay="100">

                    <article class="big-news-card shadow-sm rounded overflow-hidden">

                        <a href="blog-details.html" class="img-container">
                            <img src="{{ asset('public/assets/user/img/education/kajian-1.png') }}" alt="News Image">
                        </a>

                        <div class="text-container">
                            <div class="meta-info text-muted mb-2">
                                <small>13/02/2026 &bull; Other</small>
                            </div>

                            <h3 class="title">
                                <a href="blog-details.html">
                                    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Laborum eligendi consequuntur
                                    sint delectus deserunt praesentium blanditiis quasi dolorum, necessitatibus sed.
                                </a>
                            </h3>
                        </div>

                    </article>

                </div>

                <div class="col-lg-5 col-md-12">
                    <div class="d-flex flex-column gap-2">

                        <article class="d-flex bg-white shadow-sm rounded overflow-hidden" data-aos="fade-up"
                            data-aos-delay="200">
                            <a href="blog-details.html" class="post-img-wrapper overflow-hidden"
                                style="width: 120px; flex-shrink: 0; display: block;">
                                <img src="{{ asset('public/assets/user/img/education/milad-1.png') }}" alt=""
                                    class="w-100 h-100 img-zoom" style="object-fit: cover; display: block;">
                            </a>

                            <div class="d-flex flex-column justify-content-center p-2">
                                <p class="post-category text-muted mb-1" style="font-size: 0.75rem;">12/02/2026 &bull;
                                    merayakan</p>

                                <h6 class="title mb-0" style="font-size: 0.95rem; line-height: 1.2;">
                                    <a href="blog-details.html" class="text-dark text-decoration-none">
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum ipsa recusandae
                                        ipsam assumenda eveniet magnam nemo maxime consequatur at enim!...
                                    </a>
                                </h6>
                            </div>
                        </article>

                        <article class="d-flex bg-white shadow-sm rounded overflow-hidden" data-aos="fade-up"
                            data-aos-delay="300">
                            <a href="blog-details.html" class="post-img-wrapper overflow-hidden"
                                style="width: 120px; flex-shrink: 0; display: block;">
                                <img src="{{ asset('public/assets/user/img/education/eduexpo-1.png') }}" alt=""
                                    class="w-100 h-100 img-zoom" style="object-fit: cover; display: block;">
                            </a>
                            <div class="d-flex flex-column justify-content-center p-2">
                                <p class="post-category text-muted mb-1" style="font-size: 0.75rem;">10/02/2026 &bull;
                                    Pendidikan</p>
                                <h6 class="title mb-0" style="font-size: 0.95rem; line-height: 1.2;">
                                    <a href="blog-details.html" class="text-dark text-decoration-none">
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Excepturi distinctio
                                        pariatur, ullam porro saepe vero ad officia ut! Sequi, quo?...
                                    </a>
                                </h6>
                            </div>
                        </article>

                        <article class="d-flex bg-white shadow-sm rounded overflow-hidden" data-aos="fade-up"
                            data-aos-delay="400">
                            <a href="blog-details.html" class="post-img-wrapper overflow-hidden"
                                style="width: 120px; flex-shrink: 0; display: block;">
                                <img src="{{ asset('public/assets/user/img/education/eduexpo-2.png') }}" alt=""
                                    class="w-100 h-100 img-zoom" style="object-fit: cover; display: block;">
                            </a>
                            <div class="d-flex flex-column justify-content-center p-2">
                                <p class="post-category text-muted mb-1" style="font-size: 0.75rem;">10/02/2026 &bull;
                                    Pendidikan</p>
                                <h6 class="title mb-0" style="font-size: 0.95rem; line-height: 1.2;">
                                    <a href="blog-details.html" class="text-dark text-decoration-none">
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus maiores nisi
                                        blanditiis? Dignissimos sapiente temporibus blanditiis officiis ullam nihil
                                        natus....
                                    </a>
                                </h6>
                            </div>
                        </article>
                        <div class="text-center mt-5">
                            <a href="{{ route('pengumuman') }}" class="btn-view-more">
                                Berita lainnya <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- /Recent News Section -->

    <!-- Events Section -->
    <section id="info-section" class="info-section section">

        <div class="container section-title" data-aos="fade-up">
            <h2>Events</h2>
            <p>Featured updates and information</p>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="slider-container position-relative">

                <div class="swiper init-swiper">
                    <div class="swiper-wrapper">

                        <div class="swiper-slide">
                            <a href="detail-event-1.html" class="agenda-card">

                                <div class="agenda-img">
                                    <img src="public/assets/user/img/education/kajian-1.png" alt="Highlight 1">
                                </div>

                                <div class="agenda-body">
                                    <h3>Kajian Rutin</h3>
                                    <p class="subtitle">Kajian Rutin</p>

                                    <div class="agenda-divider"></div>

                                    <div class="agenda-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-calendar-check text-success"></i>
                                            <span>13 Feb 2026</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-clock-fill text-warning"></i>
                                            <span>3:00 pm</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="swiper-slide">
                            <a href="detail-event-1.html" class="agenda-card">

                                <div class="agenda-img">
                                    <img src="public/assets/user/img/education/milad-1.png" alt="Highlight 1">
                                </div>

                                <div class="agenda-body">
                                    <h3>Tasyakuran Milad</h3>
                                    <p class="subtitle">Tasyakuran Milad</p>

                                    <div class="agenda-divider"></div>

                                    <div class="agenda-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-calendar-check text-success"></i>
                                            <span>13 Feb 2026</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-clock-fill text-warning"></i>
                                            <span>3:00 pm</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="swiper-slide">
                            <a href="detail-event-1.html" class="agenda-card">

                                <div class="agenda-img">
                                    <img src="public/assets/user/img/education/eduexpo-1.png" alt="Highlight 1">
                                </div>

                                <div class="agenda-body">
                                    <h3>Edu Expo Ponorogo City Center</h3>
                                    <p class="subtitle">Edu Expo Ponorogo City Center</p>

                                    <div class="agenda-divider"></div>

                                    <div class="agenda-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-calendar-check text-success"></i>
                                            <span>13 Feb 2026</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-clock-fill text-warning"></i>
                                            <span>3:00 pm</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="swiper-slide">
                            <a href="detail-event-1.html" class="agenda-card">

                                <div class="agenda-img">
                                    <img src="public/assets/user/img/education/eduexpo-2.png" alt="Highlight 1">
                                </div>

                                <div class="agenda-body">
                                    <h3>Edu Expo SMAN 1 Polanharjo</h3>
                                    <p class="subtitle">Edu Expo SMAN 1 Polanharjo</p>

                                    <div class="agenda-divider"></div>

                                    <div class="agenda-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-calendar-check text-success"></i>
                                            <span>13 Feb 2026</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-clock-fill text-warning"></i>
                                            <span>3:00 pm</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>

                    <div class="swiper-pagination"></div>

                </div>

                <div class="swiper-button-prev custom-prev"></div>
                <div class="swiper-button-next custom-next"></div>

            </div>

            <div class="text-center mt-5">
                <a href="all-events.html" class="btn-view-more">
                    Event Lainnya <i class="bi bi-arrow-right"></i>
                </a>
            </div>

        </div>
    </section><!-- /Events Section -->
    <div class="social-widget-container">

        <div class="social-list-extended">
            <a href="https://www.instagram.com/tsuniversity.official?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw=="
                target="_blank" class="social-item instagram-bg">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.tiktok.com/@tsuniversity.official?is_from_webapp=1&sender_device=pc" target="_blank"
                class="social-item tiktok-bg">
                <i class="fab fa-tiktok"></i>
            </a>
            <a href="https://www.youtube.com/@TSU_Official_25" target="_blank" class="social-item youtube-bg">
                <i class="fab fa-youtube"></i>
            </a>
        </div>

        <div class="expand-arrow">
            <i class="fas fa-chevron-up"></i>
        </div>

        <a href="https://wa.me/62895705354767" target="_blank" class="main-whatsapp-btn">
            <i class="fab fa-whatsapp"></i>
        </a>

    </div>
@endsection
@section('script')

@endsection
