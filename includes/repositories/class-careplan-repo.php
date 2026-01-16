<?php
defined('ABSPATH') || exit;

/**
 * Base repository class for CarePlan
 *
 * Handles CRUD operations safely using $wpdb.
 */
abstract class CarePlan_Repo {

    /** @var string Table name */
    protected $table;

    /** @var wpdb WordPress database object */
    protected $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;

        if ( empty( $this->table ) ) {
            wp_die( __( 'Table not defined in repository.', 'careplan' ) );
        }
    }

    /**
     * Insert a record safely
     *
     * @param array $data Associative array of column => value
     * @param array $format Optional array of formats (%s, %d)
     * @return int|false Insert ID or false on failure
     */
    public function insert( $data, $format = [] ) {
        return $this->wpdb->insert( $this->table, $data, $format );
    }

    /**
     * Update a record safely
     *
     * @param array $data Data to update
     * @param array $where Conditions
     * @param array $format Optional data formats
     * @param array $where_format Optional where formats
     * @return int|false Number of rows updated or false
     */
    public function update( $data, $where, $format = [], $where_format = [] ) {
        return $this->wpdb->update( $this->table, $data, $where, $format, $where_format );
    }

    /**
     * Delete a record safely
     *
     * @param array $where Conditions
     * @param array $where_format Optional formats
     * @return int|false Number of rows deleted or false
     */
    public function delete( $where, $where_format = [] ) {
        return $this->wpdb->delete( $this->table, $where, $where_format );
    }

    /**
     * Get a single record
     *
     * @param array $where Optional conditions
     * @param string $output OBJECT|ARRAY_A|ARRAY_N
     * @param int $limit Number of rows to return
     * @return object|array|null
     */
    public function get( $where = [], $output = OBJECT, $limit = 1 ) {
        $sql = "SELECT * FROM `{$this->table}`";

        if ( $where ) {
            $conditions = [];
            $values = [];
            foreach ( $where as $key => $value ) {
                $conditions[] = "`" . esc_sql( $key ) . "` = %s";
                $values[] = $value;
            }
            $sql .= " WHERE " . implode( " AND ", $conditions );
            $sql = $this->wpdb->prepare( $sql, ...$values );
        }

        if ( $limit ) {
            $sql .= " LIMIT %d";
            $sql = $this->wpdb->prepare( $sql, $limit );
        }

        return $this->wpdb->get_row( $sql, $output );
    }

    /**
     * Get multiple records
     *
     * @param array $where Optional conditions
     * @param string $output OBJECT|ARRAY_A|ARRAY_N
     * @return array
     */
    public function get_all( $where = [], $output = OBJECT ) {
        $sql = "SELECT * FROM `{$this->table}`";

        if ( $where ) {
            $conditions = [];
            $values = [];
            foreach ( $where as $key => $value ) {
                $conditions[] = "`" . esc_sql( $key ) . "` = %s";
                $values[] = $value;
            }
            $sql .= " WHERE " . implode( " AND ", $conditions );
            $sql = $this->wpdb->prepare( $sql, ...$values );
        }

        return $this->wpdb->get_results( $sql, $output );
    }
}
