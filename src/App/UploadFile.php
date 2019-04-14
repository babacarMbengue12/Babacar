<?php
/**
 * Created by PhpStorm.
 * User: Babacar Mbengue
 * Date: 28/11/2018
 * Time: 17:51
 */

namespace Babacar;


use Intervention\Image\ImageManager;
use Psr\Http\Message\UploadedFileInterface;

class UploadFile
{
    protected $path;

    protected $format = [];


    public function upload(?UploadedFileInterface $file=null, ?string $path=null)
    {
        if (is_null($file) || $file->getError() !== UPLOAD_ERR_OK) {
            return null;
        }

        if (!file_exists($this->path)) {
            mkdir($this->path, 777, true);
        }

         $filename = $path ? $path : $this->path.DIRECTORY_SEPARATOR.$file->getClientFilename();
         $filename = $this->rename($filename);
         $filename = $this->addCopySoufix($filename);
         $file->moveTo($filename);
        $this->generateFormat($filename);

         return pathinfo($filename)['basename'];

    }//end upload()


    public function delete(?string $filename=null)
    {

        if ($filename === null || empty($filename)) {
            return;
        }

        $filename = pathinfo($filename)['basename'];
        $filename = $this->path.DIRECTORY_SEPARATOR.$filename;
        if (file_exists($filename)) {
            unlink($filename);
        }

        foreach ($this->format as $format => $_) {
            $destination = $this->getPathWithSoufix($filename, $format);
            if (file_exists($destination)) {
                unlink($destination);
            }
        }

    }//end delete()


    /**
     * @param string $filename
     * @param string $soufix
     */
    private  function addCopySoufix(string $filename, string $soufix='copy')
    {
        if (file_exists($filename)) {
            $filename = $this->getPathWithSoufix($filename, $soufix);
            return $this->addCopySoufix($filename, $soufix);
        }

        return $filename;

    }//end addCopySoufix()


    private function getPathWithSoufix(string $path, string $soufix)
    {
        $infos = pathinfo($path);
        return $infos['dirname'].DIRECTORY_SEPARATOR.$infos['filename'].'_'.$soufix.'.'.$infos['extension'];

    }//end getPathWithSoufix()


    private function rename(string $filename)
    {
            $infos = pathinfo($filename);
            return $infos['dirname'].DIRECTORY_SEPARATOR.uniqid($infos['filename']).'.'.$infos['extension'];

    }//end rename()


    private function generateFormat($filename)
    {

        foreach ($this->format as $format => [$width, $height]) {
                $destination = $this->getPathWithSoufix($filename, $format);

                $manager = new ImageManager(['driver' => 'gd']);
                $manager->make($filename)->fit($width, $height)->save($destination);
        }

    }//end generateFormat()


}//end class
