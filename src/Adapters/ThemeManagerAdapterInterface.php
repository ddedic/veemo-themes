<?php
/**
 * Project: Veemo
 * User: ddedic
 * Email: dedic.d@gmail.com
 * Date: 16/03/15
 * Time: 23:46
 */

namespace Veemo\Themes\Adapters;


/**
 * Interface ThemeManagerAdapterInterface
 * @package Veemo\Themes\Adapters
 */
interface ThemeManagerAdapterInterface
{

    /**
     * @param $slug
     * @return bool
     */
    public function exist($slug);

    /**
     * @return mixed
     */
    public function getAll();

    /**
     * @param bool $enabled
     * @return mixed
     */
    public function getFrontend($enabled = true);

    /**
     * @param bool $enabled
     * @return mixed
     */
    public function getBackend($enabled = true);

    /**
     * @param $slug
     * @return mixed
     */
    public function find($slug);

    /**
     * @return bool
     */
    public function isEnabled($slug);

    /**
     * @return bool
     */
    public function isDisabled($slug);

    /**
     * @param $slug
     * @return mixed
     */
    public function enable($slug);

    /**
     * @param $slug
     * @return mixed
     */
    public function disable($slug);

    /**
     * @param $slug
     * @return mixed
     */
    public function setActive($slug);

} 