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
