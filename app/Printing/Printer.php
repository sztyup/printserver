<?php

namespace App\Printing;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class Printer
{
    /** @var array */
    protected $config;

    /** @var Factory */
    protected $viewFactory;

    /** @var Filesystem */
    protected $filesystem;

    /**
     * PrintManager constructor.
     * @param Repository $config
     * @param Factory $viewFactory
     * @param Filesystem $filesystem
     */
    public function __construct(
        Repository $config,
        Factory $viewFactory,
        Filesystem $filesystem
    ) {
        $this->filesystem = $filesystem;
        $this->viewFactory = $viewFactory;
        $this->config = $config->get('services.printer');
    }

    /**
     * @param $file
     * @return bool
     * @throws \Exception
     */
    public function printFile($file)
    {
        if ($file instanceof \SplFileInfo) {
            $file = $file->getRealPath();
        }

        if (!is_file($file)) {
            throw new \InvalidArgumentException("File ($file) does not exists");
        }

        $printer = $this->config['printer_name'];

        $process = new Process(
            sprintf('lp -d %s %s', $printer, $file)
        );

        $process->run();

        if ($process->isSuccessful()) {
            return true;
        } else {
            throw new \Exception($process->getErrorOutput());
        }
    }
}