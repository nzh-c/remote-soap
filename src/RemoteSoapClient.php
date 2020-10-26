<?php
/**
 * Created by PhpStorm.
 * User: admin
 * CreateTime: 2020/10/26 上午11:40
 */

namespace RemoteClient\RemoteSoap;


use Illuminate\Config\Repository;

class RemoteSoapClient
{
    protected $config = null;
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }
    public function remote_client()
    {
        return 'remoteSoapClient';
    }
}
