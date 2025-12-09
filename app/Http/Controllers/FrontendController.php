<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\CommitteeMember;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Menu;
use App\Models\News;
use App\Models\Notice;
use App\Models\Page;
use App\Models\Post;
use App\Models\Result;
use App\Models\Section;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $runningEvent = Event::orderBy("created_at", "desc")->where('type', 1)->paginate(15);
        $upcomingEvent = Event::orderBy("created_at", "desc")->where('type', 0)->paginate(15);
        $about_mission_vision = Section::where('name', 'about_mission_vision')->first();
        $blogs = Blog::latest()->paginate(7);
        $topNews = News::latest()->paginate(5);
        $gamesNews = News::getGamesNews();
        return view('home', compact('runningEvent', 'upcomingEvent', 'about_mission_vision', 'gamesNews', 'blogs', 'topNews'));
    }
    public function pages()
    {
        $pages = Page::all();
        return view('pages.index', compact('pages'));
    }
    public function pageDetails($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        if ($slug == 'contact-us') {
            return view('contact', compact('page'));
        } else {
            return view('pages.details', compact('page'));
        }
    }
    public function matchFixtures()
    {
        return view('match-fixtures');
    }
    public function noticeBoard()
    {
        $notice = Notice::paginate();
        return view('notice-board', compact('notice'));
    }
    public function tournamentResult()
    {
        $results = Result::paginate();
        return view('tournament-result', compact('results'));
    }
    public function gallery()
    {
        $gallery = Gallery::paginate(10);
        return view('gallery.gallery', compact('gallery'));
    }
    public function galleryDetails($id)
    {
        $gallery = Gallery::find($id);
        $details = $gallery->details()->paginate();
        return view('gallery.gallery-details', compact('gallery', 'details'));
    }
    public function newsAndUpdates()
    {
        $news = News::paginate(3);
        return view('news.news-and-updates', compact('news'));
    }
    public function newsAndUpdatesDetails($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();
        return view('news.news-and-updates-details', compact('news'));
    }
    public function blogs()
    {
        $blogs = Blog::paginate();
        return view('blog.blogs', compact('blogs'));
    }
    public function blogsDetails($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        return view('blog.blog-details', compact('blog'));
    }
    public function runningEvents()
    {
        $events = Event::where('type', 1)->paginate(10);
        return view('events.running', compact('events'));
    }
    public function upcomingEvents()
    {
        $events = Event::where('type', 0)->paginate(10);
        return view('events.upcoming', compact('events'));
    }
    public function runningEventsDetails($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        return view('events.running-details', compact('event'));
    }
    public function committeeMembers()
    {
        $members = CommitteeMember::orderBy('order')->where('status', 1)->get();
        return view('committee-members', compact('members'));
    }
}
