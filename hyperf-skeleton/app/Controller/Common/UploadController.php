<?php


namespace App\Controller\Common;
use App\Constants\Constants;
use Carbon\Carbon;
use \ZYProSoft\Controller\AbstractController;
use ZYProSoft\Exception\HyperfCommonException;
use ZYProSoft\Http\AuthedRequest;
use App\Service\UploadService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController(prefix="/common/upload")
 * Class UploadController
 * @package App\Controller\Common
 */
class UploadController extends AbstractController
{
    /**
     * @Inject
     * @var UploadService
     */
    protected $service;

    /**
     * 必须是授权身份
     * @param AuthedRequest $request
     */
    public function uploadImage(AuthedRequest $request)
    {
        if (!$this->hasFile('upload')) {
            throw new HyperfCommonException(222,'no upload file use key upload been found');
        }
        $file = $request->file('upload');
        $systemType = config('hyperf-common.upload.system_type');
        if ($systemType == Constants::UPLOAD_SYSTEM_TYPE_LOCAL) {
            $localImageDir = config('hyperf-common.upload.local.image_dir');
            $localImagePublicUrl = config('hyperf-common.upload.local.url_prefix');
            $destinationPath = $localImageDir.DIRECTORY_SEPARATOR.Carbon::now()->getTimestamp().'.'.$file->getExtension();
            $result = $this->moveFileToPublic('upload', $destinationPath);
            if (!$result) {
                throw new HyperfCommonException(444,'upload image fail!');
            }
            $publicImageUrl = $localImagePublicUrl.$destinationPath;
            return  $this->success([
                'url' => $publicImageUrl
            ]);
        }
        //不传本地就传七牛云，其他的后面再说吧
        $result = $this->service->uploadLocalFileToQiniu($file->getRealPath());
        return $this->success($result);
    }

    public function getUploadImageToken(AuthedRequest $request)
    {
        $this->validate([
            'fileKey' => 'string|required|min:1'
        ]);
        $fileKey = $request->param('fileKey');
        $result = $this->service->getQiniuImageUploadToken($fileKey);
        return $this->success($result);
    }

    public function getUploadToken(AuthedRequest $request)
    {
        $this->validate([
            'fileKey' => 'string|required|min:1'
        ]);
        $fileKey = $request->param('fileKey');
        $result = $this->service->getQiniuCommonUploadToken($fileKey);
        return $this->success($result);
    }
}