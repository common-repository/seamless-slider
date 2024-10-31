<script>

    angular.module('faster-slider', ['ngRoute']);
    angular.module('faster-slider').constant('Config', JSON.parse( '<?=str_replace('\\\\','/', json_encode( array_merge( SeamlessSlider\Config::$ADMIN_CONFIG, array( 'debug' => SeamlessSlider\Config::$MODE === 'dev','cache' => SeamlessSlider\Config::$CACHE,'ajax_call_config' => SeamlessSlider\Config::$AJAX_CALL_CONFIG ) ) ) )?>' ) );

</script>

<div ng-app="faster-slider" ng-controller="IndexController as _ic">

    <h1>Faster Slider</h1>

    <nav class="navbar">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#/">Manage Sliders</a></li>
        </ul>
    </nav>
    <!-- Jumbotron -->
    <div class="row">
        <route-loading-indicator></route-loading-indicator>
        <div ng-show="!$root.isRouteLoading" class="col-lg-12" ng-view=""></div>
    </div>
    <!-- Site footer -->
    <footer>
        <p>by                     <a href="#">Someone</a></p>
    </footer>
</div>