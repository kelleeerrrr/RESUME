@extends('layout')

@section('title', 'Contact | Irish Rivera')

@section('content')
<h2 style="color:var(--accent);">Get In Touch</h2>
<p>Let’s collaborate or connect! I’d love to hear from you.</p>

{{-- Success / Error Message --}}
@if(session('success'))
    <div style="background-color:#2ecc71; color:white; padding:10px; border-radius:5px; margin-bottom:15px;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background-color:#e74c3c; color:white; padding:10px; border-radius:5px; margin-bottom:15px;">
        <ul style="margin:0; padding-left:20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="display:flex; flex-wrap:wrap; gap:40px; margin-top:20px;">
    <form style="flex:1; min-width:300px;" method="POST" action="{{ route('contact.send') }}">
        @csrf
        <input type="text" name="name" placeholder="Your Name" value="{{ old('name') }}" style="width:100%; padding:10px; margin-bottom:10px; border-radius:5px; border:none;">
        <input type="email" name="email" placeholder="Your Email" value="{{ old('email') }}" style="width:100%; padding:10px; margin-bottom:10px; border-radius:5px; border:none;">
        <textarea name="message" placeholder="Your Message" style="width:100%; padding:10px; border-radius:5px; border:none; height:120px;">{{ old('message') }}</textarea>
        <button type="submit" class="back-btn" style="margin-top:10px;">Send Message</button>
    </form>

    <div style="flex:1; min-width:250px;">
        <h3>Social Links</h3>
        <p style="font-size:1.1em;">Connect with me on:</p>
        <ul style="font-size:1.1em;">
            <li>📧 <a href="mailto:23-00679@g.batstate-u.edu.ph">Email</a></li>
            <li>💼 <a href="#">LinkedIn</a></li>
            <li>🐙 <a href="#">GitHub</a></li>
        </ul>
    </div>
</div>
@endsection
