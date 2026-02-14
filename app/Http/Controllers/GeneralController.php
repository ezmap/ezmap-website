<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use Vluzrmos\LanguageDetector\Facades\LanguageDetector;

class GeneralController extends Controller
{
    public function index(Request $request)
    {
        return view('index');
    }

    public function feedback(Request $request)
    {
        $this->validate($request, [
            'name'                 => 'required',
            'email'                => 'required|email',
            'feedback'             => 'required',
            'subject'              => 'required',
        ]);

        $name     = $request->input('name');
        $email    = $request->input('email');
        $feedback = $request->input('feedback');
        $subject  = $request->input('subject');
        Mail::send('emails.feedback', compact('name', 'email', 'feedback', 'subject'), function ($message) use ($name, $email, $subject, $feedback)
        {
            $message->subject('EZ Map ' . $subject);
            $message->from('feedbackform@mg.ezmap.co', 'EZ Map Feedback');
            $message->replyTo($email, $name);
            $message->to('feedback@mg.ezmap.co');
        });
        if ($request->ajax())
        {
            return response()->json(['type' => 'success', 'title' => 'Thank You', 'text' => "Your feedback was sent."]);
        }
        session()->flash('success', 'Thank you! Your feedback was sent.');

        return redirect()->back();
    }

    public function geocodeAddress(Request $request)
    {
        return $request->all();
    }

    public function changeLanguage($lang)
    {
        LanguageDetector::apply($lang);

        return redirect()->back();
    }
}
