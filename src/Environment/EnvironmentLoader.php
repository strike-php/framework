<?php

declare(strict_types=1);

namespace Bambamboole\Framework\Environment;

use Dotenv\Dotenv;
use Dotenv\Repository\Adapter\PutenvAdapter;
use Dotenv\Repository\RepositoryBuilder;

class EnvironmentLoader
{
    public function load(array $filePaths, array $names): Environment
    {
        $builder = RepositoryBuilder::createWithDefaultAdapters();
        $builder = $builder->addAdapter(PutenvAdapter::class);
        $repository = $builder->make();
        $env = Dotenv::create($repository, $filePaths, $names, false);

        return new Environment($env->safeLoad());
    }
}
