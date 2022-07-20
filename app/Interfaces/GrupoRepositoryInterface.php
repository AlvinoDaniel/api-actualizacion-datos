<?php

namespace App\Interfaces;

use App\Interfaces\BaseRepositoryInterface;

interface GrupoRepositoryInterface extends BaseRepositoryInterface 
{
   public function allGrupos();
   public function registrarGrupo(array $data);
}