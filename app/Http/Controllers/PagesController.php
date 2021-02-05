<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    /**
     * Homepage.
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        return view('pages.home');
    }

    /**
     * Privacy Policy page.
     *
     * @return \Illuminate\View\View
     */
    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    /**
     * Terms of Service page.
     *
     * @return \Illuminate\View\View
     */
    public function termsOfService()
    {
        return view('pages.terms-of-service');
    }

    /**
     * Support page.
     *
     * @return \Illuminate\View\View
     */
    public function getSupport()
    {
        return view('pages.support');
    }
}
