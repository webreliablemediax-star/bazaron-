<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class VideoController extends Controller
{

    public function index()
    {
        $videos = DB::table('videos')->orderBy('id','desc')->get();

        return view('backend.video.index',compact('videos'));
    }


   public function upload(Request $request)
{

    if($request->hasFile('video'))
    {

        $file = $request->file('video');

        $name = time().'.'.$file->getClientOriginalExtension();

        $size = round($file->getSize()/1024);

        // ensure folder exists
        $path = public_path('uploads/videos');

        if(!file_exists($path)){
            mkdir($path,0777,true);
        }

        $file->move($path,$name);

        DB::table('videos')->insert([

            'title'=>$request->title,
            'video'=>$name,
            'size'=>$size,
            'created_at'=>now(),
            'updated_at'=>now()

        ]);

    }

    return back();

}



    public function delete($id)
    {

        $video = DB::table('videos')->where('id',$id)->first();

        if($video)
        {

            $path = public_path('uploads/videos/'.$video->video);

            if(file_exists($path))
            {
                unlink($path);
            }

            DB::table('videos')->where('id',$id)->delete();

        }

        return back();

    }


}