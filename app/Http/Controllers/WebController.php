<?php

namespace App\Http\Controllers;

use App\Datatables\Printers;
use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\Controller;
use Sztyup\Datatable\DatatableResponse;

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
     * @param DatatableResponse $response
     * @return \Illuminate\Contracts\View\View
     *
     * @throws \Exception
     */
    public function index(DatatableResponse $response)
    {
        return $response->getResponse(Printers::class, 'index');
    }
}
