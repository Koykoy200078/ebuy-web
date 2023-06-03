@extends('layouts.app')

@section('title', 'About Us')

@section('content')

<div class="py-3 py-md-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="mb-4 display-4">Welcome to Our Team!</h2>
                <p class="lead mb-4">We are a team of skilled developers dedicated to providing the best shopping experience for you. </p>
            </div>
        </div>
        <br>
        <br>
        <br>
        <div class="row">
            <div class="col-md-4">
                <div class="developer-card">
                    <img src="{{ url('assets/imgD/3.jpg') }}" alt="Developer 1">
                    <h4 style="font-weight: bold;">Alexander Z Fabillar</h4>
                    <p>I am a skilled front-end developer who is passionate about creating web applications that are optimized for responsiveness and user experience. My primary focus is on utilizing HTML, CSS, and JavaScript, and I have a strong command of modern front-end frameworks like Laravel. Additionally, I bring proficiency in 3D modeling using Blender, adding a creative dimension to my skill set</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="developer-card">
                    <img src="{{ url('assets/imgD/1.jpg') }}" alt="Developer 1" class="img-fluid rounded-circle mb-3">

                    <h4 style="font-weight: bold;">Christian Franc M Carvajal</h4>
                    <p>To acquire new valuable knowledge and skills in any environment where I can apply my knowledge. To build a career that offers both challenge and growth with opportunities to expand my knowledge and skills by using them in a working environment that simulates the real world.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="developer-card">
                    <img src="{{ url('assets/imgD/2.jpg') }}" alt="Developer 1" class="img-fluid rounded-circle mb-3">

                    <h4 style="font-weight: bold;">Christian Punting</h4>
                    <p>I am a passionate front-end developer with several years of experience in creating responsive and user-friendly web applications. I specialize in HTML, CSS, and JavaScript, and I have a strong understanding of modern front-end frameworks like Laravel.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<br>
<br>
@endsection

<style>
    .developer-card {
        text-align: center;
        padding: 30px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .developer-card img {
        max-width: 150px;
    }
</style>