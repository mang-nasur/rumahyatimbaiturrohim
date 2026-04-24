<?php

namespace App\Http\Controllers;

use App\Services\LaporanService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LaporanController extends Controller
{
    public function __construct(
        private LaporanService $laporanService
    ) {}

    /**
     * Show report selection form
     */
    public function index(): View
    {
        return view('laporan.index');
    }

    /**
     * Generate report preview
     */
    public function preview(Request $request): View
    {
        $request->validate([
            'type' => 'required|in:semua,usia,pendidikan,tahun_masuk',
            'min_age' => 'nullable|integer|min:0',
            'max_age' => 'nullable|integer|min:0',
            'pendidikan' => 'nullable|string',
            'tahun' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        $type = $request->input('type');
        $filters = $request->only(['min_age', 'max_age', 'pendidikan', 'tahun']);

        $data = $this->laporanService->getData($type, $filters);
        $title = $this->laporanService->getReportTitle($type, $filters);

        return view('laporan.preview', compact('data', 'title', 'type', 'filters'));
    }

    /**
     * Export to PDF
     */
    public function exportPdf(Request $request)
    {
        $request->validate([
            'type' => 'required|in:semua,usia,pendidikan,tahun_masuk',
            'min_age' => 'nullable|integer|min:0',
            'max_age' => 'nullable|integer|min:0',
            'pendidikan' => 'nullable|string',
            'tahun' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        $type = $request->input('type');
        $filters = $request->only(['min_age', 'max_age', 'pendidikan', 'tahun']);

        $data = $this->laporanService->getData($type, $filters);
        $title = $this->laporanService->getReportTitle($type, $filters);
        $tanggalCetak = now()->format('d/m/Y H:i');

        $pdf = Pdf::loadView('laporan.pdf', compact('data', 'title', 'tanggalCetak'));
        
        $filename = 'laporan-anak-yatim-' . date('Y-m-d-His') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export to Excel
     */
    public function exportExcel(Request $request): BinaryFileResponse
    {
        $request->validate([
            'type' => 'required|in:semua,usia,pendidikan,tahun_masuk',
            'min_age' => 'nullable|integer|min:0',
            'max_age' => 'nullable|integer|min:0',
            'pendidikan' => 'nullable|string',
            'tahun' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        $type = $request->input('type');
        $filters = $request->only(['min_age', 'max_age', 'pendidikan', 'tahun']);

        $data = $this->laporanService->getData($type, $filters);
        $title = $this->laporanService->getReportTitle($type, $filters);

        $filename = 'laporan-anak-yatim-' . date('Y-m-d-His') . '.xlsx';
        
        return Excel::download(new LaporanExport($data, $title), $filename);
    }
}
