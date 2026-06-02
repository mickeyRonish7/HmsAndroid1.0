<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Verify OTP</h2>
        <p class="text-sm text-gray-600 mt-2">We've sent a 6-digit code to <strong>{{ session('otp_email') }}</strong></p>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Verification Failed</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.otp.verify') }}" id="otpForm">
        @csrf

        <!-- OTP Input -->
        <div>
            <label for="otp" class="block font-medium text-sm text-gray-700 mb-2">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Enter 6-Digit OTP
                </span>
            </label>
            
            <!-- OTP Input Boxes -->
            <div class="flex justify-center space-x-2 mb-4" id="otp-inputs">
                <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition" data-index="0" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition" data-index="1" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition" data-index="2" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition" data-index="3" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition" data-index="4" inputmode="numeric" pattern="[0-9]">
                <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition" data-index="5" inputmode="numeric" pattern="[0-9]">
            </div>

            <!-- Hidden input to submit OTP -->
            <input type="hidden" name="otp" id="otp" value="">
        </div>

        <!-- Timer -->
        <div class="text-center mb-4">
            <p class="text-sm text-gray-600">
                <span class="font-medium">Time remaining:</span> 
                <span id="timer" class="text-blue-600 font-bold">10:00</span>
            </p>
        </div>

        <div class="mt-6">
            <button type="submit" id="verifyBtn" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Verify OTP
            </button>
        </div>

        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">
                Didn't receive the code? 
                <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    Resend OTP
                </a>
            </p>
        </div>
    </form>

    <!-- JavaScript for OTP Input -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.otp-input');
            const hiddenOtp = document.getElementById('otp');
            const form = document.getElementById('otpForm');
            
            // Focus first input on load
            inputs[0].focus();

            // Handle input
            inputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    const value = e.target.value;
                    
                    // Only allow numbers
                    if (!/^\d*$/.test(value)) {
                        e.target.value = '';
                        return;
                    }

                    // Move to next input if value entered
                    if (value && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }

                    // Update hidden OTP field
                    updateOTP();
                });

                // Handle backspace
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });

                // Handle paste
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text');
                    const digits = pastedData.match(/\d/g);
                    
                    if (digits) {
                        digits.slice(0, 6).forEach((digit, i) => {
                            if (inputs[i]) {
                                inputs[i].value = digit;
                            }
                        });
                        updateOTP();
                        // Focus last filled input or next empty
                        const lastIndex = Math.min(digits.length, 6) - 1;
                        inputs[lastIndex].focus();
                    }
                });
            });

            function updateOTP() {
                let otp = '';
                inputs.forEach(input => {
                    otp += input.value;
                });
                hiddenOtp.value = otp;
            }

            // Timer countdown (10 minutes)
            let timeLeft = 600; // 10 minutes in seconds
            const timerElement = document.getElementById('timer');
            
            const countdown = setInterval(() => {
                timeLeft--;
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                
                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    timerElement.textContent = 'Expired';
                    timerElement.classList.add('text-red-600');
                    document.getElementById('verifyBtn').disabled = true;
                    document.getElementById('verifyBtn').classList.add('opacity-50', 'cursor-not-allowed');
                } else if (timeLeft <= 60) {
                    timerElement.classList.add('text-red-600');
                    timerElement.classList.remove('text-blue-600');
                }
            }, 1000);
        });
    </script>
</x-guest-layout>
