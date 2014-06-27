<?php
require_once __DIR__.DS.'functions.php';
/**
  * Loewenstark_Cache
  *
  * @category  Loewenstark
  * @package   Loewenstark_Cache
  * @author    Mathis Klooss <m.klooss@loewenstark.com>
  * @copyright 2013 Loewenstark Web-Solution GmbH (http://www.mage-profis.de/). All rights served.
  * @license   https://github.com/mklooss/Loewenstark_Cache/blob/master/README.md
  */
class Loewenstark_Cache_Model_Core_Cache
extends Mage_Core_Model_Cache
{
    /** @var string Default Cache Backend */
    protected $_defaultBackend = 'Cm_Cache_Backend_File';
    /** @var bool Create Cache file */
    protected $_create_cache = false;

    /**
     * Class constructor. Initialize cache instance based on options
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->_getCacheModel($options);
    }
    
    /**
     * will check if cache is possible, if not will choose the fallback
     * 
     * @param array $options
     */
    protected function _getCacheModel(array $options = array())
    {
        $config = $this->_getCacheFileContent();
        if($config)
        {
            $options = $config;
            unset($config);
        }
        try {
            $this->_getParentConstruct($options);
        } catch(Exception $e)
        {
            $this->_create_cache = true;
            $this->_removeCacheFile();
            if(isset($options['fallback']))
            {
                $this->_getCacheModel($this->_getCacheConfig($options['fallback']));
            } else {
                $this->_getParentConstruct(array('backend' => $this->_defaultBackend));
            }
        }
    }
    
    /**
     * Mage_Core_Model_Cache::__construct
     * start cache interface
     * 
     * @param array $options
     */
    protected function _getParentConstruct(array $options = array())
    {
        if($this->_create_cache)
        {
            $this->_setCacheFileContent($options);
        }
        parent::__construct($options);
    }


    /**
     * get Cache Configurations
     * 
     * @return array
     */
    protected function _getCacheConfig($fallback = 'cache')
    {
        return (array)json_decode(json_encode(Mage::app()->getConfig()->getNode('global/'.$fallback)), true); // convert to array
    }

    /**
     * get Cache Json Filename
     * 
     * @return string
     */
    protected function _getCacheFileName()
    {
        return  Mage::getBaseDir('var').DS.'cache.json';
    }
    
    /**
     * get Temp Filename for self::_getCacheFileName
     * 
     * @return string
     */
    protected function _getCacheTmpFileName()
    {
        return tempnam(Mage::getBaseDir('tmp'), 'loe-fc-');
    }

    /**
     * write cache config file
     * 
     * @param array $options
     * @return Loewenstark_Cache_Model_Core_Cache
     */
    protected function _setCacheFileContent($options)
    {
        $tmpName = $this->_getCacheTmpFileName();
        $name = $this->_getCacheFileName();
        file_put_contents($tmpName, json_encode($options));
        @rename($tmpName, $name);
        return $this;
    }

    /**
     * get Configuration from Cache file
     * 
     * @return array
     */
    protected function _getCacheFileContent()
    {
        if(!file_exists($this->_getCacheFileName()))
        {
            return false;
        }
        try {
            return json_decode(file_get_contents($this->_getCacheFileName()), true);
        } catch (Exception $e){}
        return false;
    }
    
    /**
     * remove Json File
     * 
     * @return Loewenstark_Cache_Model_Core_Cache
     */
    protected function _removeCacheFile()
    {
        if(file_exists($this->_getCacheFileName()))
        {
            @unlink($this->_getCacheFileName());
        }
        return $this;
    }
}
