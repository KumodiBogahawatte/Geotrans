// Countdown timer functionality
class CountdownTimer {
    constructor(elementId, endDate) {
        this.element = document.getElementById(elementId);
        this.endDate = new Date(endDate).getTime();
        this.init();
    }

    init() {
        if (!this.element) return;

        this.update();
        this.interval = setInterval(() => this.update(), 1000);
    }

    update() {
        const now = new Date().getTime();
        const distance = this.endDate - now;

        if (distance < 0) {
            clearInterval(this.interval);
            this.element.innerHTML = '<span class="text-red-500">EXPIRED</span>';
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        this.element.innerHTML = `
            <div class="flex gap-2 sm:gap-4">
                <div class="text-center">
                    <div class="bg-white text-[#680e68] font-bold text-lg sm:text-2xl px-2 sm:px-3 py-1 sm:py-2 rounded">${this.pad(days)}</div>
                    <div class="text-xs mt-1">DAYS</div>
                </div>
                <div class="text-center">
                    <div class="bg-white text-[#680e68] font-bold text-lg sm:text-2xl px-2 sm:px-3 py-1 sm:py-2 rounded">${this.pad(hours)}</div>
                    <div class="text-xs mt-1">HRS</div>
                </div>
                <div class="text-center">
                    <div class="bg-white text-[#680e68] font-bold text-lg sm:text-2xl px-2 sm:px-3 py-1 sm:py-2 rounded">${this.pad(minutes)}</div>
                    <div class="text-xs mt-1">MINS</div>
                </div>
                <div class="text-center">
                    <div class="bg-white text-[#680e68] font-bold text-lg sm:text-2xl px-2 sm:px-3 py-1 sm:py-2 rounded">${this.pad(seconds)}</div>
                    <div class="text-xs mt-1">SECS</div>
                </div>
            </div>
        `;
    }

    pad(number) {
        return number < 10 ? '0' + number : number;
    }

    destroy() {
        if (this.interval) {
            clearInterval(this.interval);
        }
    }
}

// Initialize countdown timers
document.addEventListener('DOMContentLoaded', () => {
    // Weekly deals countdown (7 days from now)
    const weeklyDealsEnd = new Date();
    weeklyDealsEnd.setDate(weeklyDealsEnd.getDate() + 7);
    
    if (document.getElementById('weekly-deals-countdown')) {
        new CountdownTimer('weekly-deals-countdown', weeklyDealsEnd);
    }

    // Flash sale countdown (24 hours from now)
    const flashSaleEnd = new Date();
    flashSaleEnd.setHours(flashSaleEnd.getHours() + 24);
    
    if (document.getElementById('flash-sale-countdown')) {
        new CountdownTimer('flash-sale-countdown', flashSaleEnd);
    }
});
