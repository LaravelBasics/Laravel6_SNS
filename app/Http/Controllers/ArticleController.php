<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Http\Requests\ArticleRequest;
use App\Tag;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Article::class, 'article');
    }

    public function index()
    {
        $articles = Article::all()->sortByDesc('created_at')->load(['user', 'likes', 'tags']);

        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return view('articles.create', [
            'allTagNames' => $allTagNames,
        ]);
    }
    // Dependency Injection（依存性の注入）の読み方は、以下のようになります。
    // 日本語読み: いそんせいのちゅうにゅう
    // 英語読み: ディペンデンシー インジェクション

    // Laravelのコントローラーはメソッドの引数で型宣言を行うと、そのクラスのインスタンスが自動で生成されてメソッド内で使えるようになります
    /**
     * public function store(ArticleRequest $request) //-- ArticleクラスのDIを行わない{
     * $article = new Article(); //-- storeアクションメソッド内でArticleクラスのインスタンスを生成している
     * // $request->tagsリクエストから取得したタグのコレクション（配列）が含まれます。このフィールドはおそらくJSON形式で送信されたタグのリスト
     *      ->each(function ($tagName) use ($article) 　eachメソッドは、コレクション内の各要素に対して指定したコールバック関数を実行します
     * コールバック関数は、コレクションの各要素（ここでは各タグ名）を受け取り、指定した処理を実行
     * use ($article)は、コールバック関数の内部で$article変数を使用できるように
     * Tag::firstOrCreate(['name' => $tagName]):Tagモデルの中で、指定された名前のタグを最初に見つけます。もし見つからない場合は、新しいタグを作成します。
     *  これにより、タグが重複することなく、常に一意のタグがデータベースに存在することが保証されます
     *    $article->tags():tagsメソッドは、おそらくArticleモデルとTagモデルの間に定義された多対多の関係を返します。
     *   これにより、Articleモデルのインスタンスがその関連付けられたタグを取得または操作できるようになります
     *  ->attach($tag):attachメソッドは、多対多のリレーションに新しいレコードを追加します
     * ここでは、$articleと$tagの間に新しい関連付けを作成
     *  firstOrCreateメソッドやcreateメソッドを使ってモデルを保存する場合、前提事項としてモデルの$fiilableプロパティにおいて、セットするプロパティ名が記述されている必要があります。
     * (もしくは、$guardedプロパティにおいて、セットするプロパティ名が記述されていない必要があります)
     *もし、プロパティ名が$fiilableプロパティ内に記述されていないと、MassAssignmentException(複数代入例外)が発生し、モデルの保存ができません
     */

    public function store(ArticleRequest $request, Article $article)
    {
        Log::info('リクエストデータ:', $request->all());

    $article->fill($request->all());
    $article->user_id = $request->user()->id;
    $article->save();
    
    Log::info('記事が保存されました。', ['article_id' => $article->id]);

    $request->tags->each(function ($tagName) use ($article) {
        $tag = Tag::firstOrCreate(['name' => $tagName]);
        $article->tags()->attach($tag);
        Log::info('タグが追加されました。', ['article_id' => $article->id, 'tag_name' => $tag->name]);
    });

    return redirect()->route('articles.index');
    }

    public function edit(Article $article)
    {
        $tagNames = $article->tags->map(function ($tag) {
            return ['text' => $tag->name];
        });
        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });
        return view('articles.edit', [
            'article' => $article,
            'tagNames' => $tagNames,
            'allTagNames' => $allTagNames,
        ]);
    }

    public function update(ArticleRequest $request, Article $article)
    { //storeアクションメソッドと似ていますが、Articleモデルのuser_id(記事の投稿ユーザーID)の情報は更新する必要が無い
        $article->fill($request->all())->save();

        $article->tags()->detach();
        $request->tags->each(function ($tagName) use ($article) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $article->tags()->attach($tag);
        });

        return redirect()->route('articles.index');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index');
    }

    public function show(Article $article)
    {
        return view('articles.show', /*compact('article')*/ ['article' => $article]);
    }

    public function like(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);
        $article->likes()->attach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }

    public function unlike(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }
}
// Artcleモデルのfillableプロパティ内に指定しておいたプロパティ(ここではtitleとbody)のみが、$articleの各プロパティに代入されます。
// このようにfillableプロパティを使うことでどういったメリットがあるかというと、以下になります。
// まず、不正なリクエストへの対策となります。
// どういうことかというと、記事投稿画面ではタイトルと本文のみを入力してPOST送信できるように作りましたが、クライアント側でツールなどを使ってそれ以外のパラメーターも含んだ不正なリクエストをPOST送信することは可能です。
// しかし、fillableプロパティを定義したことで、クライアントからのリクエストのパラメーター値をそのまま取り込んで更新しても良いプロパティは、titleとbodyのみと制限されるようになりました。
// これによって、不正なリクエストによってarticlesテーブルが予期せぬ内容に更新されることを防ぐようになりました
