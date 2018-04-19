<?php

namespace App\Datatables;

use App\Entities\Printer;
use Sztyup\Datatable\AbstractDatatable;
use Sztyup\Datatable\Column\Column;

class Printers extends AbstractDatatable
{
    /**
     * Builds the datatable.
     *
     * @param array $options
     * @throws \Exception
     */
    public function buildDatatable(array $options = [])
    {
        $this->columnBuilder
            ->add('label', Column::class, [
                'title' => 'Megnevezés'
            ])
        ;
    }

    /**
     * Returns the name of the entity.
     *
     * @return string
     */
    public function getEntity()
    {
        return Printer::class;
    }

    /**
     * Returns the name of this datatable view.
     *
     * @return string
     */
    public function getName()
    {
        return 'printers';
    }
}
