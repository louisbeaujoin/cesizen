<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InformationPage;

class InformationApiController extends Controller
{
    public function index()
    {
        $pages = InformationPage::published()->ordered()->get()->map(fn($p) => [
            'id' => $p->id,
            'title' => $p->title,
            'slug' => $p->slug,
        ]);

        return response()->json($pages);
    }

    public function show(string $slug)
    {
        $page = InformationPage::where('slug', $slug)->published()->firstOrFail();

        return response()->json([
            'id' => $page->id,
            'title' => $page->title,
            'slug' => $page->slug,
            'content' => $page->content,
        ]);
    }
}
