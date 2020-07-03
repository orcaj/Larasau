<?php

namespace App\Http\Controllers\User;

use App\Classes\Vimeo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VimeoController extends Controller
{
    function file_get_contents_chunked($file,$chunk_size,$callback)
    {
        try
        {
            $handle = fopen($file, "r");
            $i = 0;
            while (!feof($handle))
            {
                call_user_func_array($callback,array(fread($handle,$chunk_size),&$handle,$i));
                $i++;
            }

            fclose($handle);

        }
        catch(Exception $e)
        {
            trigger_error("file_get_contents_chunked::" . $e->getMessage(),E_USER_NOTICE);
            return false;
        }

        return true;
    }

    public function download(Request $request){
        global $user;
        $link           = $request->link;
        $Vimeo          = new Vimeo();
        $downloadLink   = $Vimeo->getVimeoDirectUrl($link);
        file_put_contents(getcwd().'/bin/'.$user['username'].'/'.str_random().'.mp4',file_get_contents($downloadLink));
    }
}
