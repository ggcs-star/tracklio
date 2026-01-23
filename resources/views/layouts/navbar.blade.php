@php
    $user = Auth::user();
    $userName = $user->name ?? 'Guest User';

    $initials = collect(explode(' ', $userName))
                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                    ->join('');
@endphp

<style>
    [x-cloak] { display: none !important; }
    .notification-slide {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s ease;
    }
</style>

<div class="w-full flex items-center justify-between gap-4">

    
    <h2 class="text-xl font-bold hidden md:block">Dashboard</h2>

    
    <div class="flex items-center gap-5 ml-auto">

        
        <div class="hidden md:flex items-center bg-[#F1F3F9] px-4 py-2 rounded-full border w-[320px]">
            <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
            </svg>
            <input type="text" placeholder="Search posts..." class="bg-transparent outline-none text-sm w-full"/>
        </div>

       
        <div x-data="notificationDrawer()" x-init="init()" x-cloak class="relative">

            
            <button @click="toggle()"
                    class="relative focus:outline-none group p-2.5 rounded-xl hover:bg-gradient-to-r hover:from-[#F5F7FF] hover:to-[#EFF1FF] transition-all duration-300">
                
                <div class="relative">
                    
                    <span x-show="unreadCount > 0"
                          x-text="unreadCount"
                          class="absolute -top-2 -right-2 bg-gradient-to-br from-red-500 to-pink-500 text-white
                                 text-[10px] sm:text-[11px] font-bold min-w-[18px] h-[18px] sm:min-w-[20px] sm:h-[20px]
                                 flex items-center justify-center rounded-full
                                 ring-2 ring-white shadow-lg animate-pulse"></span>

                    <svg class="w-6 h-6 text-gray-700 group-hover:text-[#4C6FFF] transition-all duration-300 
                                transform group-hover:rotate-12 group-hover:scale-110" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 12V8
                                 a6 6 0 10-12 0v4c0 .386-.146.735-.405
                                 1.002L4 17h5m6 0v1a3 3 0 11-6 0v-1"/>
                    </svg>
                </div>
            </button>

            
            <div x-show="open"
                 x-transition.opacity.duration.200ms
                 @click="close"
                 class="fixed inset-0 bg-black/30 backdrop-blur-[2px] z-40"></div>

            
            <div x-show="open"
                 x-transition:enter="notification-slide"
                 x-transition:enter-start="translate-x-full opacity-0"
                 x-transition:enter-end="translate-x-0 opacity-100"
                 x-transition:leave="notification-slide"
                 x-transition:leave-start="translate-x-0 opacity-100"
                 x-transition:leave-end="translate-x-full opacity-0"
                 class="fixed top-0 right-0 h-full w-full sm:w-[420px] bg-gradient-to-b from-white to-gray-50
                        z-50 shadow-[0_0_50px_-12px_rgba(0,0,0,0.25)] flex flex-col border-l border-gray-200/60">

                
                <div class="flex items-center justify-between px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-200/70 bg-white/95">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-[#4C6FFF] to-[#6A5FFF] 
                                    flex items-center justify-center shadow-md">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 12V8a6 6 0 00-12 0v4c0 .386-.146.735-.405 1.002L4 17h5m6 0v1a3 3 0 11-6 0v-1"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-base sm:text-lg text-gray-900">Notifications</h3>
                            <p x-show="notifications.length" class="text-xs text-gray-500 mt-0.5">
                                <span x-text="unreadCount" class="font-semibold text-[#4C6FFF]"></span> unread of 
                                <span x-text="notifications.length"></span> total
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 sm:gap-2">
                        <button x-show="notifications.length"
                                @click="clearAll"
                                class="p-1.5 sm:p-2 rounded-lg hover:bg-red-50 text-gray-500 hover:text-red-600 
                                       transition-colors duration-200 group"
                                title="Clear all notifications">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" 
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                        <button @click="close" 
                                class="p-1.5 sm:p-2 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-700 
                                       transition-colors duration-200">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

               
                <div class="flex-1 overflow-y-auto">
                    <template x-for="note in notifications" :key="note.id">
                        <div class="relative flex items-start gap-3 sm:gap-4 px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100/80 hover:bg-gray-50/80 
                                    transition-all duration-200 group"
                             :class="!note.is_read ? 'bg-gradient-to-r from-blue-50/60 to-white' : ''">
                            
                            
                            <div x-show="!note.is_read" 
                                 class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-[#4C6FFF] to-[#6A5FFF]"></div>

                            
                            <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl flex items-center justify-center shadow-sm"
                                 :class="!note.is_read ? 
                                         'bg-gradient-to-br from-[#4C6FFF]/10 to-[#6A5FFF]/10 ring-1 ring-[#4C6FFF]/20' : 
                                         'bg-gray-100'">
                                <span x-text="icon(note.type)" 
                                      class="text-xl sm:text-2xl"
                                      :class="!note.is_read ? 'opacity-100' : 'opacity-80'"></span>
                            </div>

                            
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-800 leading-relaxed" 
                                   x-text="note.message"></p>
                                
                                <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-3 mt-2">
                                    
                                    <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-0.5 rounded-full text-xs font-medium 
                                                  capitalize tracking-wide self-start"
                                          :class="!note.is_read ? 
                                                  'bg-[#4C6FFF]/10 text-[#4C6FFF]' : 
                                                  'bg-gray-100 text-gray-600'">
                                        <span x-text="note.type.replace('_', ' ')"></span>
                                    </span>
                                    
                                    
                                    <p class="text-xs text-gray-500 font-medium"
                                       x-text="timeAgo(note.created_at)"></p>
                                </div>
                            </div>

                           
                            <button @click="toggleRead(note)"
                                    class="flex-shrink-0 text-xs px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-lg font-medium transition-all duration-200
                                           transform hover:scale-105 active:scale-95 mt-2 sm:mt-0"
                                    :class="note.is_read ? 
                                            'text-gray-600 bg-gray-100 hover:bg-gray-200 hover:text-gray-800' : 
                                            'text-white bg-gradient-to-r from-[#4C6FFF] to-[#6A5FFF] hover:shadow-md'">
                                <span x-text="note.is_read ? 'Mark unread' : 'Mark read'"></span>
                            </button>
                        </div>
                    </template>

                    
                    <div x-show="!notifications.length"
                         class="h-full flex flex-col items-center justify-center px-4 sm:px-6 py-12 sm:py-16 text-center">
                        <div class="w-16 h-16 sm:w-24 sm:h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 
                                    flex items-center justify-center mb-4 sm:mb-6 shadow-inner">
                            <span class="text-3xl sm:text-4xl opacity-60">🔔</span>
                        </div>
                        <h4 class="font-bold text-gray-700 text-base sm:text-lg mb-2">No notifications yet</h4>
                        <p class="text-gray-500 text-xs sm:text-sm max-w-[200px] sm:max-w-[280px] leading-relaxed">
                            When you get notifications, they'll show up here. Stay tuned!
                        </p>
                    </div>
                </div>

                
                <div class="border-t border-gray-200/70 px-4 sm:px-6 py-3 sm:py-4 bg-white/95"
                     x-show="!showAll && notifications.length === 10">
                    <button @click="fetchAll()"
                            class="w-full py-2.5 sm:py-3 text-xs sm:text-sm font-semibold text-white 
                                   bg-gradient-to-r from-[#4C6FFF] to-[#6A5FFF]
                                   rounded-lg sm:rounded-xl shadow-md hover:shadow-lg 
                                   transform hover:-translate-y-0.5 transition-all duration-300
                                   flex items-center justify-center gap-2 group">
                        View all notifications
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 group-hover:translate-x-1 transition-transform" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

<button onclick="showPlanModal()"
        class="px-4 py-2 bg-gradient-to-r from-[#4C6FFF] to-[#8B5CF6] 
               text-white text-sm font-semibold rounded-lg hover:shadow-md 
               transition-all duration-300 hidden sm:flex items-center gap-2">
    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
    </svg>
    Get Pro
</button>


<button onclick="showPlanModal()"
        class="sm:hidden flex items-center justify-center w-10 h-10 
               bg-gradient-to-r from-[#4C6FFF] to-[#8B5CF6] 
               text-white rounded-lg">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
    </svg>
</button>
       
        <a href="{{ route('profile') }}" class="hidden sm:flex items-center gap-3">
            <div class="text-right">
                <p class="font-semibold text-sm">{{ $userName }}</p>
                <p class="text-xs text-gray-500">{{ $user->role ?? 'User' }}</p>
            </div>
            <div class="w-10 h-10 rounded-full overflow-hidden border border-[#4C6FFF]/20">
                @if($user && $user->avatar)
                    <img src="{{ asset('storage/'.$user->avatar) }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-[#E6E8FF] flex items-center justify-center
                                text-[#4C6FFF] font-semibold">
                        {{ $initials }}
                    </div>
                @endif
            </div>
        </a>
    </div>
</div>

<script>
function notificationDrawer() {
    return {
        open: false,
        notifications: [],
        unreadCount: 0,
        showAll: false,

        init() {
            this.fetchUnreadCount();
        },

        toggle() {
            this.open ? this.close() : this.openDrawer();
        },

        openDrawer() {
            this.open = true;
            this.showAll = false;
            this.fetchLatest();

            setTimeout(() => this.markAllRead(), 600);
        },

        close() {
            this.open = false;
        },

        fetchLatest() {
            fetch('/notifications')
                .then(r => r.json())
                .then(d => this.notifications = d);
        },

        fetchAll() {
            this.showAll = true;
            fetch('/notifications/all')
                .then(r => r.json())
                .then(d => this.notifications = d);
        },

        fetchUnreadCount() {
            fetch('/notifications/unread-count')
                .then(r => r.json())
                .then(d => this.unreadCount = d.count);
        },

        markAllRead() {
            fetch('/notifications/mark-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN':
                        document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => this.unreadCount = 0);
        },

        toggleRead(note) {
            fetch(`/notifications/${note.id}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN':
                        document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => {
                note.is_read = !note.is_read;
                this.fetchUnreadCount();
            });
        },

        clearAll() {
            fetch('/notifications-clear-all', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN':
                        document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => {
                this.notifications = [];
                this.unreadCount = 0;
            });
        },

        icon(type) {
            const icons = {
                'profile_update': '👤',
                'post_published': '✅',
                'post_failed': '❌',
                'comment': '💬',
                'like': '❤️',
                'share': '🔄',
                'system': '⚙️',
                'warning': '⚠️',
                'info': 'ℹ️',
                'success': '🎉'
            };
            return icons[type] || '🔔';
        },

        timeAgo(date) {
            const diff = Math.floor((new Date() - new Date(date)) / 60000);
            if (diff < 1) return 'Just now';
            if (diff < 60) return diff + ' min ago';
            if (diff < 1440) return Math.floor(diff / 60) + ' hours ago';
            return Math.floor(diff / 1440) + ' days ago';
        }
    }
}
</script>
<script>
function showPlanModal() {
    console.log('showPlanModal called');
    
    if (!document.getElementById('planModal')) {
        console.log('Creating modal HTML');
        const modalHTML = `
            <div id="planModal" class="fixed inset-0 bg-black/40 hidden flex items-center justify-center z-[9999] p-4 backdrop-blur-sm">
                <div class="bg-white rounded-xl max-w-sm w-full shadow-2xl animate-fadeIn border border-gray-200 mx-4">
                    
                    <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-xl border-b">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Pro Plan</h3>
                                <p class="text-xs text-gray-600 mt-0.5">Perfect plan for growing your social media presence</p>
                            </div>
                            <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700 p-1 hover:bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center transition-colors">
                                <i class="fas fa-times text-base"></i>
                            </button>
                        </div>
                    </div>

                    
                    <div class="p-5">
                       
                        <div class="text-center mb-5">
                            <div class="flex items-baseline justify-center gap-1 mb-2">
                                <span class="text-3xl font-bold text-gray-900">₹999</span>
                                <span class="text-gray-500 text-sm">/month</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">Everything you need to grow your social media presence</p>
                        </div>

                       
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-indigo-500 text-sm mr-3"></i>
                                <span class="text-sm text-gray-700">Unlimited Projects & Links</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-indigo-500 text-sm mr-3"></i>
                                <span class="text-sm text-gray-700">10 Social Accounts</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-indigo-500 text-sm mr-3"></i>
                                <span class="text-sm text-gray-700">Advanced Analytics</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-indigo-500 text-sm mr-3"></i>
                                <span class="text-sm text-gray-700">Priority Support</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-indigo-500 text-sm mr-3"></i>
                                <span class="text-sm text-gray-700">Team Collaboration</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-indigo-500 text-sm mr-3"></i>
                                <span class="text-sm text-gray-700">Custom URL Shortener</span>
                            </div>
                        </div>

                        
                        <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-100">
                            <div class="text-center">
                                <div class="mb-2">
                                    <span class="text-sm text-gray-600">Monthly subscription</span>
                                </div>
                                <div class="flex items-center justify-center gap-2 mb-1">
                                    <span class="text-2xl font-bold text-gray-900">₹999</span>
                                    <span class="text-gray-500">/month</span>
                                </div>
                                <p class="text-xs text-gray-500">Billed monthly, cancel anytime</p>
                            </div>
                        </div>

                        
                        <button onclick="processPayment()"
                                id="paymentButton"
                                class="w-full py-3.5 bg-gradient-to-r from-[#4C6FFF] to-[#8B5CF6] 
                                       text-white font-bold rounded-lg hover:shadow-lg hover:shadow-indigo-200 
                                       transition-all text-sm mb-3">
                            <i class="fas fa-lock mr-2 text-xs"></i>Subscribe at ₹999/month
                        </button>

                       
                        <p class="text-center text-xs text-gray-500">
                            <i class="fas fa-shield-alt mr-1 text-xs"></i> Secure SSL encrypted payment
                        </p>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
      
        setTimeout(() => {
            const closeBtn = document.getElementById('closeModalBtn');
            if (closeBtn) {
                closeBtn.addEventListener('click', hidePlanModal);
                console.log('Close button event listener added');
            }
            
            
            const modal = document.getElementById('planModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        hidePlanModal();
                    }
                });
            }
        }, 100);
    }
    
   
    const modal = document.getElementById('planModal');
    console.log('Modal element:', modal);
    
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        console.log('Modal should be visible now');
        
        
        modal.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hidePlanModal();
            }
        });
        
       
        modal.setAttribute('tabindex', '-1');
        modal.focus();
    } else {
        console.error('Modal not found!');
    }
}

function hidePlanModal() {
    console.log('hidePlanModal called');
    const modal = document.getElementById('planModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        console.log('Modal hidden');
    }
}


document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('planModal');
        if (modal && !modal.classList.contains('hidden')) {
            hidePlanModal();
        }
    }
});


async function processPayment() {
    const button = document.getElementById('paymentButton');
    if (!button) return;
    
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
    button.disabled = true;

    try {
       
        const response = await fetch('/subscription/create-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                plan_id: 'pro',
                amount: 99900, 
                billing_period: 'monthly'
            })
        });

        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message);
        }

       
        const options = {
            key: '{{ config("services.razorpay.key_id") }}',
            amount: data.amount,
            currency: 'INR',
            name: '{{ config("app.name") }}',
            description: 'Pro Plan - Monthly Subscription',
            order_id: data.order_id,
            handler: async function(response) {
                await verifyPayment(response);
            },
            prefill: {
                name: '{{ auth()->user()->name ?? "" }}',
                email: '{{ auth()->user()->email ?? "" }}',
                contact: '{{ auth()->user()->phone ?? "" }}'
            },
            theme: {
                color: '#4C6FFF'
            },
            modal: {
                ondismiss: function() {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            }
        };

        const razorpay = new Razorpay(options);
        razorpay.open();

        hidePlanModal();

    } catch (error) {
        alert('Error: ' + error.message);
        button.innerHTML = originalText;
        button.disabled = false;
    }
}


async function verifyPayment(response) {
    try {
        const verifyResponse = await fetch('/subscription/verify-payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_order_id: response.razorpay_order_id,
                razorpay_signature: response.razorpay_signature
            })
        });

        const data = await verifyResponse.json();

        if (data.success) {
           
            showSuccessMessage();
        } else {
            alert('Payment verification failed. Please contact support.');
        }

    } catch (error) {
        alert('Error verifying payment: ' + error.message);
    }
}


function showSuccessMessage() {
    const successHTML = `
        <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-[10000] p-4">
            <div class="bg-white rounded-2xl max-w-sm w-full p-8 text-center animate-fadeIn">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Payment Successful!</h3>
                <p class="text-gray-600 mb-6">Your Pro plan has been activated successfully.</p>
                <button onclick="window.location.reload()"
                        class="w-full py-3 bg-[#4C6FFF] text-white font-semibold rounded-xl hover:bg-[#3A5BD9]">
                    Continue
                </button>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', successHTML);
}

// Add CSS animations
if (!document.getElementById('modal-styles')) {
    const style = document.createElement('style');
    style.id = 'modal-styles';
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.2s ease-out;
        }
        
        /* Prevent body scroll when modal is open */
        body.modal-open {
            overflow: hidden;
        }
        
        /* Modal responsive sizing */
        @media (max-width: 640px) {
            #planModal > div {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }
        }
    `;
    document.head.appendChild(style);
}
</script>