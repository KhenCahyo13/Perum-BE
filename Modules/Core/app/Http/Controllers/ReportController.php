<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Core\Services\DashboardService;
use Modules\Core\Support\ApiResponse;

class ReportController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {}

    public function dashboard(): JsonResponse
    {
        return ApiResponse::success($this->dashboardService->summary(), message: 'Data dashboard berhasil diambil.');
    }
}
