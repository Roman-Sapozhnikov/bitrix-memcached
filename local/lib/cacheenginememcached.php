<?
/**
 * Created by PhpStorm.
 * User: dh
 * Date: 21.12.16
 * Time: 13:41
 */

namespace DHCache;

use Bitrix\Main\Config;

class CacheEngineMemcached implements ICacheEngine
{
    /*
     * @var obMemcached - connection to memcached.
     */
    private static $obMemcached = null;

    /*
     * @var isConnected -  is already connected
     */
    private static $isConnected = false;

    /*
     * @var sid - for using with several websites
     */
    private $sid = "BX";
    /*
     * @var read - bytes read
     */

    private $read = false;

    /*
     * @var written - bytes written
    */

    private $written = false;




    /*
     * Constructor
     */

    function __construct()
    {
        $cacheConfig = Config\Configuration::getValue("cache");

        if(self::$obMemcached == null)
        {
            self::$obMemcached = new \Memcached();

            if(isset($cacheConfig["hosts"]))
            {
                self::$isConnected=self::$obMemcached->addServers($cacheConfig["hosts"]);
            }
            else
            {
                self::$isConnected=self::$obMemcached->addServer("127.0.0.1","11211");
            }
        }

        if($cacheConfig && is_array($cacheConfig))
        {
            if(!empty($cacheConfig["sid"]))
            {
                $this->sid=$cacheConfig["sid"];
            }
        }
    }

    /*
     * Close connection
     */

    function close()
    {
        if(self::$obMemcached!=null)
            self::$obMemcached=null;
    }

    /*
     * Returns number of bytes read from memcached or false if there were no read operation
     */

    public function getReadBytes()
    {
        return $this->read;
    }

    /*
     * Returns number of bytes written to memcached or false if there were no write operation
     */

    public function getWrittenBytes()
    {
        return $this->written;
    }

    /*
     * Always return ""
     */
    public function getCachePath()
    {
        return "";
    }

    /*
     * Returns true if there's connection to memcached
     */
    public function isAvailable()
    {
        return self::$isConnected;
    }


}