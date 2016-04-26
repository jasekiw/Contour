<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/4/2015
 * Time: 10:28 AM
 */
?>

                <div class="links">
                    <p><a href="#">Forgot Username or Password?</a></p>

                </div>
            </div>
        </div>
    </div>

    @if(isset($copyright_html)) {!!$copyright_html!!} @endif

    <!-- Javascript -->
    <script src="{!! asset('theme/js/jquery/jquery-2.1.0.min.js') !!}"></script>
    <script src="{!! asset('theme/js/bootstrap/bootstrap.js') !!}"></script>
    <script src="{!! asset('theme/js/plugins/modernizr/modernizr.js') !!}"></script>

</body>

</html>