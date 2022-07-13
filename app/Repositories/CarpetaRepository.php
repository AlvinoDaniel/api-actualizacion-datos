<?php

namespace App\Repositories;

use App\Interfaces\CarpetaRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Carpeta;
use Illuminate\Support\Facades\DB;

class CarpetaRepository extends BaseRepository implements CarpetaRepositoryInterface {

        /**
         * @var Model
         */
        protected $model;

        /**
         * Base Repository Construct
         * 
         * @param Model $model
         */
        public function __construct(Carpeta $carpeta)
        {
            $this->model = $carpeta;
        }
}