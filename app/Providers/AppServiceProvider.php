<?php

namespace App\Providers;

use App\Entities\Printer;
use App\Extractor\SmalotPrinterAttributeExtractor;
use App\Extractor\SmalotPrinterAttributeExtractorInterface;
use App\Factory\NetworkPrinterFactory;
use App\Factory\NetworkPrinterFactoryInterface;
use App\Factory\PrinterFactory;
use App\Factory\PrinterFactoryInterface;
use App\Mediator\HostedPrinterMediator;
use App\Mediator\HostedPrinterMediatorInterface;
use App\Mediator\PrinterMediator;
use App\Mediator\PrinterMediatorInterface;
use App\Repository\PrinterRepository;
use App\Repository\PrinterRepositoryInterface;
use App\Transformer\PrinterTransformer;
use App\Transformer\PrinterTransformerInterface;
use App\Transformer\SmalotPrinterTransformer;
use App\Transformer\SmalotPrinterTransformerInterface;
use Http\Client\HttpClient;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Smalot\Cups\Transport\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(HttpClient::class, Client::class);

        $this->app->bind(SmalotPrinterAttributeExtractorInterface::class, SmalotPrinterAttributeExtractor::class);

        $this->app->bind(NetworkPrinterFactoryInterface::class, NetworkPrinterFactory::class);
        $this->app->bind(PrinterFactoryInterface::class, PrinterFactory::class);

        $this->app->bind(HostedPrinterMediatorInterface::class, HostedPrinterMediator::class);
        $this->app->bind(PrinterMediatorInterface::class, PrinterMediator::class);

        $this->app->bind(PrinterProviderInterface::class, CupsPrinterProvider::class);

        $this->app->bind(PrinterRepositoryInterface::class, static function ($app) {
            return new PrinterRepository(
                $app['em'],
                $app['em']->getClassMetaData(Printer::class)
            );
        });

        $this->app->bind(PrinterTransformerInterface::class, PrinterTransformer::class);
        $this->app->bind(SmalotPrinterTransformerInterface::class, SmalotPrinterTransformer::class);
    }
}
