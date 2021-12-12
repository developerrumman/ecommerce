<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;
use Image;

class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notices  = Notice::orderBy('id', 'asc')->get();
        return view('backend.pages.notice.manage', compact('notices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.pages.notice.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $notice = new Notice();

        $notice->title        = $request->title;
        $notice->description  = $request->description;
        $notice->status       = $request->status;

        if ($request->image) 
        {
            $image = $request->file('image');
            $img = rand() . '.' . $image->getClientOriginalExtension();
            $location = public_path('backend/img/notice/' . $img);
            Image::make($image)->save($location);
            $notice->image = $img;
        }

        $notice->save();
        return redirect()->route('notice.manage')->with('success','New batch added successfully');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $notice = Notice::find($id);

        if(!empty($notice)){
            return view('backend.pages.notice.edit', compact('notice'));
        }
        else{
            // return route('notice.manage');
            return redirect()->route('notice.manage');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $notice = Notice::find($id);

        $notice->title        = $request->title;
        $notice->description  = $request->description;
        $notice->status       = $request->status;

        if (!empty($request->image)) 
        {
           if (File::exists('backend/img/notice/' . $notice->image)){
               File::delete('backend/img/notice/' . $notice->image);  
           }
            $image = $request->file('image');
            $img = rand() . '.' . $image->getClientOriginalExtension();
            $location = public_path('backend/img/notice/' . $img);
            Image::make($image)->save($location);
            $notice->image = $img; 
        }

        $notice->save();
        return redirect()->route('notice.manage');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notice = Notice::find($id);

        if (File::exists('backend/img/notice/' . $notice->image))
           {
              File::delete('backend/img/notice/' . $notice->image);  
           }
           
           $notice->delete();
           return redirect()->route('notice.manage');
    }
}    
