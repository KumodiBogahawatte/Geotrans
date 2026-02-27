<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Geotrans</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">

    <!-- Include Header -->
    <?php include 'includes/header.php'; ?>

    <section class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 items-start">

            <!-- Left Side: Contact Form -->
            <div class="order-2 lg:order-1">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">CONNECT WITH US</h2>
                <p class="text-gray-600 mb-6 sm:mb-8">Contact us for all your questions and opinions</p>

                <div id="contact-message" class="hidden mb-4 p-4 rounded-lg"></div>

                <form id="contact-form" class="space-y-4 sm:space-y-6">

                    <!-- Name Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="first_name"
                                required
                                class="w-full px-3 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7D1074] focus:border-transparent text-base sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="last_name"
                                required
                                class="w-full px-3 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7D1074] focus:border-transparent text-base sm:text-sm">
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            name="email"
                            required
                            class="w-full px-3 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7D1074] focus:border-transparent text-base sm:text-sm">
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number <span class="text-gray-400">(Optional)</span>
                        </label>
                        <input
                            type="tel"
                            name="phone"
                            class="w-full px-3 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-base sm:text-sm">
                    </div>

                    <!-- Subject -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Subject <span class="text-gray-400">(Optional)</span>
                        </label>
                        <input
                            type="text"
                            name="subject"
                            class="w-full px-3 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7D1074] focus:border-transparent text-base sm:text-sm">
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Message <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            name="message"
                            required
                            rows="5"
                            placeholder="Hi! I'd like to ask about..."
                            class="w-full px-3 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7D1074] focus:border-transparent resize-none text-base sm:text-sm"></textarea>
                    </div>

                    <!-- Terms Checkbox -->
                    <div class="flex items-start">
                        <input
                            type="checkbox"
                            id="terms"
                            required
                            class="mt-1 w-5 h-5 sm:w-4 sm:h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500 flex-shrink-0">
                        <label for="terms" class="ml-3 sm:ml-2 text-sm text-gray-600 leading-relaxed">
                            I want to receive news and updates once in a while. By submitting, I'm agreeing to the
                            <a href="#" class="text-[#7D1074] hover:text-[#7D1074] underline">Terms & Conditions</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        id="submit-btn"
                        class="w-full sm:w-auto bg-[#7D1074] hover:bg-[#7a3a6f] text-white font-semibold px-8 py-3 sm:py-2 rounded-lg transition-colors text-base sm:text-sm">
                        SEND MESSAGE
                    </button>

                </form>

                <script>
                    document.getElementById('contact-form').addEventListener('submit', async function(e) {
                        e.preventDefault();

                        const submitBtn = document.getElementById('submit-btn');
                        const messageDiv = document.getElementById('contact-message');
                        const formData = new FormData(this);

                        // Disable button
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'SENDING...';

                        try {
                            const response = await fetch('api/contact.php', {
                                method: 'POST',
                                body: formData
                            });

                            const data = await response.json();

                            messageDiv.className = data.success ?
                                'mb-4 p-4 rounded-lg bg-green-100 border border-green-400 text-green-700' :
                                'mb-4 p-4 rounded-lg bg-red-100 border border-red-400 text-red-700';
                            messageDiv.textContent = data.message;
                            messageDiv.classList.remove('hidden');

                            if (data.success) {
                                this.reset();
                            }

                        } catch (error) {
                            messageDiv.className = 'mb-4 p-4 rounded-lg bg-red-100 border border-red-400 text-red-700';
                            messageDiv.textContent = 'An error occurred. Please try again.';
                            messageDiv.classList.remove('hidden');
                        } finally {
                            submitBtn.disabled = false;
                            submitBtn.textContent = 'SEND MESSAGE';
                        }
                    });
                </script>
            </div>

            <!-- Right Side: Contact Info & Map -->
            <div class="flex flex-col order-1 lg:order-2">

                <!-- Branch 1: HEAD QUARTER -->
                <div class="bg-white rounded-2xl p-4 sm:p-6 mb-4 sm:mb-6 shadow-sm">
                    <p class="text-xs text-gray-500 uppercase mb-2">BRANCH 1 HEAD QUARTER</p>
                    <h3 class="font-bold text-gray-900 mb-2 text-sm sm:text-base">No 60 Dhamma Road Colombo 06</h3>
                    <p class="text-[#7D1074] font-semibold mb-2 text-sm sm:text-base">071 3757555</p>
                    <p class="text-gray-700 mb-3 text-sm">Email: <a href="mailto:info@geotrans.com" class="text-[#7D1074] font-semibold break-all">info@geotrans.com</a></p>

                    <div class="h-px bg-gray-200 my-4"></div>

                    <p class="text-xs text-gray-500 uppercase mb-2">BRANCH 2 SHOWROOM</p>
                    <h3 class="font-bold text-gray-900 mb-2 text-sm sm:text-base">No 60 Dhamma Road Colombo 06</h3>
                    <p class="text-[#7D1074] font-semibold text-sm sm:text-base">071 3757555</p>

                    <!-- Social Media Links (moved inside info card) -->
                    <div class="flex space-x-3 mt-4">
                        <a href="#" class="w-10 h-10 sm:w-9 sm:h-9 bg-gray-900 hover:bg-[#7D1074] text-white rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 sm:w-9 sm:h-9 bg-gray-900 hover:bg-[#7D1074] text-white rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 sm:w-9 sm:h-9 bg-gray-900 hover:bg-[#7D1074] text-white rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23 9.71a8.5 8.5 0 00-.91-4.13 2.92 2.92 0 00-1.72-1A78.36 78.36 0 0012 4.27a78.45 78.45 0 00-8.34.3 2.87 2.87 0 00-1.46.74c-.9.83-1 2.25-1.1 3.45a48.29 48.29 0 000 6.48 9.55 9.55 0 00.3 2.12 2.93 2.93 0 001.71 1.54A78.36 78.36 0 0012 19.73a78.45 78.45 0 008.34-.3 2.87 2.87 0 001.46-.74c.9-.83 1-2.25 1.1-3.45a48.29 48.29 0 000-6.48zM9.74 14.85V8.66l5.92 3.11z"></path>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 sm:w-9 sm:h-9 bg-gray-900 hover:bg-[#7D1074] text-white rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
                                <path d="M7.5 2h9A5.5 5.5 0 0122 7.5v9a5.5 5.5 0 01-5.5 5.5h-9A5.5 5.5 0 012 16.5v-9A5.5 5.5 0 017.5 2z"></path>
                            </svg>
                        </a>
                    </div>

                </div>

                <!-- Laptop Image -->
                <div class="rounded-2xl overflow-hidden">
                    <img src="assets/images/home/CONTACT.png" alt="Laptop" class="w-full h-48 sm:h-64 md:h-80 lg:h-96 object-cover">
                </div>

            </div>

        </div>

        <!-- Google Map (full-width below the contact form) -->
        <div class="mt-6 sm:mt-8 rounded-2xl p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">FIND US ON GOOGLE MAP</h3>
            <div class="w-full h-64 sm:h-80 md:h-96 bg-gray-200 rounded-xl overflow-hidden">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.0803868729354!2d79.86091627478677!3d6.880973118906459!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae25bd74363a335%3A0x45af5cc98c2f874e!2sGeotrans%20Pvt%20Ltd!5e0!3m2!1sen!2slk!4v1772176435936!5m2!1sen!2slk" width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    class="rounded-lg"></iframe>
            </div>
        </div>

    </section>

    <!-- Include Header -->
    <?php include 'includes/footer.php'; ?>
</body>

</html>