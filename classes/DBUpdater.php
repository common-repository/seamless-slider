<?php

namespace SeamlessSlider;

class DBUpdater {

    private $VERSION = '0.4.3';

    private $VERSION_HISTORY = array('0.1','0.2','0.2.1','0.3','0.3.1','0.3.2','0.4','0.4.1','0.4.2','0.4.3');

    static $instance = null;

    static $TABLES = array(
        'ss_sliders' => 'ss_sliders',
        'ss_slides' =>  'ss_slides',
        'ss_version' => 'ss_version'
    );

    static $createHandler = array(
        'ss_sliders' => 'CREATE TABLE IF NOT EXISTS * ( slider_id int(3) NOT NULL AUTO_INCREMENT, blog_id int(3) NOT NULL, title tinytext NOT NULL, alias tinytext, short_code tinytext, options text NOT NULL, PRIMARY KEY ( slider_id ) )',
        'ss_slides' => 'CREATE TABLE IF NOT EXISTS * ( slide_id int(3) NOT NULL AUTO_INCREMENT, parent_slider_id int(3) NOT NULL, image_url tinytext NOT NULL, alt tinytext NOT NULL, sort int(3) NOT NULL, PRIMARY KEY ( slide_id ) )',
        'ss_version' => 'CREATE TABLE IF NOT EXISTS * ( uid int(2) NOT NULL AUTO_INCREMENT, blog_id int(3) NOT NULL, version varchar(7), PRIMARY KEY ( uid ) )'
    );

    static $insertHandler = array();

    static $deleteHandler = array(
        'ss_sliders' => 'DROP TABLE IF EXISTS *',
        'ss_slides' => 'DROP TABLE IF EXISTS *',
        'ss_version' => 'DROP TABLE IF EXISTS *'
    );

    static $updateHandler = array();

    public function __construct() {}

    public static function getInstance() {

        if( self::$instance === null )
            self::$instance = new static();

        return self::$instance;

    }
    public function prefixTables() {

        array_walk( self::$TABLES,function( &$table_name ) {

            $table_name = Config::$TABLE_PREFIX . $table_name;

        });

        return $this;

    }
    public function prepare() {

        //Prepare create handler
        array_walk( self::$createHandler,function( &$query,$table_name ) {

            $query = str_replace( '*', DBUpdater::$TABLES[ $table_name ],$query );

        });

        self::$insertHandler['insert_version'] = 'INSERT INTO ' . self::$TABLES[ 'ss_version' ] . ' ( blog_id, version ) VALUES ( ' . Config::$BLOG_ID . ',\'' . $this->VERSION . '\' )';

        //Prepare delete handler
        array_walk( self::$deleteHandler,function( &$query,$table_name ) {

            $query = str_replace( '*', DBUpdater::$TABLES[ $table_name ],$query );

        });

        //Prepare update handler
        self::$updateHandler['0.3'] = function() {

            //Create version table if not exists
            PDOQB::start()->raw( DBUpdater::$createHandler['ss_version'] )->fetch();

            //Rename fs_ prefixed tables if they exist
            $fsSlider = PDOQB::start()->select('*')->from( 'INFORMATION_SCHEMA.TABLES' )
                ->where( array( 'TABLE_NAME' => Config::$TABLE_PREFIX . 'fs_sliders' ) )->fetch()->getData();
            //If fs_slider table exists it means that there is also fs_slides so rename both
            if( !empty( $fsSlider ) ) {

                PDOQB::start()->raw( 'RENAME TABLE ' . Config::$TABLE_PREFIX . 'fs_sliders
                TO ' . DBUpdater::$TABLES['ss_sliders'] . ', ' . Config::$TABLE_PREFIX . 'fs_slides
                TO ' . DBUpdater::$TABLES['ss_slides'] )->fetch();

            }

            //Add sort column to ss_slides if there is no such column
            $sortColumnData = PDOQB::start()->select('*')->from( 'INFORMATION_SCHEMA.COLUMNS' )
                ->where( array(
                    'TABLE_NAME' => DBUpdater::$TABLES['ss_slides'],
                    'COLUMN_NAME' => 'sort'
                ) )->fetch()->getData();

            if( empty( $sortColumnData ) ) {

                PDOQB::start()->raw( 'ALTER IGNORE TABLE ' . DBUpdater::$TABLES['ss_slides'] . ' ADD sort INT( 4 ) NOT NULL AFTER  alt' )->fetch();

            }

        };

        return $this;

    }
    /**
     * Handle activation process
     */
    public static function handleActivation() {

        //Create tables
        foreach( self::$createHandler as $query )
            PDOQB::start()->raw( $query )->fetch();

        foreach( self::$insertHandler as $query )
            PDOQB::start()->raw( $query )->fetch();

    }
    /**
     * Handle deletion process
     */
    public function handleDeletion() {

        //delete tables
        foreach( self::$deleteHandler as $query )
            PDOQB::start()->raw( $query )->fetch();

    }
    public function handleUpdate() {

        $version = $this->getVersion();

        $dbIndex = $version !== false ? array_search( $version, $this->VERSION_HISTORY) : 0;

        $actualIndex = array_search( $this->VERSION, $this->VERSION_HISTORY);

        if( $dbIndex !== $actualIndex ) {

            for( $i = $dbIndex + 1; $i <= $actualIndex; $i++ ) {

                $tmp_version = $this->VERSION_HISTORY[ $i ];

                if( isset( self::$updateHandler[ $tmp_version ] ) )
                    call_user_func_array( self::$updateHandler[ $tmp_version ], array() );

            }

            if( $version === false )
                PDOQB::start()->raw( self::$createHandler['insert_version'] )->fetch();
            else
                PDOQB::start()->update( self::$TABLES['ss_version'] )
                    ->set( array( 'version' => $this->VERSION ) )
                    ->where( array( 'blog_id' => Config::$BLOG_ID ) )->fetch();

        }

    }
    /** Version Control */
    public function getVersion() {

        //Check if version table exists
        $versionTable = PDOQB::start()->select('*')->from( 'INFORMATION_SCHEMA.TABLES' )
            ->where( array( 'TABLE_NAME' => self::$TABLES['ss_version'] ) )->fetch()->getData();

        if( !empty( $versionTable ) ) {

            $data = PDOQB::start()->select('*')->from( self::$TABLES['ss_version'] )->where( array(

                'blog_id' => Config::$BLOG_ID

            ))->fetch()->getData();

            return  !empty( $data ) ? $data[0]['version'] : false;

        }
        return false;

    }
    public function fsTableExists() {

        $data = PDOQB::start()->select('*')->from( 'INFORMATION_SCHEMA.TABLES' )
            ->where( array( 'TABLE_NAME' => Config::$TABLE_PREFIX . 'fs_sliders' ) )->fetch()->getData();

        return !empty( $data );

    }
    public function sortColumnExists() {

        $data = PDOQB::start()->select('*')->from( $this->table_adapter['ss_slides'] )
            ->limit(1,1)->fetch()->getData();

        return empty( $data ) || isset( $data[ 0 ]['sort'] );

    }

}