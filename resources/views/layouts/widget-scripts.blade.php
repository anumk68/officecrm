<!--! ================================================================ -->
<!--! Footer Script -->
<!--! ================================================================ -->

<!--! BEGIN: Vendors JS -->
<script src="{{ asset('admin/vendors/js/vendors.min.js') }}"></script>
<script src="{{ asset('admin/vendors/js/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin/vendors/js/circle-progress.min.js') }}"></script>
<!--! END: Vendors JS -->

<!--! BEGIN: Apps Init -->
<script src="{{ asset('admin/js/common-init.min.js') }}"></script>
<script src="{{ asset('admin/js/widgets-charts-init.min.js') }}"></script>
<!--! END: Apps Init -->

<!--! BEGIN: Theme Customizer -->
<script src="{{ asset('admin/js/theme-customizer-init.min.js') }}"></script>
<!--! END: Theme Customizer -->

{!! $script ?? '' !!}
