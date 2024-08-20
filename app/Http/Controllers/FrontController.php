<?php

namespace App\Http\Controllers;

use App\Models\ArticleNews;
use App\Models\Author;
use App\Models\BannerAdvertisement;
use App\Models\Category;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    //
    public function index(){

        $categories = Category::all();

        $articles = ArticleNews::with(['Category'])
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(3)
        ->get();

        $featured_articles = ArticleNews::with(['Category'])
        ->where('is_featured', 'featured')
        ->inRandomOrder()
        ->take(5)
        ->get();

        $authors = Author::all();

        $bannerAds = BannerAdvertisement::where('is_active', 'active')
        ->where('type', 'banner')
        ->inRandomOrder()
        ->first();

        $entertainment_articles = ArticleNews::whereHas('category', function ($query){
            $query->where('name', 'Entertainment');
        })
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(6)
        ->get();

        $entertainment_featured_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Entertainment');
        })
        ->where('is_featured', 'featured')
        ->inRandomOrder()
        ->first();

        $sport_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Sports');
        })
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(6)
        ->get();

        $sport_featured_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Sports');
        })
        ->where('is_featured', 'featured')
        ->inRandomOrder()
        ->first();

        $automotive_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Automotive');
        })
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(6)
        ->get();

        $automotive_featured_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Automotive');
        })
        ->where('is_featured', 'featured')
        ->inRandomOrder()
        ->first();


        return view('front.index', compact('automotive_featured_articles' ,'automotive_articles', 'entertainment_articles', 'entertainment_featured_articles', 'sport_articles', 'sport_featured_articles', 'categories', 'articles', 'authors', 'featured_articles', 'bannerAds'));
    }

    public function category(Category $category)
    {
        $categories = Category::all();

        $bannerAds = BannerAdvertisement::where('is_active', 'active')
        ->where('type', 'banner')
        ->inRandomOrder()
        ->first();

        return view('front.category', compact('category', 'categories', 'bannerAds'));
    }

    public function author(Author $author)
    {
        $categories = Category::all();

        $bannerAds = BannerAdvertisement::where('is_active', 'active')
        ->where('type', 'banner')
        ->inRandomOrder()
        ->first();

        return view('front.author', compact('author', 'categories', 'bannerAds'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'keyword' => ['required', 'string', 'max:255']
        ]);

        $categories = Category::all();

        $keyword = $request->keyword;

    // Search for articles with the given keyword in title, content, and author's name.
        $articles = ArticleNews::with('category', 'author')
        ->where('name', 'like', '%'. $keyword. '%')->paginate(6);

        return view('front.search', compact('articles', 'keyword', 'categories'));
    }
}