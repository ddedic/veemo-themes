<?php namespace Veemo\Themes\Adapters;

/**
 * Project: Veemo
 * User: ddedic
 * Email: dedic.d@gmail.com
 * Date: 16/03/15
 * Time: 23:42
 */

use Illuminate\Filesystem\Filesystem;
use Illuminate\Config\Repository;
use Illuminate\Support\Collection;

use Veemo\Core\Modules\Exceptions\FileMissingException;


/**
 * Class FileManagerAdapter
 * @package Veemo\Themes\Adapters
 */
class FileManagerAdapter implements ThemeManagerAdapterInterface
{


    /**
     * @param $slug
     * @return bool
     */
    public function exist($slug)
    {
        // TODO: Implement exist() method.
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    /**
     * @param bool $enabled
     * @return mixed
     */
    public function getFrontend($enabled = true)
    {
        // TODO: Implement getFrontend() method.
    }

    /**
     * @param bool $enabled
     * @return mixed
     */
    public function getBackend($enabled = true)
    {
        // TODO: Implement getBackend() method.
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function find($slug)
    {
        // TODO: Implement find() method.
    }

    /**
     * @return bool
     */
    public function isEnabled($slug)
    {
        // TODO: Implement isEnabled() method.
    }

    /**
     * @return bool
     */
    public function isDisabled($slug)
    {
        // TODO: Implement isDisabled() method.
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function enable($slug)
    {
        // TODO: Implement enable() method.
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function disable($slug)
    {
        // TODO: Implement disable() method.
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function setActive($slug)
    {
        // TODO: Implement setActive() method.
    }
}