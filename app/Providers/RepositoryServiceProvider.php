<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\BaseRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Interfaces\CarpetaRepositoryInterface;
use App\Repositories\CarpetaRepository;
use App\Interfaces\GrupoRepositoryInterface;
use App\Repositories\GrupoRepository;
use App\Interfaces\DepartamentoRepositoryInterface;
use App\Repositories\DepartamentoRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(CarpetaRepositoryInterface::class, CarpetaRepository::class);
        $this->app->bind(GrupoRepositoryInterface::class, GrupoRepository::class);
        $this->app->bind(DepartamentoRepositoryInterface::class, DepartamentoRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
