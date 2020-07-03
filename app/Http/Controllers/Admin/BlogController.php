<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

class BlogController extends Controller
{
    public function posts(){
        $postList = Blog::with('comments','user')->orderBy('id','DESC')->get();
        return view('admin.blog.list',array('posts'=>$postList));
    }
    public function newPost(){
        $category = BlogCategory::all();
        return view('admin.blog.new',['category'=>$category]);
    }
    public function postDelete($id)
    {
        Blog::find($id)->delete();
        return back();
    }
    public function store(Request $request){
        global $admin;
        $request->request->add(['user_id'=>$admin['id']]);
        if($request->id){
            $request->request->add(['update_at'=>time()]);
            if(isset($request->comment))
                $request->request->set('comment','enable');
            else
                $request->request->set('comment','disable');
            Blog::where('id',$request->id)->update($request->all());
            return back();
        }else{
            $request->request->add(['create_at'=>time()]);
            if(isset($request->comment))
                $request->request->set('comment','enable');
            else
                $request->request->set('comment','disable');
            $post = Blog::create($request->all());
            return redirect('/admin/blog/post/edit/'.$post->id);
        }
    }
    public function editPost($id){
        $category = BlogCategory::all();
        $item = Blog::find($id);
        return view('admin.blog.edit',['category'=>$category,'item'=>$item]);
    }


    public function category()
    {
        $list = BlogCategory::withCount('posts')->get();
        return view('admin.blog.categroy',array('lists'=>$list));
    }
    public function categoryEdit($id)
    {
        $list = BlogCategory::all();
        $item = BlogCategory::find($id);
        return view('admin.blog.categroyedit',array('lists'=>$list,'item'=>$item));
    }
    public function categoryStore(Request $request)
    {

        if($request->edit != '') {
            $category = BlogCategory::find($request->edit);
            $category->update(['title'=> $request->title]);
        }
        else {
            $category = new BlogCategory;
            $category->title = $request->title;
            $category->save();
        }
        return back();
    }
    public function categoryDelete($id)
    {
        BlogCategory::find($id)->delete();
        return back();
    }


    public function comments()
    {
        $comments = BlogComments::with('user','post')->orderBy('id','DESC')->get();
        return view('admin.blog.comments',['comments'=>$comments]);
    }
    public function commentDelete($id)
    {
        BlogComments::find($id)->delete();
        return back();
    }
    public function commentEdit($id)
    {
        $item = BlogComments::find($id);
        return view('admin.blog.commentedit',['item'=>$item]);
    }
    public function commentStore(Request $request)
    {
        $comment = BlogComments::find($request->id);
        $comment->comment = $request->comment;
        $comment->save();
        return back();
    }
    public function commentView($action,$id)
    {
        $comment = BlogComments::find($id);
        $comment->mode = $action;
        $comment->save();
        return back();
    }
    public function commentReply($id){
        $item = BlogComments::find($id);
        return view('admin.blog.reply',['item'=>$item]);
    }
    public function commentReplyStore(Request $request){
        global $admin;
        $comment = BlogComments::create([
            'comment'=>$request->comment,
            'user_id'=>$admin['id'],
            'create_at'=>time(),
            'name'=>$admin['name'],
            'post_id'=>$request->post_id,
            'parent'=>$request->parent,
            'mode'=>'publish'
        ]);
        return redirect('admin/blog/comment/edit/'.$comment->id);
    }

    ## Article Section
    public function article(){
        $postList = Article::with(['user','category'])->orderBy('id','DESC')->get();
        return view('admin.blog.articlelist',array('posts'=>$postList));
    }
    public function articleDelete($id){
        $article = Article::find($id);
        $article->delete();
        return back();
    }
    public function articleEdit($id){
        $article = Article::find($id);
        return view('admin.blog.articleedit',['item'=>$article]);
    }
    public function articleStore(Request $request,$id){
        Article::find($id)->update($request->toArray());
        return back();
    }
}
