<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PDFService
{
    public function generateInvoice($order)
    {
        $pdf = Pdf::loadView('orders.invoice', compact('order'));
        return $pdf->download('invoice.pdf');
    }
}
