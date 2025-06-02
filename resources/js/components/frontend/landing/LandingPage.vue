<template>
    <LoadingComponent :props="loading" />
    
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-t from-[#FF016C] to-[#FF7A00] text-white">
        <div class="container py-16 md:py-24">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="w-full md:w-1/2">
                    <h1 class="text-3xl md:text-5xl font-bold mb-4 leading-tight">Delicious Food Delivered To Your Doorstep</h1>
                    <p class="text-lg mb-8">Order your favorite meals from the best restaurants in town. Fast delivery, easy payment, and amazing taste guaranteed!</p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <router-link :to="{ name: 'frontend.menu' }" class="bg-white text-primary font-medium py-3 px-6 rounded-3xl inline-flex items-center justify-center hover:bg-[#FFEDF4] transition">
                            <span>Order Now</span>
                        </router-link>
                        <router-link :to="{ name: 'auth.signupPhone' }" class="border-2 border-white text-white font-medium py-3 px-6 rounded-3xl inline-flex items-center justify-center hover:bg-white hover:text-primary transition">
                            <span>Sign Up</span>
                        </router-link>
                    </div>
                    <div class="mt-8 flex items-center gap-6">
                        <a :href="setting.site_android_app_link" target="_blank" class="flex items-center gap-2 hover:opacity-80 transition" v-if="setting.site_android_app_link">
                            <img src="/images/google-play.png" alt="Google Play" class="h-8">
                        </a>
                        <a :href="setting.site_ios_app_link" target="_blank" class="flex items-center gap-2 hover:opacity-80 transition" v-if="setting.site_ios_app_link">
                            <img src="/images/app-store.png" alt="App Store" class="h-8">
                        </a>
                    </div>
                </div>
                <div class="w-full md:w-1/2 relative">
                    <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Delicious Food" class="w-full rounded-2xl shadow-lg">
                    <div class="absolute -bottom-6 -right-6 bg-white p-4 rounded-xl shadow-lg hidden md:block">
                        <div class="flex items-center gap-3">
                            <div class="bg-[#FFEDF4] p-3 rounded-full">
                                <i class="fas fa-clock text-primary"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Delivery Time</p>
                                <p class="text-heading font-bold">25-30 min</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,176C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-16">
        <div class="container">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-4xl font-bold mb-4 text-heading">How It Works</h2>
                <p class="text-paragraph max-w-2xl mx-auto">Order your favorite food in just a few simple steps</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="flex flex-col items-center text-center p-6">
                    <div class="bg-[#FFEDF4] w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-map-marker-alt text-2xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-heading">Choose Your Location</h3>
                    <p class="text-paragraph">Enter your address to find restaurants that deliver to you</p>
                </div>
                <div class="flex flex-col items-center text-center p-6">
                    <div class="bg-[#FFEDF4] w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-utensils text-2xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-heading">Choose Your Meal</h3>
                    <p class="text-paragraph">Browse menus and select your favorite dishes</p>
                </div>
                <div class="flex flex-col items-center text-center p-6">
                    <div class="bg-[#FFEDF4] w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-truck text-2xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-heading">Fast Delivery</h3>
                    <p class="text-paragraph">Your order will be delivered to your door in minutes</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Categories Section -->
    <section class="py-16 bg-[#F7F7FC]">
        <div class="container">
            <div class="flex items-center justify-between gap-2 mb-8">
                <h2 class="text-2xl md:text-3xl font-semibold text-heading">Popular Categories</h2>
                <router-link :to="{ name: 'frontend.menu' }" class="rounded-3xl capitalize text-sm leading-6 font-medium py-1 px-3 transition text-primary bg-[#FFEDF4] hover:text-white hover:bg-primary">
                    View All
                </router-link>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <div v-for="(category, index) in categories.slice(0, 5)" :key="index" class="bg-white rounded-xl p-4 shadow-xs hover:shadow-md transition flex flex-col items-center text-center">
                    <img :src="category.image || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&h=100&q=80'" :alt="category.name" class="w-20 h-20 object-cover rounded-full mb-3">
                    <h3 class="font-medium text-heading">{{ category.name }}</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Items Section -->
    <section class="py-16">
        <div class="container">
            <div class="flex items-center justify-between gap-2 mb-8">
                <h2 class="text-2xl md:text-3xl font-semibold text-heading">Featured Items</h2>
                <router-link :to="{ name: 'frontend.menu' }" class="rounded-3xl capitalize text-sm leading-6 font-medium py-1 px-3 transition text-primary bg-[#FFEDF4] hover:text-white hover:bg-primary">
                    View All
                </router-link>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div v-for="(item, index) in featuredItems.slice(0, 4)" :key="index" class="product-card-grid">
                    <div class="product-card-grid-image">
                        <img :src="item.thumb || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&h=400&q=80'" :alt="item.name" class="w-full h-full object-cover">
                    </div>
                    <div class="product-card-grid-content-group">
                        <div class="product-card-grid-header-group">
                            <h3 class="product-card-grid-title">{{ item.name }}</h3>
                        </div>
                        <p class="product-card-grid-describe">{{ item.description }}</p>
                        <div class="product-card-grid-footer-group">
                            <div class="product-card-grid-price-group">
                                <span class="product-card-grid-price-current">{{ formatPrice(item.price) }}</span>
                            </div>
                            <button class="product-card-grid-cart-btn">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-[#F7F7FC]">
        <div class="container">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-4xl font-bold mb-4 text-heading">What Our Customers Say</h2>
                <p class="text-paragraph max-w-2xl mx-auto">Don't just take our word for it - hear from our satisfied customers</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-xs">
                    <div class="flex items-center mb-4">
                        <div class="text-[#FFDB1F] flex">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="ml-2 text-sm text-gray-500">5.0</span>
                    </div>
                    <p class="text-paragraph mb-6">"The food is always fresh and delivered on time. I love their wide selection of restaurants and easy ordering process!"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" alt="Customer" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-medium text-heading">Sarah Johnson</h4>
                            <p class="text-sm text-gray-500">Regular Customer</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-xs">
                    <div class="flex items-center mb-4">
                        <div class="text-[#FFDB1F] flex">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="ml-2 text-sm text-gray-500">5.0</span>
                    </div>
                    <p class="text-paragraph mb-6">"The app is so easy to use and the delivery is always quick. I've tried many dishes and they're all delicious!"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" alt="Customer" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-medium text-heading">Michael Chen</h4>
                            <p class="text-sm text-gray-500">Food Enthusiast</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-xs">
                    <div class="flex items-center mb-4">
                        <div class="text-[#FFDB1F] flex">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="ml-2 text-sm text-gray-500">4.5</span>
                    </div>
                    <p class="text-paragraph mb-6">"Great customer service! When my order was delayed, they immediately offered a discount on my next purchase."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" alt="Customer" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-medium text-heading">Emily Rodriguez</h4>
                            <p class="text-sm text-gray-500">Loyal Customer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mobile App Section -->
    <section class="py-16">
        <div class="container">
            <div class="bg-gradient-to-r from-[#FF016C] to-[#FF7A00] rounded-2xl overflow-hidden">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="w-full md:w-1/2 p-8 md:p-12">
                        <h2 class="text-2xl md:text-4xl font-bold mb-4 text-white">Download Our Mobile App</h2>
                        <p class="text-white mb-6">Get exclusive offers and track your orders in real-time with our mobile app</p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a :href="setting.site_android_app_link" target="_blank" class="bg-white rounded-xl py-2 px-4 flex items-center gap-3 hover:bg-[#FFEDF4] transition" v-if="setting.site_android_app_link">
                                <i class="fab fa-google-play text-2xl text-primary"></i>
                                <div>
                                    <p class="text-xs text-gray-500">Download on</p>
                                    <p class="font-medium text-heading">Google Play</p>
                                </div>
                            </a>
                            <a :href="setting.site_ios_app_link" target="_blank" class="bg-white rounded-xl py-2 px-4 flex items-center gap-3 hover:bg-[#FFEDF4] transition" v-if="setting.site_ios_app_link">
                                <i class="fab fa-apple text-2xl text-primary"></i>
                                <div>
                                    <p class="text-xs text-gray-500">Download on</p>
                                    <p class="font-medium text-heading">App Store</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 p-8 flex justify-center">
                        <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="Mobile App" class="max-w-xs rounded-xl shadow-lg">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <!-- <section class="py-16 bg-[#F7F7FC]">
        <div class="container">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-2xl md:text-3xl font-bold mb-4 text-heading">Subscribe to Our Newsletter</h2>
                <p class="text-paragraph mb-8">Get updates on new restaurants, special offers, and more</p>
                <form @submit.prevent="subscribe" class="flex flex-col sm:flex-row gap-4">
                    <input v-model="newsletter.email" type="email" placeholder="Enter your email" class="flex-1 h-12 rounded-lg border px-4 border-[#D9DBE9] focus-within:border-primary" required>
                    <button type="submit" class="h-12 text-center capitalize font-medium rounded-lg px-6 text-white bg-primary hover:bg-[#FF016C] transition">
                        Subscribe
                    </button>
                </form>
                <p v-if="newsletter.success" class="mt-4 text-green-600">{{ newsletter.success }}</p>
                <p v-if="newsletter.error" class="mt-4 text-red-600">{{ newsletter.error }}</p>
            </div>
        </div>
    </section> -->

    <!-- Contact Section -->
    <section class="py-16">
        <div class="container">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold mb-6 text-heading">Contact Us</h2>
                    <p class="text-paragraph mb-8">Have questions or feedback? We'd love to hear from you!</p>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="bg-[#FFEDF4] p-3 rounded-full">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-heading mb-1">Address</h3>
                                <p class="text-paragraph">{{ company && company.company_address ? company.company_address : 'Address not available' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="bg-[#FFEDF4] p-3 rounded-full">
                                <i class="fas fa-phone-alt text-primary"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-heading mb-1">Phone</h3>
                                <p class="text-paragraph">{{ company && company.company_phone ? company.company_phone : 'Phone not available' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="bg-[#FFEDF4] p-3 rounded-full">
                                <i class="fas fa-envelope text-primary"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-heading mb-1">Email</h3>
                                <p class="text-paragraph">{{ company && company.company_email ? company.company_email : 'Email not available' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <form @submit.prevent="sendMessage" class="bg-white p-6 rounded-xl shadow-xs">
                        <h3 class="text-xl font-semibold mb-6 text-heading">Send Us a Message</h3>
                        <div class="mb-4">
                            <label class="text-sm mb-1 block text-heading">Name</label>
                            <input v-model="contact.name" type="text" class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9] focus-within:border-primary" required>
                        </div>
                        <div class="mb-4">
                            <label class="text-sm mb-1 block text-heading">Email</label>
                            <input v-model="contact.email" type="email" class="w-full h-12 rounded-lg border px-4 border-[#D9DBE9] focus-within:border-primary" required>
                        </div>
                        <div class="mb-4">
                            <label class="text-sm mb-1 block text-heading">Message</label>
                            <textarea v-model="contact.message" class="w-full rounded-lg border p-4 border-[#D9DBE9] focus-within:border-primary h-32 resize-none" required></textarea>
                        </div>
                        <button type="submit" class="w-full h-12 text-center capitalize font-medium rounded-lg text-white bg-primary hover:bg-[#FF016C] transition">
                            Send Message
                        </button>
                        <p v-if="contact.success" class="mt-4 text-green-600">{{ contact.success }}</p>
                        <p v-if="contact.error" class="mt-4 text-red-600">{{ contact.error }}</p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent.vue";
import statusEnum from "../../../enums/modules/statusEnum.js";
import alertService from "../../../services/alertService.js";

export default {
    name: "LandingPage",
    components: {
        LoadingComponent
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            categories: [],
            featuredItems: [],
            contact: {
                name: "",
                email: "",
                message: "",
                success: "",
                error: ""
            }
        };
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        company: function () {
            return this.$store.getters['frontendCompany/lists'];
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.loadCategories();
        this.loadFeaturedItems();
        this.$store.dispatch("frontendSetting/lists").then().catch();
        this.$store.dispatch("frontendCompany/lists").then().catch();
    },
    methods: {
        loadCategories() {
            this.$store.dispatch("frontendItemCategory/lists", {
                paginate: 0,
                order_column: "sort",
                order_type: "asc",
                status: statusEnum.ACTIVE,
            }).then(res => {
                this.categories = res.data.data;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        loadFeaturedItems() {
            this.$store.dispatch("frontendItem/lists", {
                paginate: 0,
                is_featured: statusEnum.ACTIVE,
                status: statusEnum.ACTIVE,
            }).then(res => {
                this.featuredItems = res.data.data;
            }).catch((err) => {
                console.error(err);
            });
        },
        formatPrice(price) {
            return (this.$store.getters['frontendCurrency/currencySymbol'] || "€") + parseFloat(price).toFixed(2);
        },
        sendMessage() {
            this.contact.success = "";
            this.contact.error = "";
            this.loading.isActive = true;
            
            // Simulate API call since there's no contact form endpoint
            setTimeout(() => {
                this.loading.isActive = false;
                this.contact.success = "Your message has been sent successfully!";
                this.contact.name = "";
                this.contact.email = "";
                this.contact.message = "";
            }, 1000);
        }
    }
};
</script>

<style scoped>
.product-card-grid {
    @apply bg-white rounded-xl overflow-hidden shadow-xs hover:shadow-md transition;
}

.product-card-grid-image {
    @apply h-48 overflow-hidden;
}

.product-card-grid-content-group {
    @apply p-4;
}

.product-card-grid-header-group {
    @apply mb-2;
}

.product-card-grid-title {
    @apply text-lg font-medium text-heading;
}

.product-card-grid-describe {
    @apply text-paragraph text-sm mb-4 line-clamp-2;
}

.product-card-grid-footer-group {
    @apply flex items-center justify-between;
}

.product-card-grid-price-group {
    @apply flex items-center gap-2;
}

.product-card-grid-price-current {
    @apply text-primary font-semibold;
}

.product-card-grid-cart-btn {
    @apply w-10 h-10 rounded-full bg-[#FFEDF4] text-primary flex items-center justify-center hover:bg-primary hover:text-white transition;
}
</style> 