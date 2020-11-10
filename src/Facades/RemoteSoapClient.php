<?php
/**
 * Created by PhpStorm.
 * User: admin
 * CreateTime: 2020/10/26 上午11:43
 */
namespace RemoteClient\RemoteSoap\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \RemoteClient\RemoteSoap\RemoteSoapClient remote_client();
 * @method static \RemoteClient\RemoteSoap\RemoteSoapClient updateUserPlatform();
 * @method static \RemoteClient\RemoteSoap\RemoteSoapClient redundantData(array $data);
 * @method static \RemoteClient\RemoteSoap\RemoteSoapClient setSendData($mobile,$ifMultiple = false);
 * Class RemoteSoapClient
 * @package RemoteClient\RemoteSoap\Facades
 */
class RemoteSoapClient extends Facade
{
   protected static function getFacadeAccessor()
   {
        return 'RemoteSoapClient';
   }
}
