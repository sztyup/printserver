<?php

namespace App\Http\Controllers;

use App\Entities\Printer;
use App\Http\Requests\PrintFileRequest;
use App\Printing\Manager;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class ApiController extends Controller
{
    /**
     * @param Printer $printer
     * @param Manager $manager
     * @return mixed
     */
    public function getPrinter(Printer $printer, Manager $manager)
    {
        return $manager->getPrinterByCupsUri($printer->getCupsUri());
    }

    /**
     * @param Manager $manager
     * @return \App\Printing\Printer[]
     * @throws \Exception
     */
    public function listPrinters(Manager $manager)
    {
        return $manager->getPrinters();
    }

    /**
     * @param Printer $printer
     * @param PrintFileRequest $request
     * @param Manager $manager
     * @return JsonResponse
     */
    public function printWith(Printer $printer, PrintFileRequest $request, Manager $manager)
    {
        $p = $manager->getPrinterByCupsUri($printer->getCupsUri());

        if (is_null($p)) {
            abort(400);
        }
        
        $pdf = $request->file('pdf');

        $success = $manager->printFile($p, $pdf, $request->get('copies'));

        return response('', $success ? 200 : 500);
    }
}
