@include('head')

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 px-4">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Verify Your Email</h1>

        <p class="text-gray-600 text-center mb-6">
            A verification link has been sent to your email address. Please check your inbox and click the link to verify your account.
        </p>

        @if (session('message'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('message') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                Resend Verification Email
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('user.login') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                Back to Login
            </a>
        </div>
    </div>
</div>

@include('foot')
