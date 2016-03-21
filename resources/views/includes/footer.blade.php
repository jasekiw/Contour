<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 7/27/2015
 * Time: 10:44 AM
 */
?>

                                </div><!-- main-content-->
                            </div>
                        <!-- /main -->
                        </div>
                    <!-- /content-wrapper -->
                    </div>
                <!-- /row -->
                </div>
            <!-- /container -->
            </div>
        <!-- END BOTTOM: LEFT NAV AND RIGHT MAIN CONTENT -->
        <!-- FOOTER -->
        {!!  $copyright_html !!}
        <!-- END FOOTER -->
        </div>
        <!-- /wrapper -->


        <script src="{{ asset('assets/js/jquery/jquery-2.1.0.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap/bootstrap.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/modernizr/modernizr.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/bootstrap-tour/bootstrap-tour.custom.js') }}"></script>
        <script src="{{ asset('assets/js/king-common.js') }}"></script>
        <!--<script src="{{ asset('demo-style-switcher/assets/js/deliswitch.js') }}"></script>-->
        <script src="{{ asset('assets/js/plugins/stat/jquery.easypiechart.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/raphael/raphael-2.1.0.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/stat/flot/jquery.flot.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/stat/flot/jquery.flot.resize.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/stat/flot/jquery.flot.time.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/stat/flot/jquery.flot.pie.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/stat/flot/jquery.flot.tooltip.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/datatable/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/datatable/dataTables.bootstrap.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/jquery-mapael/jquery.mapael.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/raphael/maps/usa_states.js') }}"></script>
        <script src="{{ asset('assets/js/king-chart-stat.js') }}"></script>
        <script src="{{ asset('assets/js/king-table.js') }}"></script>
        <script src="{{ asset('assets/js/king-components.js') }}"></script>
        {!!  Theme::footer() !!}
        {!!  Theme::footer($title) !!}
        {!! Contour::getThemeManager()->footer() !!}
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
    </body>

</html>
