<?php

namespace App\Controllers\Tentor;

use App\Controllers\Admin\Soal as AdminSoal;

/**
 * Prompt 38 & 39 — Kelola Soal Guru / Tentor
 * Tentor has equal power in question management (Free & Premium),
 * including question authoring, multimedia, options, categorization, and scoring rules.
 */
class Soal extends AdminSoal
{
    // Inherits all Soal CRUD methods from AdminSoal.
    // The views and redirects automatically adapt to role 'tentor'.
}
