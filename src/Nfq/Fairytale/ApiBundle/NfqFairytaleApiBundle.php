<?php

namespace Nfq\Fairytale\ApiBundle;

use Nfq\Fairytale\ApiBundle\DependencyInjection\Compiler\ActionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NfqFairytaleApiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ActionPass());
    }

}
