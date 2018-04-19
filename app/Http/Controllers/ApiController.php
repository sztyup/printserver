<?php

namespace App\Http\Controllers;

use App\Entities\Printer;
use App\Printing\Manager;
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
        return $manager->getPrinterBySn($printer->getSn());
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
}
