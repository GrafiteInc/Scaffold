<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PagesController extends Controller
{
    /**
     * Homepage.
     *
     * @return View
     */
    public function home()
    {
        return view('pages.home');
    }

    /**
     * Privacy Policy page.
     *
     * @return View
     */
    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    /**
     * Terms of Service page.
     *
     * @return View
     */
    public function termsOfService()
    {
        return view('pages.terms-of-service');
    }

    /**
     * Support page.
     *
     * @return View
     */
    public function getSupport()
    {
        return view('pages.support');
    }
}
