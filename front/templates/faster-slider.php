<?php SeamlessSlider\Front::addCSS(); ?>

<div id="ss-<?=SeamlessSlider\Config::$FRONT['args']['alias']?>" class="ss-oh init">
    <div class="ss-ih">
        <div class="ss-iw"></div>
    </div>
</div>

<script type="text/javascript">

    <?php SeamlessSlider\Front::addJS(); ?>

    _SS.init( '<?=SeamlessSlider\Config::$FRONT['JSON']?>','<?=json_encode( SeamlessSlider\Config::$FRONT['args'] )?>' );

</script>


