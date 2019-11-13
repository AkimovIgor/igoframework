<?php

namespace Igoframework\Core\Cache;

class CacheManager
{
    public function setCache($key, $data, $lifeTime = 3600)
    {
        $content['data'] = $data;
        $content['life_time'] = time() + $lifeTime;
        $file = ROOT . '/tmp/cache/' . md5(md5($key)) . '.txt';
        if (file_put_contents($file, serialize($content))) {
            return true;
        }
        return false;
    }

    public function getCache($key)
    {
        $file = ROOT . '/tmp/cache/' . md5(md5($key)) . '.txt';
        if (file_exists($file)) {
            $content = unserialize(file_get_contents($file));
            if (time() <= $content['life_time']) {
                return $content['data'];
            }
            unlink($file);
        }
        return false;
    }

    public function deleteCache($key)
    {
        $file = ROOT . '/tmp/cache/' . md5(md5($key)) . '.php';
        if (file_exists($file)) {
            unlink($file);
        }
        return false;
    }
}