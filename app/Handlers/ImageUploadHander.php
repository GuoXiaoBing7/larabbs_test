<?php

namespace App\Handlers;
use Image;
class ImageUploadHander{

    //只允许以下后缀名的图片文件上传
    protected $allowed_ext = ['png','jpg','gif','jpeg'];

    public function save($file,$folder,$file_prefix,$max_width=false)
    {
        //构建传输图片储存的文件夹
        $folder_name = "uploads/images/$folder/".date('Ym/d',time());
        //详细迁移路径
        $upload_path = public_path().'/'.$folder_name;
        //获取图片后缀
        $extension =strtolower($file->getClientOriginalExtension())?:'png';

        //拼接文件名
        $filename = $file_prefix.'_'.time().'_'.str_random(10).'.'.$extension;

        //处理图片
        if (!in_array($extension,$this->allowed_ext)){
            return false;
        }

        //移动图片
        $file->move($upload_path,$filename);

        // 如果限制了图片宽度，就进行裁剪
        if ($max_width && $extension != 'gif') {

            // 此类中封装的函数，用于裁剪图片
            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }
        return [
          'path'=>config('app.url')."/$folder_name/$filename",
        ];
    }


    public function reduceSize($file_path, $max_width)
    {
        // 先实例化，传参是文件的磁盘物理路径
        $image = Image::make($file_path);

        // 进行大小调整的操作
        $image->resize($max_width, null, function ($constraint) {

            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        // 对图片修改后进行保存
        $image->save();
    }
}