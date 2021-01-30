<?php


namespace App\Service;
use App\Constants\ErrorCode;
use Qiniu\Auth;
use ZYProSoft\Exception\HyperfCommonException;
use ZYProSoft\Service\AbstractService;

class UploadService extends AbstractService
{
    public function getQiniuImageUploadToken(string $fileKey)
    {
        $policy = [
            'insertOnly' => true,
            'mimeLimit' => 'image/*',
        ];
        return $this->getQiniuCommonUploadToken($fileKey, $policy);
    }

    public function getQiniuCommonUploadToken(string $fileKey, array $policy = null)
    {
        $accessKey = config('file.qiniu.accessKey');
        $secretKey = config('file.qiniu.secretKey');
        $domain = config('file.qiniu.domain');
        $bucket = config('file.qiniu.bucket');
        if (empty($accessKey) || empty($secretKey) || empty($domain) || empty($bucket)) {
            throw new HyperfCommonException(ErrorCode::QINIU_UPLOAD_CONFIG_NOT_SET);
        }
        $auth = new Auth($accessKey, $secretKey);
        $ttl = config('hyperf-common.upload.qiniu.token_ttl', 3600);
        $token = $auth->uploadToken($bucket, $fileKey, $ttl, $policy);
        return ['token' => $token];
    }

    public function uploadLocalFileToQiniu(string $localPath)
    {
        $stream = fopen($localPath, 'r+');
        $result = $this->fileQiniu()->writeStream($localPath, $stream);
        fclose($stream);
        return $result;
    }
}