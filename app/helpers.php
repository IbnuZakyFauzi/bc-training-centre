<?php

use App\Services\PhaseService;

if (!function_exists('format_phase_label')) {
    function format_phase_label(?string $phase, ?string $certification = null): string
    {
        if (!$phase) {
            return '-';
        }

        if ($certification && class_exists(PhaseService::class)) {
            $meta = PhaseService::meta($certification, $phase);
            if ($meta && !empty($meta['label'])) {
                return $meta['label'];
            }
        }

        if (preg_match('/^evaluasi_(\d+)$/', $phase, $matches)) {
            return 'Evaluasi ' . $matches[1];
        }

        return ucfirst(str_replace('_', ' ', $phase));
    }
}

if (!function_exists('app_storage_url')) {
    function app_storage_url(?string $path): string
    {
        if (empty($path)) {
            return '';
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
            return $path;
        }
        $cleanPath = ltrim($path, '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }
        return route('media.serve', ['path' => $cleanPath]);
    }
}

if (!function_exists('app_storage_base64')) {
    function app_storage_base64(?string $path): string
    {
        if (empty($path)) {
            return '';
        }
        if (str_starts_with($path, 'data:')) {
            return $path;
        }
        $cleanPath = ltrim($path, '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($cleanPath)) {
            $content = \Illuminate\Support\Facades\Storage::disk('public')->get($cleanPath);
            $mime = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($cleanPath) ?: 'image/png';
            return 'data:' . $mime . ';base64,' . base64_encode($content);
        }
        return '';
    }
}
