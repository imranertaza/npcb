<?php
namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\CommitteeMember;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Notice;
use App\Models\Page;
use App\Models\Post;
use App\Models\Result;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Slider;
use App\Services\ImageService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Exception;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;

class FrontendController extends Controller
{
    public function index()
    {
        $runningEvent         = Event::orderBy("created_at", "desc")->where('status', "1")->where('type', 1)->paginate(15);
        $upcomingEvent        = Event::orderBy("created_at", "desc")->where('status', "1")->where('type', 0)->paginate(15);
        $about_mission_vision = Section::where('name', 'about_mission_vision')->first();
        $blogs                = Blog::where('status', "1")->latest()->paginate(7);
        $topNews              = News::where('status',1)->latest()->paginate(5);
        $gamesNews            = News::getGamesNews();
        $slides               = Slider::where('key', 'banner_section')->where('enabled', 1)->get();

        return view('home', compact('runningEvent', 'upcomingEvent', 'about_mission_vision', 'gamesNews', 'blogs', 'topNews', 'slides'));
    }
    public function pages()
    {
        $pages = Page::all();
        return view('pages.index', compact('pages'));
    }
    public function pageDetails($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        // $imagePath = 'assets/images/default.jpg';
        // $url       = ImageService::resizeAndCache($imagePath, 80, 80);
        if ($slug == 'contact-us') {
            return view('contact', compact('page'));
        } else if ($slug == 'sports') {
            $ports = Post::where('status', 1)->paginate(10);
            return view('pages.details', compact('page', 'sports'));
        } else {
            return view('pages.details', compact('page'));
        }
    }
    public function matchFixtures()
    {
        $matchFixtures = Notice::where('type', 1)->where('status', 1)->paginate(10);
        return view('match-fixtures', compact('matchFixtures'));
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
        $pageTitle = 'News and Updates';
        $news      = News::paginate();
        return view('news.news-and-updates', compact('news', 'pageTitle'));
    }
    public function spotlightNews()
    {
        $news      = News::getSpotlightNews();
        $pageTitle = 'Spotlights';
        return view('news.news-and-updates', compact('news', 'pageTitle'));
    }
    public function newsAndUpdatesDetails($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();
        return view('news.news-and-updates-details', compact('news'));
    }
    public function blogs()
    {
        $blogs = Blog::where('status', "1")->paginate();
        return view('blog.blogs', compact('blogs'));
    }
    public function blogsDetails($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        return view('blog.blog-details', compact('blog'));
    }
    public function runningEvents()
    {
        $events = Event::where('type', 1)->where('status', 1)->paginate(10);
        return view('events.running', compact('events'));
    }
    public function upcomingEvents()
    {
        $events = Event::where('type', 0)->where('status', 1)->paginate(10);
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
    public function postCategoryDetails($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        // paginate directly on the relationship
        $posts = $category->posts()->paginate(12);
        return view('sports.sports', compact('category', 'posts'));
    }
    public function postDetails($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        return view('sports.sports-details', compact('post'));
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'email'                => 'required|email',
            'subject'              => 'nullable|string|max:255',
            'description'          => 'nullable|string|max:1000',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        // Load mail settings from DB
        $settings = Setting::whereIn('label', [
            'mail_protocol',
            'mail_address',
            'smtp_host',
            'smtp_username',
            'smtp_password',
            'smtp_port',
            'smtp_timeout',
            'smtp_crypto',
        ])->pluck('value', 'label');

        try {
            $mail = new PHPMailer(true);

            // Choose protocol based on DB setting
            if (($settings['mail_protocol'] ?? 'smtp') === 'mail') {
                // Use PHP's built-in mail() function
                $mail->isMail();
            } else {
                // Default to SMTP
                $mail->isSMTP();
                $mail->Host       = $settings['smtp_host'] ?? '';
                $mail->SMTPAuth   = true;
                $mail->Username   = $settings['smtp_username'] ?? '';
                $mail->Password   = $settings['smtp_password'] ?? '';
                $mail->SMTPSecure = $settings['smtp_crypto'] ?? 'tls';
                $mail->Port       = (int) ($settings['smtp_port'] ?? 587);
                $mail->Timeout    = (int) ($settings['smtp_timeout'] ?? 30);
            }

            // Sender & recipient
            $mail->setFrom($request->email, 'Website Contact Form');
            $mail->addAddress($settings['mail_address'] ?? 'admin@example.com');

            // Content
            $mail->isHTML(true);
            $mail->Subject = $request->subject ?? 'Contact Form Submission';
            $mail->Body    = nl2br($request->description ?? 'No message provided');

            $mail->send();

            ToastMagic::success('Message sent successfully!');
        } catch (Exception $e) {

            ToastMagic::error('Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        }

        return back();
    }
}
