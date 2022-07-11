<?php

declare(strict_types=1);

namespace Bambamboole\Framework\Core\Environment;

use Dotenv\Dotenv;
use Dotenv\Repository\Adapter\PutenvAdapter;
use Dotenv\Repository\RepositoryBuilder;

class EnvironmentLoader
{
    /** @param \SplFileInfo[] $filePaths */
    public function load(array $filePaths): Environment
    {
        $paths = $names = [];
        foreach ($filePaths as $file) {
            $paths[] = $file->getPath();
            $names[] = $file->getFilename();
        }
        $builder = RepositoryBuilder::createWithDefaultAdapters();
        $builder = $builder->addAdapter(PutenvAdapter::class);
        $repository = $builder->make();
        $env = Dotenv::create(
            $repository,
            \array_unique($paths),
            \array_unique($names),
            false,
        );

        return new Environment($env->safeLoad());
    }
}
