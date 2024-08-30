<?php

namespace App\Http\Libs;

use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Exception;
use Illuminate\Support\Facades\Storage;

class Pdf
{
    protected $domPDF;

    public function __construct(DomPDFPDF $domPDF)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $this->domPDF = $domPDF;
    }

    public function generatePdf($view, $path, $data = null, $fileName = 'file_')
    {

        if ($fileName === 'file_') {
            $fileName = 'file_'.md5(rand()). date("dmY-His") . '.pdf';
        } else {
            $fileName = $fileName .'_'. date("dmY-His") . '.pdf';
        }

        try {

            $pdf = $this->domPDF->loadView($view, compact('data'))
            ->setOptions(['isRemoteEnabled' => true]);

            Storage::put($path . '/' . $fileName, $pdf->output());

            return url('uploads/' . $path . '/' . $fileName);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getFile($view, $path, $data = null, $format = 'portrait')
    {
        $nameFile = md5(rand()) . '.pdf';
        
        try {
            $pdf = $this->domPDF->loadView($view, compact('data'))
                ->setOptions(['isRemoteEnabled' => true])
                ->setPaper('A4', $format);

            Storage::put($path . '/' . $nameFile, $pdf->output());

            return $path . '/' . $nameFile;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
