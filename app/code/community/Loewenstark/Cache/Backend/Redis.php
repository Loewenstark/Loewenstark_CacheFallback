<?php
/**
 * this is not an replacment of the origin redis class, just an extension
 * here i will only fix issues with HHVM and PHP using LZ4 Compression
 * at the same time
 */
class Loewenstark_Cache_Backend_Redis
extends Cm_Cache_Backend_Redis
{

    /**
     * check if lz4 will be used
     * 
     * @return boolean
     */
    protected function isLz4()
    {
        if ($this->_compressionLib == 'l4z')
        {
            return true;
        }
        return false;
    }

    /**
     * is current engine Facebook-HHVM
     * 
     * @return bool
     */
    protected function isHHVM()
    {
        return defined('HHVM_VERSION');
    }

    /**
     * get Prefix Type
     * PH = PHP Runtime
     * HH = HHVM Runtime
     * 
     * @return type
     */
    protected function _getPrefixTypes()
    {
        if (!$this->isLz4())
        {
            return array();
        }
        return array(
            'PH',
            'HH'
        );
    }

    /**
     * get Id without _getPrefixTypes
     * 
     * @param string $id
     * @return string
     */
    protected function _getId($id)
    {
        if (!$this->isLz4())
        {
            return $id;
        }
        return substr($id, 2);
    }

    /**
     * Remove a cache record
     *
     * @param  string $id Cache id
     * @return boolean True if no problem
     */
    public function remove ($id)
    {
        if (!$this->isLz4())
        {
            return parent::remove($id);
        }
        $result = array();
        $id = $this->_getId($id);
        foreach ($this->_getPrefixTypes() as $_type)
        {
            $result[$_type] = parent::remove($_type.$id);
        }
        if ($this->isHHVM())
        {
            return $result['HH'];
        }
        return $result['PH'];
    }

    /**
     * Clean some cache records
     *
     * Available modes are :
     * 'all' (default)  => remove all cache entries ($tags is not used)
     * 'old'            => runs _collectGarbage()
     * 'matchingTag'    => supported
     * 'notMatchingTag' => supported
     * 'matchingAnyTag' => supported
     *
     * @param  string $mode Clean mode
     * @param  array  $tags Array of tags
     * @throws Zend_Cache_Exception
     * @return boolean True if no problem
     */
    public function clean($mode = Zend_Cache::CLEANING_MODE_ALL, $tags = array())
    {
        if (!$this->isLz4())
        {
            return parent::clean($mode, $tags);
        }
        if( $tags && ! is_array($tags)) {
            $tags = array($tags);
        }
        if (count($tags) > 0)
        {
            foreach ($tags as $_tag)
            {
                $id = $this->_getId($id);
                foreach ($this->_getPrefixTypes() as $_type)
                {
                    if (!in_array($_type.$id, $tags))
                    {
                        $tags[] = $_type.$id;
                    }
                }
            }
        }
        return parent::clean($mode, $tags);
    }
}
 
