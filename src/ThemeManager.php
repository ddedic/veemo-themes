<?php
/**
 * Project: Veemo
 * User: ddedic
 * Date: 16/03/15
 * Time: 20:39
 */

namespace Veemo\Themes;


use Illuminate\Config\Repository;
use Veemo\Themes\Adapters\ThemeManagerAdapterInterface;


/**
 * Class ThemeManager
 * @package Veemo\Themes
 */
class ThemeManager
{


    /**
     * @var ThemeManagerAdapterInterface
     */
    private $adapter;

    /**
     * @var Repository
     */
    private $config;


    /**
     * @param ThemeManagerAdapterInterface $adapter
     * @param Repository $config
     */
    public function __construct(ThemeManagerAdapterInterface $adapter, Repository $config)
    {
        $this->adapter = $adapter;
        $this->config = $config;

    }


    /**
     * @param bool $enabled
     * @return mixed
     */
    public function frontend($enabled = true)
    {
        return $this->adapter->getFrontend($enabled);
    }

    /**
     * @param bool $enabled
     * @return mixed
     */
    public function backend($enabled = true)
    {
        return $this->adapter->getBackend($enabled);
    }


    /**
     * @return mixed
     */
    public function all()
    {
        return $this->adapter->getAll();
    }

    /**
     * @return mixed
     */
    public function dummy()
    {
        return 'Dummy method';
    }

}