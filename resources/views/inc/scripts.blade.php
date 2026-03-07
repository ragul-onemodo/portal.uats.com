<!-- jQuery library -->
<script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>

<!-- Bootstrap -->
<script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>

<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<!-- Data Table -->
<script src="{{ asset('assets/js/lib/dataTables.min.js') }}"></script>

<!-- Iconify -->
<script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>

<!-- jQuery UI -->
<script src="{{ asset('assets/js/lib/jquery-ui.min.js') }}"></script>

<!-- Vector Map -->
<script src="{{ asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>

<!-- Popup -->
<script src="{{ asset('assets/js/lib/magnifc-popup.min.js') }}"></script>

<!-- Slick Slider -->
<script src="{{ asset('assets/js/lib/slick.min.js') }}"></script>

<!-- Prism -->
<script src="{{ asset('assets/js/lib/prism.js') }}"></script>

<!-- File Upload -->
<script src="{{ asset('assets/js/lib/file-upload.js') }}"></script>

<!-- Audio Player -->
<script src="{{ asset('assets/js/lib/audioplayer.js') }}"></script>

<!-- Main App JS -->
<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- Page Specific Chart -->
<script src="{{ asset('assets/js/homeOneChart.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
    integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- CUSTOM Scripts --}}
<script src="{{ asset('assets/js/custom/modal.js') }}"></script>
{{-- <script src="{{ asset('assets/js/custom/datatable-global.js') }}"></script> --}}





{{-- Script Stack --}}
@stack('scripts')
