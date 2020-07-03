<?php

namespace App\Http\Controllers\User;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    public function lists(){
        global $user;
        $lists = Article::with(['category'])->where('user_id',$user['id'])->orderBy('id','DESC')->get();
        return view('user.article.list',['lists'=>$lists]);
    }
    public function articleNew(){
        return view('user.article.new');
    }
    public function store(Request $request){
        global $user;
        $request->request->add(['user_id'=>$user['id'],'create_at'=>time()]);
        $article = Article::create($request->toArray());
        return redirect('/user/article/edit/'.$article->id)->with('msg',trans('main.article_success'));
    }
    public function articleEdit($id){
        global $user;
        $article = Article::where('user_id',$user['id'])->find($id);
        if(!$article)
            return abort(404);
        return view('user.article.edit',['article'=>$article]);
    }
    public function editStore(Request $request,$id){
        global $user;
        $article = Article::where('user_id',$user['id'])->find($id);
        if(!$article)
            return abort(404);
        $article->update($request->toArray());
        return redirect('/user/article/edit/'.$id);
    }
    public function delete($id){
        global $user;
        $article = Article::where('user_id',$user['id'])->find($id);
        $article->update(['mode'=>'delete']);
        return back();
    }
}
