<?php
class ImageMagick
{

    /**
     * Resizes by width and crop by height
     * @param string $src
     * @param string $dst
     * @param array $options, e.g. array('width' => 495, 'height' => 68)
     */
    public function resizeAndCrop($src, $dst, $options, $quality=100)
    {
        $str = 'convert ' . $this->_imLimits() . ' -quality ' . $quality . '% -resize "' . $options['width'] . 'x"  -crop "' . $options['width'] . 'x' . $options['height'] . '+0+0" +repage "' . $src . '" "' . $dst . '"';
        //print_r($str);exit;
        exec($str, $output, $ret);
        if (!$ret) {
            return true;
        }
        return false;
    }

    public function processGif($src)
    {
        $str = "convert " . $this->_imLimits() . " '$src'  -delete 1--1 '$src'";
        //print_r($str);
        exec($str, $out, $ret);
        if (!$ret) {
            return true;
        }
        return false;
    }

    public function resize($src, $dst, $options)
    {
        exec('convert ' . $this->_imLimits() . ' -quality 100% -resize "' . $options['width'] . 'x' . $options['height'] . '" "' . $src . '" "' . $dst . '"', $output, $ret);
        if (!$ret) {
            return true;
        }
        return false;
    }

    public function resizeMaxAndCrop($src, $dst, $options)
    {
        //$res = $options['width']>$options['width']?$options['width'].'x':'x'.$options['height'];
        $res = $options['width'].'x'.$options['height'];
        $str = 'convert ' . $this->_imLimits() . ' -quality 100% -resize "' . $res . '"^   -crop "' . $options['width'] . 'x' . $options['height'] . '+0+0" +repage "' . $src . '" "' . $dst . '"';
        //print_r($str);exit;
        exec($str, $output, $ret);
        //print_r($output);exit;
        if (!$ret) {
            return true;
        }
        return false;
    }

    public function cropAndResizeDoll($src, $dst, $options)
    {
        $res = $options['width'].'x'.$options['height'];
        $str = 'convert ' . $this->_imLimits() . ' -quality 100% -resize "' . $res . '"^   -crop "' . $options['cwidth'] . 'x' . $options['cheight'] . '+'.$options['offset_left'].'+'.$options['offset_top'].'" +repage "' . $src . '" "' . $dst . '"';
        exec($str, $output, $ret);
        //print_r($output);exit;
        if (!$ret) {
            return true;
        }
        return false;
    }

    public function resizeNoPropotions($src, $dst, $options, $quality=100)
    {
        exec('convert ' . $this->_imLimits() . ' -quality ' . $quality . '% -resize ' . $options['width'] . 'x' . $options['height'] . '\! "' . $src . '" "' . $dst . '"', $output, $ret);
        if (!$ret) {
            return true;
        }
        return false;
    }

    public function split($src, $dst, $partsNumber)
    {
        exec('convert ' . $this->_imLimits() . ' -quality 100% -crop "' . 100 / $partsNumber . '%x100%" "' . $src . '" +repage "' . $dst . '"', $output, $ret);
        if (!$ret) {
            return true;
        }
        return false;
    }

    public function addWatermark($file_path, $place = 'SouthWest', $watermark_path = null)
    {
        if (!$watermark_path) {
            //$watermark_path = FCPATH . 'img/water.png';
        }

        exec("convert " . $this->_imLimits() . " -quality 100%  $file_path $watermark_path -gravity $place -composite $file_path 2>&1", $out, $ret);
        if (!$ret) {
            return true;
        }
        return false;
    }


    public function getGradient($color1, $filepath, $color2 = "#FFFFFF", $size = "10x100")
    {
        $str = "convert " . $this->_imLimits() . " -quality 100% -size $size  gradient:'" . $color1 . "'-'" . $color2 . "' $filepath";
        //print_r($str);
        exec($str, $out, $ret);
        if (!$ret) {
            return true;
        }
        return false;
    }

    public function getColoredArrow($colorPath, $arrowPath, $filePath)
    {
        $str = "convert " . $this->_imLimits() . " -quality 100% $arrowPath $colorPath -clut $filePath";
        //print_r($str);
        exec($str, $out, $ret);
        if (!$ret) {
            return true;
        }
        return false;
    }

    public function createText($options, $imagePath, $libPath)
    {
        $font = $options['font'];
        $text = $options['text'];
        $size = $options['size'];
        $color1 = $options['colors']['color1'];
        $color2 = $options['colors']['color2'];
        $textToolPath = $libPath . "texteffect";
        $exec_str = "bash $textToolPath -t '$text' -f '$font' -b none -p $size -c '$color1'-'$color2' -A 0 '$imagePath'";
        //print_r($exec_str);exit;
        exec($exec_str, $output, $ret);
        if (!$ret) {
            return true;
        }
        return false;
    }

    public function mergeImages($mainPath, $imagePath, $targetPath, $position)
    {
        $geometry = "+" . round($position['left']) . "+" . round($position['top']);
        $exec_str = "convert " . $this->_imLimits() . " -quality 100% '$mainPath' '$imagePath'  -geometry $geometry -composite '$targetPath' 2>&1";
        exec($exec_str, $output, $ret);
        if (!$ret) {
            return true;
        }
        return false;
    }

    public function thumbCreate($imagePath,$thumbPath)
    {
        $str = "convert -quality 90% -resize 625x232 '$imagePath' '$thumbPath'";
        exec($str);
    }


    // Delete image-magick temp files
    // Don't call this function on every image operation as it is a performance hit. We'll schedule a cron job
    public function deleteTmp()
    {
        $execStr = "du -a /tmp | sort -n -r | head -n 10";
        exec($execStr, $output, $ret);
        if (!$ret) {
            foreach ($output as $row) {
                $file = explode("\t", $row);
                if (count($file) == 2 && (int)$file[0] > 10000000 && $file[1] != '/tmp') {
                    exec("rm -rf " . $file[1], $output, $ret);
                    //print_r($output);
                }
            }
        }

    }



    public function cropSquareImages($images, $options)
    {
        foreach ($images as $image) {
            $this->resizeMaxAndCrop($image, $image, $options);
        }
    }

    public function montage($images, $filePath)
    {
        $list = '';
        foreach ($images as $image) {
            $list .= " '$image'";
        }

        $execStr = "montage $list -tile 8x3 -geometry +0+0 '$filePath'";

        exec($execStr);
    }


    public function rotate($image, $angle, $trg = null)
    {
        if (!$trg) {
            $trg = $image;
        }

        $execStr = "convert  -background none -rotate $angle $image $trg";

        exec($execStr);
    }

    /*
    array(
        'size'=>
        'trg'=>

        'images'=>array(
            array(
                'file'=>
                'startpoint'=>array('x'=>, 'y'=>)
            )
        )
    )
    */

    public function montageCompl($options, $type)
    {
        exec("convert -size {$options['size']} xc:none ".$options['trg']);

        foreach ($options[$type] as $image) {
            exec("composite  -geometry +{$image['startpoint']['x']}+{$image['startpoint']['y']} {$image['file']} {$options['trg']} {$options['trg']}");
        }

    }

    public function glue($image1, $image2, $trg)
    {
        exec("composite $image1 $image2 $trg");
    }

    public function toJpeg($path) {
        $name = explode('/', $path);
        $realName = $name[count($name)-1];
        $realNameEx = explode('.', $realName);
        $newName = $realNameEx[0] . '.jpg';
        $newPath = str_replace($realName, $newName, $path);

        exec("convert $path $newPath");
        return $newName;
    }


    public function addBorder($options, $imagePath, $libPath) {
        $borderToolPath = $libPath . "bordergrid";
        //-s 10 -t 2 -o 4 -d 1 -a 45 -b 0 -c white
        $s = isset($options['size'])?$options['size']:10;
        $t = isset($options['thickness'])?$options['thickness']:2;
        $o = isset($options['offset'])?$options['offset']:4;
        $d = isset($options['dimension'])?$options['dimension']:1;
        $a = isset($options['angle'])?$options['angle']:45;
        $b = isset($options['colors'])?$options['colors']:0;
        $c = isset($options['blur'])?$options['blur']:'white';

        $exec_str = "bash $borderToolPath -s $s -t $t -o $o -d $d -a $a -b $b -c $c '$imagePath' '$imagePath'";

        exec($exec_str, $output, $ret);
        if (!$ret) {
            return true;
        }
        return false;
    }



    private function _imLimits()
    {
        //return " -limit area 1000MiB -limit disk 1000MiB -limit file 1000MiB -limit map 1000MiB -limit memory 1000MiB ";
        return "";
    }







}


