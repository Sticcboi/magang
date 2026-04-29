<?php
if (!function_exists('ensure_kelembagaan_tables')) {
    function ensure_kelembagaan_tables($conn)
    {
        $queries = [
            "CREATE TABLE IF NOT EXISTS kelembagaan_pages (
                id INT(11) NOT NULL AUTO_INCREMENT,
                page VARCHAR(50) NOT NULL,
                overview TEXT DEFAULT NULL,
                visi TEXT DEFAULT NULL,
                misi TEXT DEFAULT NULL,
                legal_basis TEXT DEFAULT NULL,
                work_area TEXT DEFAULT NULL,
                notes TEXT DEFAULT NULL,
                updated_at TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                PRIMARY KEY(id),
                UNIQUE KEY(page)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

            "CREATE TABLE IF NOT EXISTS kelembagaan_staff (
                id INT(11) NOT NULL AUTO_INCREMENT,
                page VARCHAR(50) NOT NULL,
                name VARCHAR(150) NOT NULL,
                role VARCHAR(150) DEFAULT NULL,
                contact VARCHAR(100) DEFAULT NULL,
                order_no INT(11) DEFAULT 0,
                created_at TIMESTAMP NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

            "CREATE TABLE IF NOT EXISTS kelembagaan_units (
                id INT(11) NOT NULL AUTO_INCREMENT,
                page VARCHAR(50) NOT NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT DEFAULT NULL,
                order_no INT(11) DEFAULT 0,
                created_at TIMESTAMP NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

            "CREATE TABLE IF NOT EXISTS kelembagaan_programs (
                id INT(11) NOT NULL AUTO_INCREMENT,
                page VARCHAR(50) NOT NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT DEFAULT NULL,
                order_no INT(11) DEFAULT 0,
                created_at TIMESTAMP NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
        ];

        foreach ($queries as $query) {
            mysqli_query($conn, $query);
        }
    }

    function get_kelembagaan_page($conn, $page)
    {
        $page = mysqli_real_escape_string($conn, $page);
        $result = mysqli_query($conn, "SELECT * FROM kelembagaan_pages WHERE page = '$page' LIMIT 1");
        if ($result && mysqli_num_rows($result)) {
            return mysqli_fetch_assoc($result);
        }
        return [
            'page' => $page,
            'overview' => null,
            'visi' => null,
            'misi' => null,
            'legal_basis' => null,
            'work_area' => null,
            'notes' => null,
        ];
    }

    function get_kelembagaan_staff($conn, $page)
    {
        $page = mysqli_real_escape_string($conn, $page);
        $rows = [];
        $result = mysqli_query($conn, "SELECT * FROM kelembagaan_staff WHERE page = '$page' ORDER BY order_no ASC, id ASC");
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    function get_kelembagaan_units($conn, $page)
    {
        $page = mysqli_real_escape_string($conn, $page);
        $rows = [];
        $result = mysqli_query($conn, "SELECT * FROM kelembagaan_units WHERE page = '$page' ORDER BY order_no ASC, id ASC");
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    function get_kelembagaan_programs($conn, $page)
    {
        $page = mysqli_real_escape_string($conn, $page);
        $rows = [];
        $result = mysqli_query($conn, "SELECT * FROM kelembagaan_programs WHERE page = '$page' ORDER BY order_no ASC, id ASC");
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
}
