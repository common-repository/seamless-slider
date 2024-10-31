<script>
    angular.module('faster-slider', ['ngRoute','colorpicker.module','ui']);
    angular.module('faster-slider').constant('Config',JSON.parse( '<?=SeamlessSlider\Config::$ADMIN['JSON']?>' ) );
</script>

<div class="faster-slider clearfix" ng-app="faster-slider" ng-controller="IndexController as _ic">

    <div class="wrap">

        <h1>Seamless Sliders<a href="#add_slider" class="page-title-action">Add New</a></h1>

        <ul class="subsubsub">
            <li class="all"><a ng-class="{ 'current' : _ic.isActiveMenu('/') }" href="#/">All <span class="count">({{ slidersLength }})</span></a> |</li>
            <li class="publish"><a ng-class="{ 'current' : _ic.isActiveMenu('/howtouse') }" href="#/howtouse">How To Use</a></li>
            <li>
                <route-loading-indicator></route-loading-indicator>
            </li>
        </ul>

        <div class="clear"></div>

        <section ng-show="!$root.isRouteLoading" ng-view=""></section>

        <div class="clear"></div>

        <footer>

            <div class="row">
                <div class="col-lg-12">
                    <p>by Guramidev</p>
                </div>
            </div>

        </footer>

    </div>

</div>