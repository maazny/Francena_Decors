<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Service;
use App\Models\Project;
use App\Models\TeamMember;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML sitemap.
     */
    public function index(): Response
    {
        $posts = BlogPost::where('status', true)
            ->where('published_at', '<=', now())
            ->orderBy('updated_at', 'desc')
            ->get();

        $services = Service::active()
            ->orderBy('updated_at', 'desc')
            ->get();

        $projects = Project::published()
            ->orderBy('updated_at', 'desc')
            ->get();

        $teamMembers = TeamMember::active()
            ->orderBy('updated_at', 'desc')
            ->get();

        $content = view('sitemap', compact('posts', 'services', 'projects', 'teamMembers'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
