<?php

namespace SeamlessSlider;

class PDOQB
{
    static private $instance = null;

    private $config = array(
        'DB_HOST' => DB_HOST,
        'DB_NAME' => DB_NAME,
        'DB_USER' => DB_USER,
        'DB_PASS' => DB_PASSWORD,
        'DB_PORT' => 3306
    );
    private $conn;

    private $call;
    private $query;
    private $executable;
    private $conditionalStatements;
    private $conditionalStates;
    private $conditionalExecutables;
    private $conditional;
    private $conditions;
    private $state;
    private $return;

    private function __construct() { }
    /**
     *
     * Sets start values of all the needed arguments
     *
     */
    private function getInitialState()
    {
        $this->call = -1;
        $this->query = array();
        $this->executable = array();
        $this->conditionalStatements = array();
        $this->conditionalStates = array();
        $this->conditionalExecutables = array();
        $this->conditional = false;
        $this->conditions = array();
        $this->state = array();
        $this->return = array(
            'success' => true
        );
    }
    /**
     *
     * State controllers, used to defined whether requested query should include SQL keywords
     * like SELECT / WHERE etc
     *
     */
    private function setState( $type )
    {
        if( $this->conditional ) {

            $this->conditionalStates[ $this->call ] = isset( $this->conditionalStates[ $this->call ] ) ? $this->conditionalStates[ $this->call ] : array();

            $this->conditionalStates[ $this->call ][ $type ] = true;

        }
        else {

            if( !isset($this->state[ $this->call ]) ) {
                $this->state[ $this->call ] = array();
            }
            $this->state[ $this->call ][ $type ] = true;

        }

    }
    private function checkState( $type )
    {
        return isset( $this->state[ $this->call ] ) && isset( $this->state[ $this->call ][ $type ] ) && $this->state[ $this->call ][ $type ];
    }
    /**
     *
     * Conditional handlers, currently only (if/endif) is implemented
     *
     */
    public function _if( $condition )
    {
        $this->conditional = true;

        $this->conditions[ $this->call ] = $condition;

        return $this;
    }
    public function _endif()
    {
        $this->conditional = false;

        if ( $this->conditions[ $this->call ] ) {

            $this->query[ $this->call ] .= $this->conditionalStatements[ $this->call ];

            if( isset( $this->conditionalStates[ $this->call ] ) && !empty( $this->conditionalStates[ $this->call ] ) ) {

                foreach( $this->conditionalStates[ $this->call ] as $key => $type ) {

                    $this->setState( $key );

                }

            }
            if( isset( $this->conditionalExecutables[ $this->call ] ) && !empty( $this->conditionalExecutables[ $this->call ] ) ) {

                $this->executable = array_merge( $this->executable,$this->conditionalExecutables[ $this->call ] );

            }

        }

        $this->conditionalStatements[ $this->call ] = '';
        $this->conditionalStates[ $this->call ] = array();
        $this->conditionalExecutables[ $this->call ] = array();

        return $this;
    }
    /**
     *
     * Used to start building any query. Needed for implementing sub-query support
     *
     */
    public static function start()
    {
        if( !self::$instance )
            self::$instance = new static();


        if( !self::$instance->conn ) {

            self::$instance->getConnection();
            self::$instance->getInitialState();

        }


        self::$instance->call++;
        self::$instance->query[ self::$instance->call ] = '';
        self::$instance->conditionalStatements[ self::$instance->call ] = '';

        return self::$instance;
    }
    /**
     *
     * Query constructors
     *
     */
    public function select ( $n )
    {
        if( $this->checkState( 'select' ) ) {

            $this->addToQuery( ', ' . $n );

        }
        else {

            $this->addToQuery( 'SELECT ' . $n );

        }

        $this->setState('select');

        return $this;
    }
    public function delete ()
    {
        $this->addToQuery( 'DELETE' );

        return $this;
    }
    public function update ( $n )
    {
        $this->addToQuery( 'UPDATE ' . $n );

        return $this;
    }
    public function insertInto ( $n )
    {
        $this->addToQuery( 'INSERT INTO ' . $n );

        $this->setState('insert');

        return $this;
    }
    public function from ( $n )
    {
        $this->addToQuery( ' FROM ' . $n );

        return $this;
    }
    public function where( $n )
    {
        if( !empty($n) ) {

            $r = $this->valueParser( $n );

            if( $this->checkState( 'where' ) ) {

                $this->addToQuery( ' AND ' . implode( ' AND ',$r ) );

            }
            else {

                $this->addToQuery( ' WHERE ' . implode( ' AND ',$r ) );

            }

            $this->setState('where');

        }
        return $this;
    }
    public function notNull( $n )
    {
        if( $this->checkState( 'where' ) ) {

            $this->addToQuery( ' AND ' . $n . ' IS NOT NULL' );

        }
        else {

            $this->addToQuery( ' WHERE ' . $n . ' IS NOT NULL' );

        }

        $this->setState('where');

        return $this;
    }
    public function values ( $n )
    {
        if( !empty( $n ) ) {

            $c = $v = array();
            foreach( $n as $c_n => $r_v ) {
                $c[] = $c_n;
                $v[] = ':' . $c_n;
                $this->executable[':' . $c_n] = trim( $r_v );
            }

            $this->addToQuery( ' ( ' . implode( ' , ',$c ) . ' ) VALUES ( ' . implode( ' , ',$v ) . ' )' );

        }

        return $this;
    }
    public function set( $n )
    {
        if( !empty($n) ) {

            $r = $this->valueParser( $n );

            $this->addToQuery( ' SET ' . implode( ' , ',$r ) );

        }
        return $this;
    }
    public function join( $table )
    {
        return $this->joinMaker( 'JOIN',$table );
    }
    public function rightJoin( $table )
    {
        return $this->joinMaker( 'RIGHT JOIN',$table );
    }
    public function leftJoin( $table )
    {
        return $this->joinMaker( 'LEFT JOIN',$table );
    }
    public function innerJoin( $table )
    {
        return $this->joinMaker( 'INNER JOIN',$table );
    }
    public function raw( $n )
    {
        $this->addToQuery( $n );

        $this->setState('raw');

        return $this;
    }
    public function on( $n )
    {
        if( !empty($n) ) {

            $r = $this->valueParser( $n,true );

            $this->addToQuery( ' ON ' . implode( ' AND ',$r ) );

        }
        return $this;
    }
    public function groupBy( $n )
    {
        $v = is_array($n) ? implode( ' AND ',$n ) : $n;

        $this->addToQuery( ' GROUP BY ' . $v );

        return $this;
    }
    public function orderBy( $n , $type = 'ASC' )
    {
        $v = is_array($n) ? implode( ' , ',$n ) : $n;

        $this->addToQuery( ' ORDER BY ' . $v . ' ' . $type);

        return $this;
    }
    public function limit( $p,$pp )
    {
        $offset = ($p - 1) * $pp;

        $this->addToQuery( ' LIMIT ' . $offset . ',' . $pp );

        return $this;
    }
    public function _as ( $n )
    {
        $this->addToQuery( ' AS ' . $n );

        return $this;
    }
    /**
     *
     * Helper functions
     *
     */
    private function addToQuery( $statement )
    {
        if( $this->conditional ) {

            $this->conditionalStatements[ $this->call ] .= $statement;

        }
        else {

            $this->query[ $this->call ] .= $statement;

        }
    }
    private function addToExecutable( $c_n,$r_v )
    {
        if( $this->conditional ) {

            $this->conditionalExecutables[ $this->call ] = isset( $this->conditionalExecutables[ $this->call ] ) ? $this->conditionalExecutables[ $this->call ] : array();

            $this->conditionalExecutables[ $this->call ][':'.$c_n] = trim( $r_v );

        }
        else {

            $this->executable[':'.$c_n] = trim( $r_v );

        }
    }
    private function joinMaker( $type,$table )
    {
        $this->addToQuery( ' ' . $type . ' ' . $table );

        return $this;
    }
    private function valueParser( $n,$t = false )
    {
        $r = array();

        foreach( $n as $c_n => $r_v ) {

            if( is_array($r_v) ) {

                $r[] = $c_n .' IN ( \'' . implode( '\' , \'',$r_v ) . '\' )';

            }
            else {

                if( $t ) {

                    $r[] = $c_n . ' = ' . trim( $r_v );

                }
                else {

                    $fixed_c_n = str_replace('.','',$c_n);

                    $r[] = $c_n . ' = :' . $fixed_c_n;

                    $this->addToExecutable( $fixed_c_n,$r_v );

                }

            }

        }

        return $r;
    }
    /**
     *
     * Execution functions
     *
     */
    public function get()
    {
        $r = $this->call > 0 ? '( ' . trim( $this->query[ $this->call ] ) . ' )' : trim( $this->query[ $this->call ] );

        $this->query[ $this->call ] = '';

        $this->call--;

        return $r;
    }
    public function fetch()
    {
        $this->return['query'] = $this->query[ $this->call ];
        $this->return['executable'] = $this->executable;

        try {
            $r = $this->conn->prepare( $this->query[ $this->call ] );

            $r->execute( $this->executable );

            if( isset( $this->state[ $this->call ][ 'select' ] ) ) {

                $this->return['data'] = $r->fetchAll();

            }
            if( isset( $this->state[ $this->call ][ 'insert' ] ) ) {

                $this->return['data'] = $this->conn->lastInsertId();

            }
            $this->call--;
        }
        catch( PDOException $e ) {
            $this->return['success'] = false;
            $this->return['reason'] = $e->getMessage();
        }

        $this->conn = null;

        return $this;
    }
    /**
     *
     * For connection purposes
     *
     */
    public function getConnection() {
        if (!$this->conn)
            $this->PdoConnection();
        return $this->conn;
    }
    private function PdoConnection()
    {
        try {
            $this->conn = new \PDO('mysql:host=' . $this->config['DB_HOST'] .
                ';port=' . $this->config['DB_PORT'] .
                ';dbname=' . $this->config['DB_NAME'],
                $this->config['DB_USER'], $this->config['DB_PASS'],
                array( \PDO::ATTR_PERSISTENT => false));
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
            die();
        }

        $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
        $this->conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        set_time_limit(500000);

        $this->conn->exec('SET CHARACTER SET ' . DB_CHARSET);
    }
    public function getData()
    {
        $data = $this->return['data'];

        $this->getInitialState();

        return $data;
    }
    public function getReturn()
    {
        $data = $this->return;

        $this->getInitialState();

        return $data;
    }
    public function error() {

        return !$this->return['success'];

    }
    public function emptyData() {

        return empty( $this->return['data'] );

    }
}

