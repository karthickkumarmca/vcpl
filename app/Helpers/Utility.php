<?php

use App\Models\Setting;
use App\Exceptions\Handler;

function generateVideoUploadTimings($startingTime, $endingTime, $noOfVideoUploadsPerDay) {
    $timings = [];
    if ($startingTime < $endingTime) {
        /**
         * Time difference between each video upload should be minimum of 15 mins. If doesn't meet the condition the
         * reduce the video uploading times for the day
         */
        $totalMins               = calculateMinutesFromTiming($startingTime->diff($endingTime));
        $timeElapseBwVideoUpload = ceil($totalMins / config('general_settings.minutes_between_video_uploads'));
        if ($timeElapseBwVideoUpload < $noOfVideoUploadsPerDay) {
            $noOfVideoUploadsPerDay = $timeElapseBwVideoUpload;
        }

        $startingHour            = $startingTime->format('H');
        $endingHour              = $endingTime->format('H');
        $startingMinute          = 0;
        $endingMinute            = 60;
        $totalFailureAttempts    = 0;
        while (count($timings) < $noOfVideoUploadsPerDay) {
            $date = clone $startingTime;
            $date->setTime(rand($startingHour, $endingHour), rand($startingMinute, $endingMinute));
            if (
                !(
                    (
                        $startingTime->format('Y-m-d H:i:s') < $date->format('Y-m-d H:i:s')
                    ) &&
                    (
                        $date->format('Y-m-d H:i:s') < $endingTime->format('Y-m-d H:i:s')
                    )
                )
            ) {
                continue;
            }

            $isNewTimingOk = true;
            foreach ($timings AS $timing) {
                $elapsingMinutes = calculateMinutesFromTiming($date->diff($timing));
                if ($elapsingMinutes < config('general_settings.minutes_between_video_uploads')) {
                    $isNewTimingOk = false;
                    break;
                }
            }

            if ($isNewTimingOk) {
                $timings[] = $date;
            }
            else {
                //If the finding new timings takes more than 50 attempts just break the loop
                $totalFailureAttempts++;
                if ($totalFailureAttempts > 50) {
                    break;
                }
            }
        }
    }

    return $timings;
}

function calculateMinutesFromTiming($timing) {
    return ($timing->days * 24 * 60) + ($timing-> h * 60) + $timing->i;
}
function getBasedataFromFile($url, $filename)
{
    if(get_http_response_code($url) == config('status_code.httpSuccessCode')) {

        $fileResponse = [
            'data'      => '',
            'filename'  => $filename
        ];
        $type   = pathinfo($url, PATHINFO_EXTENSION);
        $contextOptions = [
            "ssl" => [
                "verify_peer"       => false,
                "verify_peer_name"  => false
            ]
        ];
        if ($imgData = file_get_contents($url, false, stream_context_create($contextOptions))) {
            $fileResponse['data'] = 'data:application/' . $type . ';base64,' . base64_encode($imgData);
            if(empty($type)) {
                $file_info = new \finfo(FILEINFO_MIME_TYPE);
                $mime_type = $file_info->buffer($imgData);
                if($mime_type) {
                    $mimeTypeExploded = explode('/', $mime_type);
                    if(isset($mimeTypeExploded[1])) {
                        $fileResponse['filename'] = $filename.".".$mimeTypeExploded[1];
                    }
                }
            } else {
                $fileResponse['filename'] = $filename.".".$type;
				
            }
        }
		
        return $fileResponse;
    }
    else
    {
		
        return $fileResponse = ['data'=> '','filename'  =>''];
		
    }
}
function get_http_response_code($url) {
    //$headers = get_headers($url);
    //return substr($headers[0], 9, 3);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $data=array(
            'url'=>$url,
            'httpCode'=>$httpCode,          
        );
    $log = new Log();
    Log::channel('request')->notice(json_encode($data));
    return $httpCode;
    //if( $httpCode == 200 ){return true;}
}