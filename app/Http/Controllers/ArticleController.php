<?php namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Todo;
use Auth;
use Request;
use App\Models\Article;
 
class ArticleController extends Controller {
 
   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
   public function index() {
 
     $articles = Article::orderBy('created_at', 'desc')->get();
     return $articles;
   }
 
   /**
    * Store a newly created resource in storage.
    *
    * @return Response
    */
   public function store() {
      $article = new Article(Request::all());
      $article->save();
      return $article;
   }
 
 
}