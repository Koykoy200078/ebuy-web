@extends('layouts.app')

@section('title', 'About Us')

@section('content')

<div class="py-3 pyt-md-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>About Us</h2>
                <p>We are a team of three developers dedicated to providing the best shopping experience.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="developer-card">
                    <img src="{{ url('assets/imgD/3.jpg') }}" alt="Developer 1">

                    <h4>Alexander Z Fabillar</h4>
                    <p>Specialization: Front-end Development</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="developer-card">
                    <img src="{{ url('assets/imgD/1.jpg') }}" alt="Developer 1">

                    <h4>Christian Franc M Carvajal</h4>
                    <p>Specialization: Back-end Development</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="developer-card">
                    <img src="{{ url('assets/imgD/2.jpg') }}" alt="Developer 1">

                    <h4>Christian Punting</h4>
                    <p>Specialization: Full-stack Development</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
