<?php

class Loewenstark_Cache_Model_Core_Cache
extends Mage_Core_Model_Cache
{
    
    protected $_defaultBackend = 'Cm_Cache_Backend_File';
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
        return (array)Mage::app()->getConfig()->getNode('global/'.$fallback);
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
     * @param array $options
     * @return Loewenstark_Cache_Model_Core_Cache
     */
    protected function _setCacheFileContent($options)
    {
        file_put_contents($this->_getCacheFileName(), json_encode($options));
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
