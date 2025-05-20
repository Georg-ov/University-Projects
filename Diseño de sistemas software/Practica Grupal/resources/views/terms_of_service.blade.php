@extends('layouts.master')

@section('title', 'Terms of Service')

@section('content')
<body style="background: -webkit-linear-gradient(to right, #D79292, #60A9BD);
             background: linear-gradient(to right, #D79292, #60A9BD);
             color: #000000; /* White text color */">

    <div class="container py-5">
        <h1 class="text-center mb-4 font-weight-bold" style="font-family: 'Orbitron', sans-serif;">Terms of Service</h1>

        <p style="color: #000000;">These terms and conditions govern the use of the website and the purchase of online courses offered on this website.</p>

        <h3 style="color: #000000; font-family: 'Orbitron', sans-serif;">1. Acceptance of Terms</h3>
        <p style="color: #000000;">By accessing and using this website, you agree to comply with these terms and conditions in full. If you do not agree with any part of these terms, do not use this website.</p>

        <h3 style="color: #000000; font-family: 'Orbitron', sans-serif;">2. Course Purchase</h3>
        <p style="color: #000000;">Courses offered on this website are subject to availability. To purchase a course, you must provide the required information and complete the payment process.</p>

        <h3 style="color: #000000; font-family: 'Orbitron', sans-serif;">3. Intellectual Property Rights</h3>
        <p style="color: #000000;">All content on this website, including courses, is protected by intellectual property rights and may not be copied, distributed, or used without authorization.</p>

        <h3 style="color: #000000; font-family: 'Orbitron', sans-serif;">4. Cancellations and Refunds</h3>
        <p style="color: #000000;">Cancellation and refund policies are subject to the specific conditions of each course. Please check the course details before making a purchase.</p>

        <h3 style="color: #000000; font-family: 'Orbitron', sans-serif;">5. Privacy and Security</h3>
        <p style="color: #000000;">We respect your privacy and employ security measures to protect your personal information. Please refer to our privacy policy for more details.</p>

        <p style="color: #000000;">These terms and conditions may change at any time. Please check this page regularly for updates.</p>

        <p style="color: #000000;">If you have any questions about these terms and conditions, please contact us.</p>

        <p style="color: #000000;">Last updated: {{ now()->format('d/m/Y') }}</p>
    </div>

</body>
@endsection