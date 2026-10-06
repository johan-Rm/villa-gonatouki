<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\Imagine;

use Liip\ImagineBundle\Binary\BinaryInterface;
use Liip\ImagineBundle\Imagine\Cache\Helper\PathHelper;
use Liip\ImagineBundle\Imagine\Cache\Resolver\ResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\RequestContext;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class CustomResolver implements ResolverInterface
{
    /**
     * @Container
     **/
    private $container;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var RequestContext
     */
    protected $requestContext;

    /**
     * @var string
     */
    protected $webRoot;

    /**
     * @var string
     */
    protected $cachePrefix;

    /**
     * @var string
     */
    protected $cacheRoot;

    /**
     * @param string $webRootDir
     * @param string $cachePrefix
     */
    public function __construct(
        ContainerInterface $container,
        Filesystem $filesystem,
        RequestContext $requestContext,
        $webRootDir,
        $cachePrefix
    ) {
        $this->container = $container;
        $this->filesystem = $filesystem;
        $this->requestContext = $requestContext;

        $this->webRoot = rtrim(str_replace('//', '/', $webRootDir), '/');
        $this->cachePrefix = ltrim(str_replace('//', '/', $cachePrefix), '/');
        $this->cacheRoot = $this->webRoot.'/'.$this->cachePrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($path, $filter)
    {
        // die('CustomResolver::resolve');
        return sprintf('%s/%s',
            rtrim($this->getBaseUrl(), '/'),
            ltrim($this->getFileUrl($path, $filter), '/')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isStored($path, $filter)
    {
        // die('CustomResolver::isStored');
        return is_file($this->getFilePath($path, $filter));
    }

    /**
     * {@inheritdoc}
     */
    public function store(BinaryInterface $binary, $path, $filter)
    {
        // die('CustomResolver::store');
        $this->filesystem->dumpFile(
            $this->getFilePath($path, $filter),
            $binary->getContent()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function remove(array $paths, array $filters)
    {
        // die('CustomResolver::remove');
        if (empty($paths) && empty($filters)) {
            return;
        }

        if (empty($paths)) {
            $filtersCacheDir = [];
            foreach ($filters as $filter) {
                $filtersCacheDir[] = $this->cacheRoot.'/'.$filter;
            }

            $this->filesystem->remove($filtersCacheDir);

            return;
        }

        foreach ($paths as $path) {
            foreach ($filters as $filter) {
                $this->filesystem->remove($this->getFilePath($path, $filter));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getFilePath($path, $filter)
    {
        // die('CustomResolver::getFilePath');
        return $this->webRoot.'/'.$this->getFullPath($path, $filter);
    }

    /**
     * {@inheritdoc}
     */
    protected function getFileUrl($path, $filter)
    {
        // die('CustomResolver::getFileUrl');
        return PathHelper::filePathToUrlPath($this->getFullPath($path, $filter));
    }

    /**
     * @return string
     */
    protected function getBaseUrl()
    {
        // die('CustomResolver::getBaseUrl');

        $port = '';
        if ('https' === $this->requestContext->getScheme() && 443 !== $this->requestContext->getHttpsPort()) {
            $port = ":{$this->requestContext->getHttpsPort()}";
        }

        if ('http' === $this->requestContext->getScheme() && 80 !== $this->requestContext->getHttpPort()) {
            $port = ":{$this->requestContext->getHttpPort()}";
        }

        $baseUrl = $this->requestContext->getBaseUrl();
        if ('.php' === mb_substr($this->requestContext->getBaseUrl(), -4)) {
            $baseUrl = pathinfo($this->requestContext->getBaseurl(), PATHINFO_DIRNAME);
        }
        $baseUrl = rtrim($baseUrl, '/\\');

        return sprintf('%s://%s%s%s',
            $this->requestContext->getScheme(),
            $this->requestContext->getHost(),
            $port,
            $baseUrl
        );

        // return $this->container->getParameter('view.host');
    }

    private function getFullPath($path, $filter)
    {
        // die('CustomResolver::getFullPath');
        // crude way of sanitizing URL scheme ("protocol") part
        $path = str_replace('://', '---', $path);

        return $this->cachePrefix.'/'.$filter.'/'.ltrim($path, '/');
    }
}
