<?php

namespace Dcat\Admin\Extension\Config\Http\Controllers;

use Dcat\Admin\Traits\HasUploadedFile;

Class FileController
{
    use HasUploadedFile;

    public function handle()
    {

        $disk = $this->disk();
        // 判断是否是删除文件请求
        if ($this->isDeleteRequest()) {
            // 删除文件并响应
            return $this->deleteFileAndResponse($disk);
        }

        // 获取上传的文件
        $file = $this->file();

        // 获取上传的字段名称
        $column = $this->uploader()->upload_column;

        $dir = 'app-files';
        $newName = $column . time() . '.' . $file->getClientOriginalExtension();

        $result = $disk->putFileAs($dir, $file, $newName);

        $path = "{$dir}/$newName";

        return $result
            ? $this->responseUploaded($path, $disk->url($path))
            : $this->responseErrorMessage('文件上传失败');
    }

}
