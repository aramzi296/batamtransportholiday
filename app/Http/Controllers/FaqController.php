<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of FAQs.
     */
    public function index()
    {
        $faqs = Faq::active()->ordered()->get();
        return view('faqs.index', compact('faqs'));
    }
}

