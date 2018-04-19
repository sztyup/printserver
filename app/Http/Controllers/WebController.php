<?php

namespace App\Http\Controllers;

use App\Datatables\Printers;
use App\Printing\Manager;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sztyup\Datatable\DatatableInterface;
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
     * @param Request $request
     * @param Printers $printers
     * @return \Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function index(Request $request, Printers $printers)
    {
        return $this->renderDatatables($request, $printers, 'index');
    }

    /**
     * @param Request $request
     * @param DatatableInterface $datatable
     * @param $view
     * @param array $viewData
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    protected function renderDatatables(Request $request, DatatableInterface $datatable, $view, $viewData = [])
    {
        $datatable->buildDatatable();

        if ($request->method() == 'POST' && $request->isXmlHttpRequest()) {
            $response = new DatatableResponse($request, $datatable);

            try {
                return $response->getResponse();
            } catch (\Exception $e) {
                if (config('app.debug')) {
                    throw $e;
                }

                return response()->json([
                    'error' => "\nHiba történt a táblázat megjelenítése közben"
                ]);
            }
        } else {
            return $this->view($view, array_merge($viewData, [
                'dataTable' => $datatable
            ]));
        }
    }
}
