<?php

class Loewenstark_Cache_Model_Core_Cache
extends Mage_Core_Model_Cache
{
    
    protected $_defaultBackend = 'Cm_Cache_Backend_File';
    protected $_create_cache = false;
    protected $_debug = false;

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
     * Mage_Core_Model_Cache::__construct
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
            if($this->_debug)
            {
                file_put_contents(Mage::getBaseDir('log').DS.'cache.log', "####".date('r')."####\n".print_r($options, true)."\n".$e->getMessage()."\n".$e->getTraceAsString()."\n", FILE_APPEND);
            }
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
     * 
     * @return array
     */
    protected function _getCacheConfig($fallback = 'cache')
    {
        return (array)json_decode(json_encode(Mage::app()->getConfig()->getNode('global/'.$fallback)), true); // convert to array
    }

    /**
     * 
     * @return string
     */
    protected function _getCacheFileName()
    {
        return  Mage::getBaseDir('var').DS.'cache.json';
    }
    
    /**
     * 
     * @return string
     */
    protected function _getCacheTmpFileName()
    {
        return tempnam(Mage::getBaseDir('tmp'), 'loe-fc-');
    }

    /**
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
