<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keyword;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class ArticleController extends Controller
{
    public function show(Request $request, $keyword = null, $domain = null)
    {
        // Determine if the request is from a subdomain or main domain
        $articlesPath = resource_path('articles');
        $articleName = $keyword ? $keyword : $request->route('articleName');
        $articleFile = "{$articlesPath}/{$articleName}.html";

        if (File::exists($articleFile)) {
            $content = File::get($articleFile);
        } else {
            $content = $this->getRandomArticle($articlesPath);
        }

        return response()->make($content);
    }

    public function showRandom()
    {
        $articlesPath = resource_path('articles');
        // Logic to fetch and show a random article
        $article = $this->getRandomArticle($articlesPath);

        return view('article', ['article' => $article]);
    }

    private function getRandomArticle($path)
    {
        $allArticles = File::files($path);
        if (count($allArticles) > 0) {
            $randomArticle = $allArticles[array_rand($allArticles)];
            return File::get($randomArticle->getPathname());
        }

        return 'No articles available.';
    }

    public function upload(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string',
            'slug' => 'required|string',
            'content' => 'required|string',
        ]);

        // Store slug in keywords table
        $keyword = new Keyword();
        $keyword->keyword = $request->slug;
        $keyword->save();

        // Generate HTML content
        $htmlContent = view('article_template', [
            'title' => $request->keyword,
            'content' => $request->content
        ])->render();

        // Determine file path
        $filePath = resource_path('articles/' . Str::slug($request->keyword) . '.html');

        // Create the articles directory if it doesn't exist
        if (!file_exists(resource_path('articles'))) {
            mkdir(resource_path('articles'), 0777, true);
        }

        // Store the HTML file in resources/articles
        file_put_contents($filePath, $htmlContent);

        return response()->json(['message' => 'Article uploaded successfully']);
    }
}
