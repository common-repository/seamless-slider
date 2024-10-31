<?php

$api = new SeamlessSlider\API( $_SERVER['REQUEST_METHOD'] );

if( $api->superRequest() && $api->requestAllowed() ) {

    echo json_encode( $api->returnData() );

    exit();

}
