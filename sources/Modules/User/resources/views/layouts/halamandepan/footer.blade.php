<footer id="footer" class="footer position-relative light-background">

    <div class="container footer-top">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-3 footer-links">
                <h4>Link Cepat</h4><hr>
                <ul>
                    <li><a href="#">Isian Khusus Perekomendasi</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-3 footer-links">
                <h4>Informasi Pendaftaran</h4><hr>
                <ul>
                    <li><a href="#">Informasi Pendaftaran Mahasiswa Baru {{date('Y')}} Universitas Tiga Serangkai</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6 footer-about">
                <h4>Universitas Tiga Serangkai</h4>
                <div class="footer-contact pt-3">
                    <p>
                        <a href="https://maps.app.goo.gl/6yTHL2ePncTFF4ot8" target="_blank">
                            <i class="bi bi-geo-alt-fill"></i> Jl. K.H Samanhudi No.84-86, Purwosari, Kec. Laweyan, Kota Surakarta, Jawa Tengah 57149
                        </a>
                    </p>
                    <p><i class="bi bi-telephone-fill"></i> 0271-716500</p>
                    <p><i class="bi bi-envelope-fill"></i> pmb@tsu.ac.id</p>
                    <p>
                        <a href="https://wa.me/62895705354767?text=Saya tanya terkait pendaftaran" target="_blank">
                            <i class="bi bi-whatsapp"></i> 0895705354767
                        </a>
                    </p>
                    </a>

                    <p>
                        <a href="https://www.instagram.com/tsuniversity.official?igsh=cXk2enh2NDNhaHl0" target="_blank">
                            <i class="bi bi-instagram"></i> tsuniversity.official
                        </a>
                    </p>
                </div>
                <div class="social-links d-flex mt-4 d-none">
                    <a href=""><i class="bi bi-twitter-x"></i></a>
                    <a href=""><i class="bi bi-facebook"></i></a>
                    <a href=""><i class="bi bi-instagram"></i></a>
                    <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
            </div>

        </div>
    </div>

    <div class="container copyright text-center mt-4">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">{{date('Y')}}</strong> <span>Universitas Tiga Serangkai</span></p>
        <div class="credits d-none">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you've purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </div>

</footer>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>
<!-- Preloader -->
<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="{{ asset('public/assets/user/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('public/assets/user/vendor/php-email-form/validate.js') }}"></script>
<script src="{{ asset('public/assets/user/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('public/assets/user/vendor/purecounter/purecounter_vanilla.js') }}"></script>
<script src="{{ asset('public/assets/user/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('public/assets/user/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('public/assets/user/vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('public/assets/user/vendor/glightbox/js/glightbox.min.js') }}"></script>
{{-- alert --}}
<script src="{{ asset('public/assets/admin/dist/js/sweetalert.js') }}"></script>

<!-- Main JS File -->
<script src="{{ asset('public/assets/user/js/main.js') }}"></script>

<script></script>
@yield('script')
