<?php
/**
 * Shared upload checks for OWI Helpdesk upload handlers.
 * Compatible with the application's PHP 7.2 runtime.
 */

if (!function_exists('owi_upload_session_ok')) {
    function owi_upload_session_ok()
    {
        if (session_status() === PHP_SESSION_NONE && !@session_start()) {
            return false;
        }

        return isset($_SESSION['login'], $_SESSION['user_id'])
            && $_SESSION['login'] === 'true'
            && is_scalar($_SESSION['user_id'])
            && trim((string) $_SESSION['user_id']) !== '';
    }
}

if (!function_exists('owi_upload_request_ok')) {
    function owi_upload_request_ok()
    {
        return owi_upload_session_ok()
            && isset($_SERVER['REQUEST_METHOD'])
            && strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
    }
}

if (!function_exists('owi_upload_fail')) {
    function owi_upload_fail($statusCode)
    {
        if (!headers_sent()) {
            http_response_code((int) $statusCode);
        }
        exit;
    }
}

if (!function_exists('owi_upload_file_entries')) {
    function owi_upload_file_entries($files)
    {
        if (!is_array($files)
            || !isset($files['name'], $files['type'], $files['tmp_name'], $files['error'], $files['size'])) {
            return false;
        }

        $entries = array();
        $isMultiple = is_array($files['name']);
        $count = $isMultiple ? count($files['name']) : 1;
        if ($count < 1) {
            return false;
        }

        for ($i = 0; $i < $count; $i++) {
            $name = $isMultiple ? (isset($files['name'][$i]) ? $files['name'][$i] : null) : $files['name'];
            $tmp = $isMultiple ? (isset($files['tmp_name'][$i]) ? $files['tmp_name'][$i] : null) : $files['tmp_name'];
            $error = $isMultiple ? (isset($files['error'][$i]) ? $files['error'][$i] : null) : $files['error'];
            $size = $isMultiple ? (isset($files['size'][$i]) ? $files['size'][$i] : null) : $files['size'];

            if (!is_string($name) || !is_string($tmp) || !is_int($error) || !is_numeric($size)) {
                return false;
            }
            if ($error !== UPLOAD_ERR_OK) {
                return false;
            }

            $entries[] = array(
                'name' => $name,
                'tmp_name' => $tmp,
                'error' => $error,
                'size' => (int) $size
            );
        }

        return $entries;
    }
}

if (!function_exists('owi_upload_field_has_file')) {
    function owi_upload_field_has_file($files)
    {
        if (!is_array($files) || !isset($files['name'], $files['error'])) {
            return true;
        }

        $names = is_array($files['name']) ? $files['name'] : array($files['name']);
        $errors = is_array($files['error']) ? $files['error'] : array($files['error']);
        foreach ($names as $index => $name) {
            $error = isset($errors[$index]) ? $errors[$index] : null;
            if ($error !== UPLOAD_ERR_NO_FILE || (is_string($name) && $name !== '')) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('owi_upload_validate_first')) {
    function owi_upload_validate_first($files)
    {
        $entries = owi_upload_file_entries($files);
        if ($entries === false || !isset($entries[0])) {
            return false;
        }
        return owi_upload_validate_entry($entries[0]);
    }
}

if (!function_exists('owi_upload_validate_entry')) {
    function owi_upload_validate_entry($entry)
    {
        if (!is_array($entry)
            || !isset($entry['name'], $entry['tmp_name'], $entry['error'], $entry['size'])
            || $entry['error'] !== UPLOAD_ERR_OK
            || !is_uploaded_file($entry['tmp_name'])) {
            return false;
        }

        $name = $entry['name'];
        $baseName = basename($name);
        if ($name === '' || $baseName === '' || $baseName !== $name
            || strpos($name, '/') !== false || strpos($name, '\\') !== false
            || strpos($name, "\0") !== false || strpos($name, '..') !== false
            || preg_match('/[\x00-\x1F\x7F]/', $name)) {
            return false;
        }

        $actualSize = @filesize($entry['tmp_name']);
        if ($entry['size'] < 1 || $entry['size'] > 5 * 1024 * 1024
            || $actualSize === false || $actualSize > 5 * 1024 * 1024) {
            return false;
        }

        $extension = strtolower(pathinfo($baseName, PATHINFO_EXTENSION));
        $mimeByExtension = array(
            'jpg' => array('image/jpeg'),
            'jpeg' => array('image/jpeg'),
            'png' => array('image/png'),
            'gif' => array('image/gif'),
            'pdf' => array('application/pdf'),
            'doc' => array('application/msword', 'application/x-ole-storage'),
            'xls' => array('application/vnd.ms-excel', 'application/x-ole-storage'),
            'docx' => array(
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/zip'
            ),
            'xlsx' => array(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/zip'
            )
        );

        if (!isset($mimeByExtension[$extension]) || !class_exists('finfo')) {
            return false;
        }

        try {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($entry['tmp_name']);
        } catch (Throwable $e) {
            return false;
        }

        if (!is_string($mime) || !in_array($mime, $mimeByExtension[$extension], true)) {
            return false;
        }

        if ($extension === 'docx' || $extension === 'xlsx') {
            if (!class_exists('ZipArchive')) {
                return false;
            }

            $zip = new ZipArchive();
            if ($zip->open($entry['tmp_name']) !== true) {
                return false;
            }

            $types = $zip->getFromName('[Content_Types].xml');
            if (!is_string($types) || $types === '') {
                $zip->close();
                return false;
            }

            if ($extension === 'docx') {
                $validStructure = $zip->locateName('word/document.xml') !== false
                    && strpos($types, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml') !== false;
            } else {
                $validStructure = $zip->locateName('xl/workbook.xml') !== false
                    && strpos($types, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml') !== false;
            }
            $zip->close();

            if (!$validStructure) {
                return false;
            }
        }

        return array(
            'name' => $baseName,
            'extension' => $extension,
            'tmp_name' => $entry['tmp_name'],
            'size' => $entry['size'],
            'mime' => $mime
        );
    }
}

if (!function_exists('owi_upload_validate_collection')) {
    function owi_upload_validate_collection($files)
    {
        $entries = owi_upload_file_entries($files);
        if ($entries === false) {
            return false;
        }

        $validated = array();
        foreach ($entries as $entry) {
            $file = owi_upload_validate_entry($entry);
            if ($file === false) {
                return false;
            }
            $validated[] = $file;
        }

        return $validated;
    }
}
