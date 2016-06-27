<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    const ENV_DEVELOPMENT = 'dev';
    const ENV_TESTING = 'test';
    const ENV_PRODUCTION = 'prod';

    const DIR_VAR = 'var';
    const DIR_CACHE = 'cache';
    const DIR_LOGS = 'logs';
    const DIR_TEMPORARY = 'tmp';
    const DIR_SESSIONS = 'sessions';

    /**
     * Sets some PHP environment settings
     *
     * @return void
     */
    private function setupPHP()
    {
        if ($this->isDebugEnvironment()) {
            ini_set('display_errors', true);

            if (extension_loaded('xdebug')) {
                ini_set('xdebug.max_nesting_level', 1000);
            }
        }

        mb_internal_encoding($this->getCharset());

        date_default_timezone_set('Europe/Warsaw');
        ini_set('date.timezone', 'Europe/Warsaw');
        putenv(sprintf('TMPDIR=%s', $this->getTempDir()));
        putenv(sprintf('TMP=%s', $this->getTempDir()));
        putenv(sprintf('TEMP=%s', $this->getTempDir()));
        ini_set('upload_tmp_dir', $this->getTempDir());
        ini_set('session.save_path', $this->getSessionsDir());
        ini_set('soap.wsdl_cache_dir', $this->getTempDir());
    }

    /**
     * Checks strange directories
     *
     * @return void
     */
    private function checkStorageDirectories()
    {
        if (!is_dir($this->getVarDir())) {
            mkdir($this->getVarDir(), 0777, true);
        }

        if (!is_dir($this->getVarEnvironmentDir())) {
            mkdir($this->getVarEnvironmentDir(), 0777, true);
        }

        if (!is_dir($this->getTempDir())) {
            mkdir($this->getTempDir(), 0777, true);
        }

        if (!is_dir($this->getSessionsDir())) {
            mkdir($this->getSessionsDir(), 0777, true);
        }

        if (!is_dir($this->getCacheDir())) {
            mkdir($this->getCacheDir(), 0777, true);
        }

        if (!is_dir($this->getLogDir())) {
            mkdir($this->getLogDir(), 0777, true);
        }
    }

    /**
     * {@inheritdoc}
     * @see \Symfony\Component\HttpKernel\Kernel::__construct()
     */
    public function __construct($environment = self::ENV_PRODUCTION, $debug = false)
    {
        parent::__construct($environment, $this->isDebugEnvironment() || $debug);

        $this->checkStorageDirectories();
        $this->setupPHP();
    }

    /**
     * List of debug enviroments
     *
     * @return array
     */
    public static function getDebugEnvironment()
    {
        return [
            self::ENV_DEVELOPMENT => 1,
            self::ENV_TESTING => 1,
        ];
    }

    /**
     * Is it debug environment
     *
     * @return bool
     */
    public function isDebugEnvironment()
    {
        return array_key_exists($this->getEnvironment(), $this->getDebugEnvironment()) || $this->isDebug();
    }

    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new Csa\Bundle\GuzzleBundle\CsaGuzzleBundle(),
            new ApiBundle\ApiBundle(),
            new FrontendBundle\FrontendBundle(),
            new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),
        ];

        if ($this->isDebugEnvironment()) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    /**
     * Gets the main storage directory.
     *
     * @return string
     */
    protected function getVarDir()
    {
        return dirname($this->getRootDir()) . DIRECTORY_SEPARATOR . self::DIR_VAR;
    }

    /**
     * Gets the storage directory based on environment.
     *
     * @return string
     */
    protected function getVarEnvironmentDir()
    {
        return $this->getVarDir() . DIRECTORY_SEPARATOR . $this->getEnvironment();
    }

    /**
     * Gets the temp directory.
     *
     * @return string
     */
    public function getTempDir()
    {
        return $this->getVarEnvironmentDir() . '/' . self::DIR_TEMPORARY;
    }

    /**
     * Gets the sessions directory.
     *
     * @return string
     */
    public function getSessionsDir()
    {
        return $this->getVarEnvironmentDir() . '/' . self::DIR_SESSIONS;
    }

    /**
     * {@inheritdoc}
     * @see \Symfony\Component\HttpKernel\Kernel::getCacheDir()
     */
    public function getCacheDir()
    {
        return $this->getVarEnvironmentDir() . '/' . self::DIR_CACHE;
    }

    /**
     * {@inheritdoc}
     * @see \Symfony\Component\HttpKernel\Kernel::getLogDir()
     */
    public function getLogDir()
    {
        return $this->getVarEnvironmentDir() . '/' . self::DIR_LOGS;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
