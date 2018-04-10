<?php

namespace App\Printing;

use Symfony\Component\Process\Process;

class Discovery
{
    public function __construct()
    {
    }

    public function discover()
    {
        $process = new Process(
            'lpstat -v'
        );

        $process->run();

        if ($process->isSuccessful()) {
            $output = $process->getOutput();
            $printers = $this->parseOutput($output);

            return $printers;
        } else {
            echo $process->getErrorOutput();

            return [];
        }
    }

    protected function parseOutput(string $output)
    {
        $networkPrinters = [];
        $localPrinters = [];

        foreach (explode("\n", $output) as $line) {
            // Match network printers
            preg_match('/device for ([^:]*): socket:\/\/([0-9.]*):([0-9]*)/', $line, $matches);

            if (count($matches) == 4) {
                $networkPrinters[] = [
                    'name' => $matches[1],
                    'ip' => $matches[2],
                    'port' => $matches[3]
                ];
            }

            // Math local printers
            preg_match('/device for ([^:]*): ([^usb]*usb[^n]*)/', $line, $matches);

            if (count($matches) == 3) {
                $localPrinters[] = [
                    'name' => $matches[1],
                    'address' => $matches[2]
                ];
            }
        }

        return [
            'network' => $networkPrinters,
            'local' => $localPrinters
        ];
    }
}