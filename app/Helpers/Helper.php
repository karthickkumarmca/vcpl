<?php

namespace App\Helpers;

use App\Models\DistrictQuarantine;
use DateTime;
use DateInterval;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class Helper
{
    public static function getlogindata($str = '', $type = 1)
    {
        $string = '';
        if ($str != '') {
            if ($type == 1) {
                $string = decrypt($str, 'AES-128-ECB');
            } else {
                $string = encrypt($str, 'AES-128-ECB');
            }
        }
        return $string;
    }
    public static function getImageLink($image){

        if(is_array($image)){
            $img = [];
            foreach ($image as $key => $value) {
                $img[] = self::getCommonImageLink($value);
            }
            return $img;
        }
        else{
            return self::getCommonImageLink($image);
        }
    }
    public static function getCommonImageLink($image){
        $image      = str_replace(env('AWS_URL'), "", $image);
        $img        = $image;
        if($image!=''){
            $s3 = \Storage::disk('s3');
            $client = $s3->getDriver()->getAdapter()->getClient();
            $expiry = "+10080 minutes";

            $command = $client->getCommand('GetObject', [
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $image
            ]);

            $request = $client->createPresignedRequest($command, $expiry);
            $img = (string) $request->getUri();
        }
        return $img;
    }
    public static function getBasedataFromFile($url, $filename)
    {
        $fileResponse = [
            'data' => '',
            'filename' => $filename,
        ];
        $type = pathinfo($url, PATHINFO_EXTENSION);
        $contextOptions = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ];
        if ($imgData = file_get_contents($url, false, stream_context_create($contextOptions))) {
            $fileResponse['data'] = 'data:application/' . $type . ';base64,' . base64_encode($imgData);
            if (empty($type)) {
                $file_info = new \finfo(FILEINFO_MIME_TYPE);
                $mime_type = $file_info->buffer($imgData);
                if ($mime_type) {
                    $mimeTypeExploded = explode('/', $mime_type);
                    if (isset($mimeTypeExploded[1])) {
                        $fileResponse['filename'] = $filename . "." . $mimeTypeExploded[1];
                    }
                }
            } else {
                $fileResponse['filename'] = $filename . "." . $type;
            }
        }
        return $fileResponse;
    }

    public static function seoUrl($string) {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }
    public static function getDecryptedText($string){
        if(env('ENCRYPT_INPUT_VARIABLES') == 1){
            return Crypt::decryptString($string);
        }
        else{
            return $string;
        }
    }
}
