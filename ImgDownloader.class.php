<?php
/**
 * Created by PhpStorm.
 * User: Dominik
 * Date: 2019/1/20
 * Time: 19:51
 */

class ImgDownloader{
    private $ch;
    private $img_list;
    private $root;

    //初始化curl
    public function __construct($root){
        //初始化图片保存目录
        $this->root = $root;

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this->ch,CURLOPT_RETURNTRANSFER,1);
    }

    //使用正则获取图片地址列表
    public function get_img_list($url){
        curl_setopt($this->ch,CURLOPT_URL,$url);
        $ret = curl_exec($this->ch);
        $reg = '/src="(.+?\.jpg)/';
        preg_match_all($reg,$ret,$match);
        $this->img_list = $match[1];
    }

    public function crawl($url){
        $this->get_img_list($url);

        for($i=0;$i<count($this->img_list);$i++){
            curl_setopt($this->ch,CURLOPT_URL,$this->img_list[$i]);
            $img = curl_exec($this->ch);
            $img_path = $this->root.'/'.$i.'.jpg';
            file_put_contents($img_path,$img);
        }
        echo 'OK!!!';

    }

    public function close(){
        curl_close($this->ch);
    }

}


$spider = new ImgDownloader('ks');
$spider->crawl('https://movie.douban.com/celebrity/1324838/photos/');