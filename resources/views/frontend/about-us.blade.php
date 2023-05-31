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
        <div class="row">
            <div class="col-md-4">
                <div class="developer-card"> <img src="{{ url('assets/imgD/3.jpg') }}" alt="Developer 1" class="img-fluid rounded-circle mb-3">
                    <h4 class="mb-2">Alexander Z Fabillar</h4>
                    <p class="text-muted mb-0">Specialization: Front-end Development</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="developer-card">
                    <img src="{{ url('assets/imgD/1.jpg') }}" alt="Developer 1" class="img-fluid rounded-circle mb-3">

                    <h4 class="mb-2">Christian Franc M Carvajal</h4>
                    <p class="text-muted mb-0">Specialization: Full-stack Development</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="developer-card">
                    <img src="{{ url('assets/imgD/2.jpg') }}" alt="Developer 1" class="img-fluid rounded-circle mb-3">

                    <h4 class="mb-2">Christian Punting</h4>
                    <p class="text-muted mb-0">Specialization: Full-stack Development</p>
                </div>
            </div>
        </div>
    </div>
</div>
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