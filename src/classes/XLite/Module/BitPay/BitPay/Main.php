<?php
namespace XLite\Module\BitPay\BitPay;


abstract class Main extends \XLite\Module\AModule
{
    /**
     * Author name
     *
     * @return string
     */
    public static function getAuthorName()
    {
        return 'BitPay';
    }

    /**
     * Module name
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'BitPay';
    }

    /**
     * Get module major version
     *
     * @return string
     */
    public static function getMajorVersion()
    {
        return '5.2';
    }

    /**
     * Get module minor version
     *
     * @return string
     */
    public static function getMinorVersion()
    {
        return '0';
    }

    /**
     * Return an URL to the module icon.
     * If an empty string is returned "icon.png" from the module directory will be used.
     *
     * @return string
     */
    public static function getIconURL()
    {
       return '';
    }

    /**
     * Module description
     *
     * @return string
     */
    public static function getDescription()
    {
        return 'BitPay Payment Method';
    }

    /**
     * Return a list of modules the module depends on.
     * Each item should be a full module identifier: "<Developer>\<Module>".
     *
     * @return array
     */
    public static function getDependencies()
    {
        return array();
    }

}
