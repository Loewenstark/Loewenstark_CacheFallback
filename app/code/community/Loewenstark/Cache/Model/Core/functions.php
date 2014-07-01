<?php
if(strstr(phpversion(), 'hiphop'))
{
    // LZ4 for HHVM
    if(!function_exists('lz4_compress') && function_exists('lz4compress'))
    {
        /**
         * compress data
         * 
         * @param string $data
         * @param type $high (optional) not used by HHVM
         * @param type $extra (optional) not used by HHVM
         * @return string
         */
        function lz4_compress($data, $high = false, $extra = null)
        {
            return lz4compress($data);
        }
    }
    if(!function_exists('lz4_uncompress') && function_exists('lz4uncompress'))
    {
        function lz4_uncompress($data, $maxsize = -1, $offset = -1)
        {
            return lz4uncompress($data);
        }
    }
    if(!function_exists('lz4_decompress') && function_exists('lz4uncompress'))
    {
        function lz4_decompress($data, $maxsize = -1, $offset = -1)
        {
            return lz4uncompress($data);
        }
    }

    // LZF
    if(!function_exists('lzf_decompress') && function_exists('lz4uncompress'))
    {
        function lzf_decompress($data, $maxsize = -1, $offset = -1)
        {
            return lz4uncompress($data);
        }
    }
    if(!function_exists('lzf_compress') && function_exists('lz4uncompress'))
    {
        function lzf_compress($data)
        {
            return lz4compress($data);
        }
    }

    //SNAPPY for HHVM
    if(!function_exists('snappy_compress') && function_exists('sncompress'))
    {
        /**
         * compress with snappy
         * 
         * @param string $data
         * @return string
         */
        function snappy_compress($data)
        {
            return sncompress($data);
        }
    }
    if(!function_exists('snappy_uncompress') && function_exists('snuncompress'))
    {
        /**
         * Decompress Snappy
         * 
         * @param string $data
         * @return string
         */
        function snappy_uncompress($data)
        {
            return snuncompress($data);
        }
    }
    if(!function_exists('snappy_decompress') && function_exists('snuncompress'))
    {
        /**
         * Decompress Snappy
         * 
         * @param string $data
         * @return string
         */
        function snappy_decompress($data)
        {
            return snuncompress($data);
        }
    }
}