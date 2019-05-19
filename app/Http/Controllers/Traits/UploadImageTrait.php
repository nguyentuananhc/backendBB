<?php

namespace App\Http\Controllers\Traits;


trait UploadImageTrait
{
    public function upload($requestName, $prefix)
    {
        if (\request()->hasFile($requestName)) {
            $destination = '/images/' . $prefix;
            $image = \request()->file($requestName);
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path($destination);
            $image->move($destinationPath, $name);
            $uploadedLink = $destination . '/' . $name;

            return $uploadedLink;
        }

        return null;
    }
}
