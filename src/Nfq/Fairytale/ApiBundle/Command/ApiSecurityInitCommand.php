<?php

namespace Nfq\Fairytale\ApiBundle\Command;

use Nfq\Fairytale\ApiBundle\Security\CredentialStore;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class ApiSecurityInitCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('api:security:init')
            ->addArgument('bundle', InputArgument::REQUIRED)
            ->addArgument('role', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $bundle = $input->getArgument('bundle');
        $role = $input->getArgument('role');

        $bundleRoot = $this->getContainer()->get('kernel')->locateResource('@' . $bundle . '/Entity');

        $finder = new Finder();
        $finder->files()->in($bundleRoot)->name('*.php');

        $root = dirname($this->getContainer()->getParameter('kernel.root_dir')) . '/src/';

        $tree = [];
        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            list($fqcn, $class) = str_replace(
                [$root, '.php', '/'],
                ['', '', '\\'],
                [$file->getPathname(), $file->getFilename()]
            );

            $tree[$bundle][$class] = [];
            $refObj = new \ReflectionObject(new $fqcn);

            foreach ($refObj->getProperties() as $prop) {
                $tree[$bundle][$class][$prop->getName()] = [
                    CredentialStore::CREATE => $role,
                    CredentialStore::READ   => $role,
                    CredentialStore::UPDATE => $role,
                    CredentialStore::DELETE => $role,
                ];
            }
        }

        $output->writeln(Yaml::dump($tree, PHP_INT_MAX));
    }
}
