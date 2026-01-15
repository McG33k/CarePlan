<?php
defined('ABSPATH') || exit;

/**
 * Base repository class for CarePlan
 */
abstract class CarePlan_Repo {

    protected $table;
    protected $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    /**
     * Insert a record safely
     */
    public function insert($data, $format = []) {
        return $this->wpdb->insert($this->table, $data, $format);
    }

    /**
     * Update a record safely
     */
    public function update($data, $where, $format = [], $where_format = []) {
        return $this->wpdb->update($this->table, $data, $where, $format, $where_format);
    }

    /**
     * Delete a record safely
     */
    public function delete($where, $where_format = []) {
        return $this->wpdb->delete($this->table, $where, $where_format);
    }

    /**
     * Get a single record
     */
    public function get($where = [], $output = OBJECT, $limit = 1) {
        $sql = "SELECT * FROM {$this->table}";
        if ($where) {
            $conditions = [];
            $values = [];
            foreach ($where as $key => $value) {
                $conditions[] = "`$key` = %s";
                $values[] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $conditions);
            $sql = $this->wpdb->prepare($sql, ...$values);
        }
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        return $this->wpdb->get_row($sql, $output);
    }

    /**
     * Get multiple records
     */
    public function get_all($where = [], $output = OBJECT) {
        $sql = "SELECT * FROM {$this->table}";
        if ($where) {
            $conditions = [];
            $values = [];
            foreach ($where as $key => $value) {
                $conditions[] = "`$key` = %s";
                $values[] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $conditions);
            $sql = $this->wpdb->prepare($sql, ...$values);
        }
        return $this->wpdb->get_results($sql, $output);
    }
}
