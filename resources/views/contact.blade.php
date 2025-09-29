<x-main-layout>
    <div class="bg-food-cream py-16">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-16">
                <h1 class="text-5xl font-bold text-food-dark mb-4">Get In Touch 📞</h1>
                <p class="text-xl text-food-text-muted max-w-3xl mx-auto">
                    We'd love to hear from you! Reach out for reservations, feedback, or just to say hello
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <!-- Contact Form -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-3xl font-bold text-food-dark mb-6">Send Us a Message 💌</h2>
                    
                    @if(session('success'))
                        <div class="bg-food-success text-white p-4 rounded-lg mb-6 shadow-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-food-dark mb-2">
                                Full Name *
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                value="{{ old('name') }}"
                                class="w-full px-4 py-3 border border-food-border rounded-lg focus:ring-2 focus:ring-food-primary focus:border-transparent @error('name') border-red-500 @enderror"
                                placeholder="Your full name"
                                required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-food-dark mb-2">
                                Email Address *
                            </label>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                value="{{ old('email') }}"
                                class="w-full px-4 py-3 border border-food-border rounded-lg focus:ring-2 focus:ring-food-primary focus:border-transparent @error('email') border-red-500 @enderror"
                                placeholder="your.email@example.com"
                                required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-sm font-medium text-food-dark mb-2">
                                Subject *
                            </label>
                            <select 
                                name="subject" 
                                id="subject" 
                                class="w-full px-4 py-3 border border-food-border rounded-lg focus:ring-2 focus:ring-food-primary focus:border-transparent @error('subject') border-red-500 @enderror"
                                required>
                                <option value="">Select a subject</option>
                                <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                <option value="Reservation" {{ old('subject') == 'Reservation' ? 'selected' : '' }}>Reservation</option>
                                <option value="Catering" {{ old('subject') == 'Catering' ? 'selected' : '' }}>Catering Services</option>
                                <option value="Feedback" {{ old('subject') == 'Feedback' ? 'selected' : '' }}>Feedback</option>
                                <option value="Complaint" {{ old('subject') == 'Complaint' ? 'selected' : '' }}>Complaint</option>
                                <option value="Partnership" {{ old('subject') == 'Partnership' ? 'selected' : '' }}>Business Partnership</option>
                                <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('subject')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-food-dark mb-2">
                                Message *
                            </label>
                            <textarea 
                                name="message" 
                                id="message" 
                                rows="5"
                                class="w-full px-4 py-3 border border-food-border rounded-lg focus:ring-2 focus:ring-food-primary focus:border-transparent @error('message') border-red-500 @enderror"
                                placeholder="Tell us how we can help you..."
                                required>{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button 
                                type="submit" 
                                class="w-full bg-food-primary text-white py-3 px-6 rounded-lg font-semibold hover:bg-food-primary-dark transition shadow-md">
                                🚀 Send Message
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Contact Information -->
                <div class="space-y-8">
                    <!-- Business Info -->
                    <div class="bg-white rounded-lg shadow-md p-8">
                        <h2 class="text-3xl font-bold text-food-dark mb-6">Visit Us Today 🏪</h2>
                        
                        <div class="space-y-6">
                            <!-- Address -->
                            <div class="flex items-start space-x-4">
                                <div class="text-2xl">📍</div>
                                <div>
                                    <h3 class="font-semibold text-food-dark mb-1">Our Location</h3>
                                    <p class="text-food-text-muted">
                                        123 Flavor Street, Foodie District<br>
                                        Delicious City, DC 12345<br>
                                        United States
                                    </p>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="flex items-start space-x-4">
                                <div class="text-2xl">📞</div>
                                <div>
                                    <h3 class="font-semibold text-food-dark mb-1">Call Us</h3>
                                    <p class="text-food-text-muted">
                                        Main: (555) 123-FOOD<br>
                                        Reservations: (555) 123-BOOK<br>
                                        Catering: (555) 123-CATER
                                    </p>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="flex items-start space-x-4">
                                <div class="text-2xl">📧</div>
                                <div>
                                    <h3 class="font-semibold text-food-dark mb-1">Email Us</h3>
                                    <p class="text-food-text-muted">
                                        General: info@foodandbeverage.com<br>
                                        Support: support@foodandbeverage.com<br>
                                        Catering: catering@foodandbeverage.com
                                    </p>
                                </div>
                            </div>

                            <!-- Social Media -->
                            <div class="flex items-start space-x-4">
                                <div class="text-2xl">🌐</div>
                                <div>
                                    <h3 class="font-semibold text-food-dark mb-1">Follow Us</h3>
                                    <div class="flex space-x-3 mt-2">
                                        <span class="bg-food-primary text-white px-3 py-1 rounded text-sm">📘 Facebook</span>
                                        <span class="bg-food-primary text-white px-3 py-1 rounded text-sm">📷 Instagram</span>
                                        <span class="bg-food-primary text-white px-3 py-1 rounded text-sm">🐦 Twitter</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hours -->
                    <div class="bg-white rounded-lg shadow-md p-8">
                        <h2 class="text-2xl font-bold text-food-dark mb-6">Opening Hours ⏰</h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-food-light-dark">
                                <span class="font-medium text-food-dark">Monday - Thursday</span>
                                <span class="text-food-text-muted">11:00 AM - 10:00 PM</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-food-light-dark">
                                <span class="font-medium text-food-dark">Friday - Saturday</span>
                                <span class="text-food-text-muted">11:00 AM - 11:00 PM</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-food-light-dark">
                                <span class="font-medium text-food-dark">Sunday</span>
                                <span class="text-food-text-muted">12:00 PM - 9:00 PM</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="font-medium text-food-dark">Holidays</span>
                                <span class="text-food-text-muted">Special Hours</span>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-food-light rounded-lg">
                            <p class="text-food-dark text-sm">
                                <strong>🍽️ Kitchen closes 30 minutes before closing time.</strong><br>
                                Last orders for delivery are accepted 1 hour before closing.
                            </p>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-food-primary rounded-lg p-8 text-white text-center">
                        <h2 class="text-2xl font-bold mb-4">Need Immediate Help? 🆘</h2>
                        <p class="mb-6 opacity-90">For urgent matters or same-day reservations</p>
                        <div class="space-y-3">
                            <a href="tel:+15551234663" class="block bg-white text-food-primary py-3 px-6 rounded-lg font-semibold hover:bg-food-cream transition">
                                📞 Call Now: (555) 123-FOOD
                            </a>
                            <a href="{{ route('products.index') }}" class="block bg-food-secondary text-white py-3 px-6 rounded-lg font-semibold hover:bg-opacity-80 transition">
                                🛒 Order Online Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="mt-16">
                <h2 class="text-4xl font-bold text-food-dark text-center mb-12">Frequently Asked Questions ❓</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="font-bold text-food-dark mb-3">Do you take reservations?</h3>
                        <p class="text-food-text-muted text-sm">
                            Yes! We accept reservations for parties of 4 or more. Call us at (555) 123-BOOK 
                            or send us a message through the contact form.
                        </p>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="font-bold text-food-dark mb-3">Do you offer catering services?</h3>
                        <p class="text-food-text-muted text-sm">
                            Absolutely! We provide catering for events of all sizes. Contact our catering team 
                            at (555) 123-CATER for custom menus and pricing.
                        </p>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="font-bold text-food-dark mb-3">What's your delivery area?</h3>
                        <p class="text-food-text-muted text-sm">
                            We deliver within a 5-mile radius of our restaurant. Check if we deliver to 
                            your area by entering your address during checkout.
                        </p>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="font-bold text-food-dark mb-3">Do you accommodate dietary restrictions?</h3>
                        <p class="text-food-text-muted text-sm">
                            Yes! We offer vegetarian, vegan, and gluten-free options. Please inform us of any 
                            allergies or dietary requirements when ordering.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>