<?php

namespace App\Helpers;

class FileHelper {

    public static function uploadFile($file,$location,$dataFile=null)
    {
        if ($file) {
            $fileName = time() . $file->getClientOriginalName();
            $file->move('storage/'.$location.'/', $fileName);
            if ($dataFile) {
                unlink('storage/'.$location.'/' . $dataFile);
            }

            return $fileName;
        }
    }

}