<?php

namespace App\Http\Controllers;

use App\Printing\Manager;
use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\Controller;

class WebController extends Controller
{
    protected $viewFactory;

    public function __construct(Factory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    protected function view($view, $viewData = [])
    {
        return $this->viewFactory->make($view, $viewData);
    }

    /**
     * @param Manager $manager
     * @return \Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function index(Manager $manager)
    {
        dd($manager->getPrinters()[0]->getSn());
        return $this->view('index', [
            'printers' => $manager->getPrinters()[0]->getSn()
        ]);
    }
}
