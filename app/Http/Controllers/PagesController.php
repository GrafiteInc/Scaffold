<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    /**
     * Homepage
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        return view('pages.home');
    }

    /**
     * Privacy Policy page
     *
     * @return \Illuminate\Http\Response
     */
    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    /**
     * Terms of Service page
     *
     * @return \Illuminate\Http\Response
     */
    public function termsOfService()
    {
        return view('pages.terms-of-service');
    }

    /**
     * Contact page
     *
     * @return \Illuminate\Http\Response
     */
    public function getContact()
    {
        return view('pages.contact');
    }
}
