<?php
/**
 * Created by PhpStorm.
 * User: Linux
 * CreateTime: 2020/11/3 下午2:45
 */

namespace RemoteClient\RemoteSoap\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use RemoteClient\RemoteSoap\Facades\RemoteSoapClient;

class HandleUserUniqid implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 120;
    public $tries = 3;
    //错误重试次数
    protected $errorRetryCount = 0;
    protected $user;
    protected $mobile;
    protected $nums;

    /**
     * HandleUserData constructor.
     * @param array $user        用户信息
     * @param array $mobile      手机号
     * @param int   $nums        每次最多执行个数(默认500)
     */
    public function __construct($user,$mobile,$nums=500)
    {
        $this->user = $user;
        $this->mobile = $mobile;
        $this->nums = $nums;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(count($this->mobile) > $this->nums)
        {
            $mobile = array_chunk($this->mobile,$this->nums);
        }else{
            $mobile = $this->mobile;
        }
        if(count($mobile) != count($mobile,1))
        {
            foreach ($mobile as $v)
            {
                $this->createUid($v);
            }
        }else{
            $this->createUid($mobile);
        }
    }


    private function createUid($mobile)
    {
        $data = $this->getRemoteData($mobile);
        if(!empty($data))
        {
            $data = json_decode($data,true);
            $returnData = $data['data'];
            $sql = "INSERT INTO `users` (`mobile`,`uid`) VALUES ";
            $mobiles = [];
            foreach ($returnData as $item)
            {
                $sql .= "(".$item['mobile'].",".$item['uid']."),";
                $mobiles[$item['mobile']] = $item['uid'];
            }
            $sql = rtrim($sql,',');
            $sql .= " ON DUPLICATE KEY UPDATE uid=VALUES(`uid`);";
            try {
                DB::insert($sql);
            }catch (\Exception $e)
            {
               throw new \Exception($e->getMessage());
            }

        }
    }

    protected function getRemoteData($mobile)
    {
        $data = [];
        while ( $this->errorRetryCount <= 1 )
        {
            try {
                $data = RemoteSoapClient::setSendData($mobile,true)->remoteClient();
                return $data;
            }catch (\Exception $e)
            {
                $this->errorRetryCount += 1;
                throw new \Exception(json_encode($e->getMessage()));
            }
        }
        return [];
    }
}
