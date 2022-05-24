<?php

namespace App\Helpers;

class ImageHelper {

    public static function uploadImage($image,$location,$dataImage=null)
    {
        if ($image) {
            $imageName = time() . $image->getClientOriginalName();
            $image->move('storage/'.$location.'/', $imageName);
            if ($dataImage) {
                unlink('storage/'.$location.'/' . $dataImage);
            }

            return $imageName;
        }
    }

}