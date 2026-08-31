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
