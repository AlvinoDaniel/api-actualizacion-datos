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
use App\Interfaces\PersonalRepositoryInterface;
use App\Repositories\PersonalRepository;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Interfaces\ConfiguracionRepositoryInterface;
use App\Repositories\ConfiguracionRepository;

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
        $this->app->bind(PersonalRepositoryInterface::class, PersonalRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ConfiguracionRepositoryInterface::class, ConfiguracionRepository::class);
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
