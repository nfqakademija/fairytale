<?php

namespace Nfq\Fairytale\FrontendBundle\Service;

use Assetic\Asset\AssetInterface;
use Assetic\Factory\AssetFactory;
use Assetic\Filter\DependencyExtractorInterface;
use Assetic\Util\LessUtils;

/**
 * Loads LESS files using the PHP implementation of less, ILess
 *
 * @link https://github.com/mishal/iless
 */
class ILessFilter implements DependencyExtractorInterface
{
    protected $loadPaths = [];

    public function filterLoad(AssetInterface $asset)
    {
        $root = $asset->getSourceRoot();
        $path = $asset->getSourcePath();

        $imports = array();

        if ($root && $path) {
            $imports[] = dirname($root.'/'.$path);
        }

        foreach ($this->loadPaths as $loadPath) {
            $imports[] = $loadPath;
        }

        $parser = new \ILess_Parser(array('import_dirs' => $imports));

        $asset->setContent($parser->parseString($asset->getContent())->getCSS());
    }

    /**
     * {@inheritDoc}
     * @SuppressWarnings("unused")
     */
    public function filterDump(AssetInterface $asset)
    {
    }

    public function getChildren(AssetFactory $factory, $content, $loadPath = null)
    {
        $loadPaths = $this->loadPaths;
        if (null !== $loadPath) {
            $loadPaths[] = $loadPath;
        }

        if (empty($loadPaths)) {
            return array();
        }

        $children = [];
        foreach (LessUtils::extractImports($content) as $reference) {
            $children = array_merge($children, $this->findCildren($factory, $reference, $loadPaths));
        }

        return $children;
    }

    protected function findChildren($factory, $reference, $loadPaths)
    {
        $children = [];
        if ('.css' === substr($reference, -4)) {
            // skip normal css imports
            // todo: skip imports with media queries
            return $children;
        } elseif ('.less' !== substr($reference, -5)) {
            $reference .= '.less';
        }

        foreach ($loadPaths as $loadPath) {
            if (file_exists($file = $loadPath.'/'.$reference)) {
                $coll = $factory->createAsset($file, array(), array('root' => $loadPath));
                foreach ($coll as $leaf) {
                    $leaf->ensureFilter($this);
                    $children[] = $leaf;
                    break 2;
                }
            }
        }
        return $children;
    }
}
