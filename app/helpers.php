<?php

if (!function_exists('getBase64Image')) {
    function getBase64Image($path)
    {
        if (!$path) {
            return null;
        }
        $fullPath = storage_path('app/public/' . $path);
        if (file_exists($fullPath)) {
            $type = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            $data = file_get_contents($fullPath);
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml',
                'webp' => 'image/webp',
                'avif' => 'image/avif',
            ];
            $mime = $mimeTypes[$type] ?? 'application/octet-stream';
            return 'data:' . $mime . ';base64,' . base64_encode($data);
        }
        return null;
    }
}
