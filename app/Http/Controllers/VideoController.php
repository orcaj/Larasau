<?php

namespace App\Http\Controllers;

use App\Models\ContentPart;
use App\Models\Sell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Classes\VideoStream;
use Illuminate\Support\Facades\URL;

class VideoController extends Controller
{
    ## User Section ##
    public function stream($id){
        global $user;
        if(!$user)
            return back()->with('msg',trans('admin.login_to_play_video'));

        $part = ContentPart::with('content')->where('mode','publish')->find($id);
        if(!$part)
            abort(404);

        if($part->free == 0 && $user['id'] != $part->content->user_id) {
            $sell = Sell::where('buyer_id', $user['id'])->where('content_id', $part->content->id)->count();
            if ($sell == 0)
                abort(404);
        }


        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $file = $storagePath.'source/content-'.$part->content->id.'/video/part-'.$part->id.'.mp4';

        if(!file_exists($file))
            abort(404);

        $stream = new VideoStream($file);
        $stream->start();

    }
    public function streamAdmin($id){

        $part = ContentPart::with('content')->where('mode','publish')->find($id);
        if(!$part)
            abort(404);


        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $file = $storagePath.'source/content-'.$part->content->id.'/video/part-'.$part->id.'.mp4';

        if(!file_exists($file))
            abort(404);

        $stream = new VideoStream($file);
        $stream->start();

    }
    public function download($id){

        global $user;
        if(!$user)
            abort(404);

        $part = ContentPart::with('content')->where('mode','publish')->find($id);
        if(!$part)
            abort(404);

        if($part->free == 0) {
            $sell = Sell::where('buyer_id', $user['id'])->where('content_id', $part->content->id)->count();
            if ($sell == 0)
                abort(404);
        }

        if($part->content->download == 0)
            abort(404);

        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $file  = 'source/content-'.$part->content->id.'/video/part-'.$part->id.'.mp4';
        $file1 = 'source/content-'.$part->content->id.'/video/part-'.$part->id.'.pdf';
        $file2 = 'source/content-'.$part->content->id.'/video/part-'.$part->id.'.zip';
        $file3 = 'source/content-'.$part->content->id.'/video/part-'.$part->id.'.docx';
        $file4 = 'source/content-'.$part->content->id.'/video/part-'.$part->id.'.rar';
        $file5 = 'source/content-'.$part->content->id.'/video/part-'.$part->id.'.mp3';

        if(file_exists($storagePath.$file))
            return Response::download($storagePath.$file);
        if(file_exists($storagePath.$file1))
            return Response::download($storagePath.$file1);
        if(file_exists($storagePath.$file2))
            return Response::download($storagePath.$file2);
        if(file_exists($storagePath.$file3))
            return Response::download($storagePath.$file3);
        if(file_exists($storagePath.$file4))
            return Response::download($storagePath.$file4);
        if(file_exists($storagePath.$file5))
            return Response::download($storagePath.$file5);

        return back();

    }

    ## Admin Section ##
    public function progressBar($txt)
    {
        $content = @file_get_contents($txt);
        if($content){
            //get duration of source
            preg_match("/Duration: (.*?), start:/", $content, $matches);
            if(!isset($matches[1]))
                return 'error';
            $rawDuration = $matches[1];

            //rawDuration is in 00:00:00.00 format. This converts it to seconds.
            $ar = array_reverse(explode(":", $rawDuration));
            $duration = floatval($ar[0]);
            if (!empty($ar[1])) $duration += intval($ar[1]) * 60;
            if (!empty($ar[2])) $duration += intval($ar[2]) * 60 * 60;

            //get the time in the file that is already encoded
            preg_match_all("/time=(.*?) bitrate/", $content, $matches);

            $rawTime = array_pop($matches);

            //this is needed if there is more than one match
            if (is_array($rawTime)){$rawTime = array_pop($rawTime);}

            //rawTime is in 00:00:00.00 format. This converts it to seconds.
            $ar = array_reverse(explode(":", $rawTime));
            $time = floatval($ar[0]);
            if (!empty($ar[1])) $time += intval($ar[1]) * 60;
            if (!empty($ar[2])) $time += intval($ar[2]) * 60 * 60;

            //calculate the progress
            $progress = round(($time/$duration) * 100);

            //echo "Duration: " . $duration . "<br>";
            //echo "Current Time: " . $time . "<br>";
            //echo "Progress: " . $progress . "%";

            return $progress;

        }
    }
    public function Convert($id)
    {
        $part = ContentPart::find($id);
        if($part) {
            $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().'source/content-'.$part->content_id.'/video';
            $logPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().'source/log';
            if(!is_dir($storagePath)){
                File::makeDirectory($storagePath, $mode = 0777, true, true);
            }
            if(!is_dir($logPath)){
                File::makeDirectory($logPath, $mode = 0777, true, true);
            }
            $opening = getcwd().parse_url(rawurldecode('/logo.mpg') ,PHP_URL_PATH);
            $import = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().'source/temp/temp.mpg';
            $export = $storagePath.'/part-'.$part->id.'.mp4';
            $log = $logPath.'/block.txt';
            $logo = getcwd().get_option('video_watermark');
            //exec("ffmpeg -y -i $opening -i $import -c:v libx264 -b:v 1.5M -vf \"scale=1280:720:force_original_aspect_ratio=decrease,pad=1280:720:(ow-iw)/2:(oh-ih)/2\" -strict -2 -preset ultrafast $export 1> $log 2>&1");
            exec("cat $opening $import | ffmpeg -y -f mpeg -i - -qscale 0 -vcodec h264 $export 1> $log 2>&1");
            return Redirect::to(URL::previous().'#convert');
            //dd(file_get_contents($log));
        }
        else{
            abort(404);
        }
    }
    public function Convertlogo($id)
    {
        $part = ContentPart::find($id);
        if($part) {
            $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().'source/content-'.$part->content_id.'/video';
            $logPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().'source/log';
            $logo = getcwd().get_option('video_watermark');
            if(!is_dir($storagePath)){
                File::makeDirectory($storagePath, $mode = 0777, true, true);
            }
            if(!is_dir($logPath)){
                File::makeDirectory($logPath, $mode = 0777, true, true);
            }
            $import = getcwd(). parse_url(urlencode($part->upload_video) ,PHP_URL_PATH);
            $export = $storagePath.'/part-'.$part->id.'.mp4';
            $log = $logPath.'/block.txt';
            exec("ffmpeg -y -i $import -i $logo -filter_complex \"overlay = W - w - 10:H - h - 10\" -c:v libx264 -preset ultrafast $export");
            dd(file_get_contents($log));
            return Redirect::to(URL::previous().'#convert');
        }
        else{
            abort(404);
        }
    }
    public function progress(){
        $logPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().'source/log';
        $content = $logPath.'/block.txt';
        echo $this->progressBar($content);
    }
    public function screenShot(Request $request)
    {
        $patch = public_path().$request->upload_video;
        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().'source\content-'.$request->content_id.'\image';
        $export = $storagePath.'\thumbnail-'.$request->id.'.jpg';
        if(!file_exists($storagePath)){
            File::makeDirectory($storagePath, $mode = 0777, true, true);
        }
        empty($request->intval)?$intval=20:$intval = $request->intval;
        exec("ffmpeg -i $patch -deinterlace -an -ss $intval -f mjpeg -t 1 -r 1 -y -s $request->resolution $export 2>&1");
        return Redirect::to(URL::previous().'#convert');
    }
    public function preConvert($id){
        $part = ContentPart::find($id);
        if($part) {
            $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'source/temp';
            $logPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().'source/log';
            if (!is_dir($storagePath)) {
                File::makeDirectory($storagePath, $mode = 0777, true, true);
            }

            $log = $logPath.'/block.txt';
            $import = getcwd().parse_url(rawurldecode($part->upload_video) ,PHP_URL_PATH);
            $export = $storagePath.'/temp.mpg';
            exec("ffmpeg -y -i $import -qscale 0 $export 1> $log 2>&1");
            return Redirect::to(URL::previous().'#convert');
        }
    }
    public function copy($id){
        $part = ContentPart::find($id);
        if($part) {
            $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().'source/content-'.$part->content_id.'/video';
            if(!is_dir($storagePath)){
                File::makeDirectory($storagePath, $mode = 0777, true, true);
            }
            $import = getcwd().parse_url(rawurldecode($part->upload_video) ,PHP_URL_PATH);
            $info   = pathinfo($import);
            $export = $storagePath.'/part-'.$part->id.'.'.$info['extension'];
            copy($import, $export);
            return Redirect::to(URL::previous().'#convert');
        }
        else{
            abort(404);
        }
    }
    ####################
}
