<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 7/27/2015
 * Time: 10:44 AM
 */
?>

        {!!  $copyright_html !!}
        <!-- END FOOTER -->



        <script src="{{ asset('theme/js/jquery/jquery-2.1.0.min.js') }}"></script>
        <script src="{{ asset('theme/js/bootstrap/bootstrap.js') }}"></script>
        <script src="{{ asset('theme/js/plugins/modernizr/modernizr.js') }}"></script>
        <script src="{{ asset('theme/js/plugins/bootstrap-tour/bootstrap-tour.custom.js') }}"></script>
        <script src="{{ asset('theme/js/king-common.js') }}"></script>
    
        <script src="{{ asset('theme/js/plugins/stat/jquery.easypiechart.min.js') }}"></script>
        <script src="{{ asset('theme/js/plugins/raphael/raphael-2.1.0.min.js') }}"></script>
        <script src="{{ asset('theme/js/plugins/stat/flot/jquery.flot.min.js') }}"></script>
        <script src="{{ asset('theme/js/plugins/stat/flot/jquery.flot.resize.min.js') }}"></script>
        <script src="{{ asset('theme/js/plugins/stat/flot/jquery.flot.time.min.js') }}"></script>
        <script src="{{ asset('theme/js/plugins/stat/flot/jquery.flot.pie.min.js') }}"></script>
        <script src="{{ asset('theme/js/plugins/stat/flot/jquery.flot.tooltip.min.js') }}"></script>
        <script src="{{ asset('theme/js/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
        <script src="{{ asset('theme/js/plugins/datatable/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('theme/js/plugins/datatable/dataTables.bootstrap.js') }}"></script>
        <script src="{{ asset('theme/js/plugins/jquery-mapael/jquery.mapael.js') }}"></script>
        <script src="{{ asset('theme/js/plugins/raphael/maps/usa_states.js') }}"></script>
        <script src="{{ asset('theme/js/king-chart-stat.js') }}"></script>
        <script src="{{ asset('theme/js/king-table.js') }}"></script>
        <script src="{{ asset('theme/js/king-components.js') }}"></script>
        {!!  \app\libraries\theme\Theme::footer() !!}
        {!!  \app\libraries\theme\Theme::footer($title) !!}
        {!! \app\libraries\contour\Contour::getThemeManager()->footer() !!}
                @yield('scripts')
        @if( Session::has('message') || Session::has('message_title'))

            <script type="text/javascript">
                $.gritter.add({
                    title: "{{ Session::get('message_title')}}",
                    text: "{{Session::get('message')}}",
                    class_name: "{{ Session::get('message_type')}}"

                });
            </script>
        @endif


