<?php
/**
 * Imagick图像处理类
 * Last Modify By skyeinfo@qq.com
 */

class ImagickLab
{
    private $image = null;
    private $type = null;

    public function __construct(){}

    public function __destruct() {
        if($this->image !== null) {
            $this->image->destroy();
        }
    }

    /**
     * 支持path为url
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/20
     * @lastModify skyeinfo@qq.com
     * @param $path
     * @return Imagick|null
     * @throws ImagickException
     */
    public function open($path) {
        $this->image = new Imagick($path);
        if($this->image) {
            $this->type = strtolower($this->image->getImageFormat());
        }
        return $this->image;
    }

    /**
     * 裁剪图片
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/20
     * @lastModify skyeinfo@qq.com
     * @param int $x
     * @param int $y
     * @param null $width
     * @param null $height
     * @throws ImagickException
     */
    public function cropImage($x = 0, $y = 0, $width = null, $height = null) {

        if($width==null) $width = $this->image->getImageWidth() - $x;
        if($height==null) $height = $this->image->getImageHeight() - $y;
        if($width<=0 || $height<=0) return;

        if($this->type == 'gif') {
            $image = $this->image;
            $canvas = new Imagick();

            $images = $image->coalesceImages();
            foreach($images as $frame){
                $img = new Imagick();
                $img->readImageBlob($frame);
                $img->cropImage($width, $height, $x, $y);

                $canvas->addImage($img);
                $canvas->setImageDelay($img->getImageDelay());
                $canvas->setImagePage($width, $height, 0, 0);
            }

            $image->destroy();
            $this->image = $canvas;
        } else {
            $this->image->cropImage($width, $height, $x, $y);
        }
    }

    /**
     * 更改图像大小
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/20
     * @lastModify skyeinfo@qq.com
     * @param int $width
     * @param int $height
     * @param string $fit
     * @param array $fill_color
     * @throws ImagickException
     */
    public function resizeImage($width = 100, $height = 100, $fit = 'center', $fill_color = array(255,255,255,0)) {

        switch($fit) {
            case 'force':
                if($this->type=='gif') {
                    $image = $this->image;
                    $canvas = new Imagick();

                    $images = $image->coalesceImages();
                    foreach($images as $frame){
                        $img = new Imagick();
                        $img->readImageBlob($frame);
                        $img->thumbnailImage( $width, $height, false );

                        $canvas->addImage( $img );
                        $canvas->setImageDelay( $img->getImageDelay() );
                    }
                    $image->destroy();
                    $this->image = $canvas;
                } else {
                    $this->image->thumbnailImage( $width, $height, false );
                }
                break;
            case 'scale':
                if($this->type=='gif') {
                    $image = $this->image;
                    $images = $image->coalesceImages();
                    $canvas = new Imagick();
                    foreach($images as $frame) {
                        $img = new Imagick();
                        $img->readImageBlob($frame);
                        $img->thumbnailImage( $width, $height, true );

                        $canvas->addImage( $img );
                        $canvas->setImageDelay( $img->getImageDelay() );
                    }
                    $image->destroy();
                    $this->image = $canvas;
                } else {
                    $this->image->thumbnailImage( $width, $height, true );
                }
                break;
            case 'scale_fill':
                $size = $this->image->getImagePage();
                $src_width = $size['width'];
                $src_height = $size['height'];

                $x = 0;
                $y = 0;

                $dst_width = $width;
                $dst_height = $height;

                if($src_width*$height > $src_height*$width) {
                    $dst_height = intval($width*$src_height/$src_width);
                    $y = intval( ($height-$dst_height)/2 );
                } else {
                    $dst_width = intval($height*$src_width/$src_height);
                    $x = intval( ($width-$dst_width)/2 );
                }

                $image = $this->image;
                $canvas = new Imagick();

                $color = 'rgba('.$fill_color[0].','.$fill_color[1].','.$fill_color[2].','.$fill_color[3].')';
                if($this->type=='gif') {
                    $images = $image->coalesceImages();
                    foreach($images as $frame) {
                        $frame->thumbnailImage( $width, $height, true );

                        $draw = new ImagickDraw();
                        $draw->composite($frame->getImageCompose(), $x, $y, $dst_width, $dst_height, $frame);

                        $img = new Imagick();
                        $img->newImage($width, $height, $color, 'gif');
                        $img->drawImage($draw);

                        $canvas->addImage( $img );
                        $canvas->setImageDelay( $img->getImageDelay() );
                        $canvas->setImagePage($width, $height, 0, 0);
                    }
                } else {
                    $image->thumbnailImage( $width, $height, true );

                    $draw = new ImagickDraw();
                    $draw->composite($image->getImageCompose(), $x, $y, $dst_width, $dst_height, $image);

                    $canvas->newImage($width, $height, $color, $this->get_type() );
                    $canvas->drawImage($draw);
                    $canvas->setImagePage($width, $height, 0, 0);
                }
                $image->destroy();
                $this->image = $canvas;
                break;
            default:
                $size = $this->image->getImagePage();
                $src_width = $size['width'];
                $src_height = $size['height'];

                $crop_w = $src_width;
                $crop_h = $src_height;

                if($src_width * $height > $src_height * $width) {
                    $crop_w = intval($src_height * $width / $height);
                } else {
                    $crop_h = intval($src_width * $height/$width);
                }

                switch($fit) {
                    case 'north_west':
                        $crop_x = 0;
                        $crop_y = 0;
                        break;
                    case 'north':
                        $crop_x = intval(($src_width - $crop_w) / 2);
                        $crop_y = 0;
                        break;
                    case 'north_east':
                        $crop_x = $src_width - $crop_w;
                        $crop_y = 0;
                        break;
                    case 'west':
                        $crop_x = 0;
                        $crop_y = intval(($src_height-$crop_h) / 2);
                        break;
                    case 'center':
                        $crop_x = intval(($src_width-$crop_w) / 2);
                        $crop_y = intval(($src_height-$crop_h) / 2);
                        break;
                    case 'east':
                        $crop_x = $src_width-$crop_w;
                        $crop_y = intval(($src_height - $crop_h) / 2);
                        break;
                    case 'south_west':
                        $crop_x = 0;
                        $crop_y = $src_height-$crop_h;
                        break;
                    case 'south':
                        $crop_x = intval(($src_width - $crop_w) / 2);
                        $crop_y = $src_height - $crop_h;
                        break;
                    case 'south_east':
                        $crop_x = $src_width - $crop_w;
                        $crop_y = $src_height - $crop_h;
                        break;
                    default:
                        $crop_x = intval(($src_width - $crop_w) / 2);
                        $crop_y = intval(($src_height - $crop_h) / 2);
                }

                $image = $this->image;
                $canvas = new Imagick();

                if($this->type=='gif') {
                    $images = $image->coalesceImages();
                    foreach($images as $frame){
                        $img = new Imagick();
                        $img->readImageBlob($frame);
                        $img->cropImage($crop_w, $crop_h, $crop_x, $crop_y);
                        $img->thumbnailImage($width, $height, true);

                        $canvas->addImage($img);
                        $canvas->setImageDelay($img->getImageDelay());
                        $canvas->setImagePage($width, $height, 0, 0);
                    }
                } else {
                    $image->cropImage($crop_w, $crop_h, $crop_x, $crop_y);
                    $image->thumbnailImage($width, $height, true);
                    $canvas->addImage($image);
                    $canvas->setImagePage($width, $height, 0, 0);
                }
                $image->destroy();
                $this->image = $canvas;
        }
    }

    /**
     * 添加水印图片
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/20
     * @lastModify skyeinfo@qq.com
     * @param $path
     * @param int $x
     * @param int $y
     * @throws ImagickException
     */
    public function addWaterMarkPic($path, $x = 0, $y = 0) {
        $watermark = new Imagick($path);
        $draw = new ImagickDraw();
        $draw->composite($watermark->getImageCompose(), $x, $y, $watermark->getImageWidth(), $watermark->getimageheight(), $watermark);

        if ($this->type=='gif') {
            $image = $this->image;
            $canvas = new Imagick();
            foreach($image as $frame) {
                $img = new Imagick();
                $img->readImageBlob($frame);
                $img->drawImage($draw);

                $canvas->addImage($img);
                $canvas->setImageDelay($img->getImageDelay());
            }
            $image->destroy();
            $this->image = $canvas;
        } else {
            $this->image->drawImage($draw);
        }
    }

    /**
     * 添加水印文字
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/20
     * @lastModify skyeinfo@qq.com
     * @param $text
     * @param int $x
     * @param int $y
     * @param int $angle
     * @param array $style
     */
    public function addWaterMarkText($text, $x = 0 , $y = 0, $angle = 0, $style=array()) {
        $draw = new ImagickDraw();
        if(isset($style['font'])) $draw->setFont($style['font']);
        if(isset($style['font_size'])) $draw->setFontSize($style['font_size']);
        if(isset($style['fill_color'])) $draw->setFillColor($style['fill_color']);
        if(isset($style['under_color'])) $draw->setTextUnderColor($style['under_color']);

        if($this->type=='gif') {
            foreach($this->image as $frame) {
                $frame->annotateImage($draw, $x, $y, $angle, $text);
            }
        } else {
            $this->image->annotateImage($draw, $x, $y, $angle, $text);
        }
    }

    /**
     * 保存到指定路径
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/20
     * @lastModify skyeinfo@qq.com
     * @param $path
     */
    public function save($path){
        if ($this->type=='gif') {
            $this->image->writeImages($path, true);
        } else {
            $this->image->writeImage($path);
        }
    }

    /**
     * HTTP输出图像
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/20
     * @lastModify skyeinfo@qq.com
     * @param bool $header
     */
    public function output($header = true) {
        if($header) header('Content-type: '.$this->type);
        echo $this->image->getImagesBlob();
    }

    /**
     * 获取宽度
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     * @return mixed
     */
    public function getWidth() {
        $size = $this->image->getImagePage();
        return $size['width'];
    }

    /**
     * 获取高度
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     * @return mixed
     */
    public function getHeight() {
        $size = $this->image->getImagePage();
        return $size['height'];
    }

    /**
     * 设置图像类型， 默认与源类型一致
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     * @param string $type
     */
    public function set_type($type = 'png') {
        $this->type = $type;
        $this->image->setImageFormat( $type );
    }

    /**
     * 获取源图像类型
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     * @return null
     */
    public function getType() {
        return $this->type;
    }

    /**
     * 当前对象是否为图片
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     * @return bool
     */
    public function isImage() {
        if($this->image) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 生成缩略图 $fit为真时将保持比例并在安全框 $width X $height 内生成缩略图片
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     * @param int $width
     * @param int $height
     * @param bool $fit
     */
    public function thumbnail($width = 100, $height = 100, $fit = true) {
        $this->image->thumbnailImage( $width, $height, $fit );
    }

    /**
     * 添加一个边框
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     * @param $width
     * @param $height
     * @param string $color
     */
    public function addBorder($width, $height, $color = 'rgb(220, 220, 220)') {
        $color = new ImagickPixel();
        $color->setColor($color);
        $this->image->borderImage($color, $width, $height);
    }

    /**
     * 模糊处理
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     * @param $radius
     * @param $sigma
     */
    public function blur($radius, $sigma){
        $this->image->blurImage($radius, $sigma);
    }

    /**
     * 高斯模糊
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     * @param $radius
     * @param $sigma
     */
    public function gaussianBlur($radius, $sigma) {
        $this->image->gaussianBlurImage($radius, $sigma);
    }

    /**
     * 运动模糊
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     * @param $radius
     * @param $sigma
     * @param $angle
     */
    public function motionBlur($radius, $sigma, $angle) {
        $this->image->motionBlurImage($radius, $sigma, $angle);
    }

    /**
     * 径向模糊
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     * @param $radius
     */
    public function radialBlur($radius){
        $this->image->radialBlurImage($radius);
    }

    /**
     * 添加噪点
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     * @param null $type
     */
    public function add_noise($type=null) {
        $this->image->addNoiseImage($type==null ? imagick::NOISE_IMPULSE : $type);
    }

    /**
     * 调整色阶
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     * @param $black_point
     * @param $gamma
     * @param $white_point
     */
    public function level($black_point, $gamma, $white_point) {
        $this->image->levelImage($black_point, $gamma, $white_point);
    }

    /**
     * 调整亮度、饱和度、色调
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     * @param $brightness
     * @param $saturation
     * @param $hue
     */
    public function modulate($brightness, $saturation, $hue) {
        $this->image->modulateImage($brightness, $saturation, $hue);
    }

    /**
     * 素描
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     * @param $radius
     * @param $sigma
     */
    public function charcoal($radius, $sigma) {
        $this->image->charcoalImage($radius, $sigma);
    }

    /**
     * 油画效果
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     * @param $radius
     */
    public function oilPaint($radius) {
        $this->image->oilPaintImage($radius);
    }

    /**
     * 水平翻转
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     */
    public function flop() {
        $this->image->flopImage();
    }

    /**
     * 垂直翻转
     * @author skyeinfo@qq.com
     * @lastModifyTime 2018/8/21
     * @lastModify skyeinfo@qq.com
     */
    public function flip() {
        $this->image->flipImage();
    }
}