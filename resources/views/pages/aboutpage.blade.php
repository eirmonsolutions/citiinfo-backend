@extends('layouts.app')

@section('title', 'Citiinfo – Australia Business Directory | About Our Platform')

@section('meta_description', 'Learn about Citiinfo, an Australia business directory helping users discover local businesses and services while enabling companies to promote their listings online.')

@section('meta_keywords', '')

@section('content')


<section class="banner-area-other">
    <div class="container">
        <div class="banner-text">
            <h1>About Us</h1>
        </div>
    </div>
</section>

<section class="about-deatil-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="left-side-about">
                    <h4>About Citiinfo</h4>
                    <p>
                        <a href="https://citiinfo.com.au/" target="_blank"> Citiinfo </a> is a modern Australia business directory designed to help people easily discover trusted local businesses and services. Our platform connects users with companies across major Australian cities including <b>Melbourne, Sydney, Brisbane, Perth, and Adelaide.</b>
                    </p>

                    <p>
                        Whether you are searching for a reliable plumber, a nearby restaurant, a beauty salon, or a car rental service, Citiinfo helps you find the right business quickly and conveniently.
                    </p>

                    <p>
                        Our directory includes a wide range of industries such as restaurants, salons, dentists, hospitals, plumbers, real estate agencies, towing services, skip bin hire companies, and many other local service providers.
                    </p>

                    <p>
                        Citiinfo also helps businesses grow their online presence by allowing them to <a target="_blank" href="https://citiinfo.com.au/listing"> create business listings,</a> <b> showcase services, display contact information, and reach more customers across Australia.</b>
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="right-side-about">
                    <img src="{{asset('assets/images/about-imgs/img-1.svg')}}" alt="">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-deatil-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="right-side-about">
                    <img src="{{asset('assets/images/about-imgs/img-1.svg')}}" alt="">
                </div>
            </div>
            <div class="col-md-6">
                <div class="left-side-about">
                    <h4>Why Businesses Trust Citiinfo</h4>
                    <p>
                        Citiinfo is built to make discovering and promoting local businesses simple and effective. Our platform provides a user-friendly way for customers to explore services while giving businesses the opportunity to expand their reach.
                    </p>

                    <p>
                        Users can <a target="_blank" href="https://citiinfo.com.au/categories"> search businesses by category,</a> city, or service type, making it easy to find trusted providers near them. Each listing provides important details such as business information, services offered, and location.
                    </p>

                    <p>
                        For business owners, Citiinfo acts as a powerful digital marketplace where companies can showcase their services and connect with customers who are actively searching for them.
                    </p>

                    <p>
                        Our goal is to support local businesses while helping users make better decisions when choosing services across Australia.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-deatil-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="left-side-about">
                    <h4 class="mb-3">Our Purpose and Future Vision</h4>

                    <h5>Our Purpose</h5>
                    <p>
                        Our purpose is to create a reliable <b> local business discovery platform</b> that helps people easily find trusted services while supporting businesses in growing their online visibility.
                    </p>

                    <h5>Our Future Vision</h5>

                    <p>
                        Our future vision is to become one of the leading <b> Australia business directories,</b> helping thousands of businesses promote their services and allowing customers to quickly connect with reliable local providers.
                    </p>

                    <p>
                        By continuously improving our platform and expanding our listings, we aim to make Citiinfo a trusted destination for discovering businesses across Australia.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="right-side-about">
                    <img src="{{asset('assets/images/about-imgs/img-1.svg')}}" alt="">
                </div>
            </div>
        </div>
    </div>
</section>



@endsection