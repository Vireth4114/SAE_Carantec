<?php
namespace App\Http\Controllers;

use App\Models\web\AcnDives;
use App\Models\web\AcnGroups;
use App\Models\web\AcnPrerogative;
use Illuminate\Support\Facades\DB;

class PDFController extends Controller {
    
    static public function generatePDF() {        
        return view('pdfConverter')->render();
    }
}

?>
