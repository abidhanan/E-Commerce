<x-layouts.app>
    <div class="max-w-3xl mx-auto px-6 py-16">
        
        <div class="bg-gray-50 p-8 md:p-12 shadow-sm border border-gray-100">
            <h1 class="text-3xl font-light mb-1 uppercase tracking-wider text-center md:text-left">Seller Registration</h1>
            
            <div class="mb-10 relative">
                <div class="h-1 bg-black w-16 mb-2 transition-all duration-500" id="progress-bar"></div>
                <p class="text-sm text-[#c4a052] font-bold" id="step-title">Identity & Business Profile</p>
            </div>

            <form action="/seller/register" method="POST" id="seller-form">
                @csrf
                
                <div id="step-1" class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-2">Brand Name</label>
                        <input type="text" name="brand_name" class="w-full bg-gray-200 border-none px-4 py-3 focus:ring-2 focus:ring-black transition" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-2">Company Name</label>
                        <input type="text" name="company_name" class="w-full bg-gray-200 border-none px-4 py-3 focus:ring-2 focus:ring-black transition" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-2">Product Category</label>
                        <div class="w-full bg-gray-200 border-none px-4 py-3 flex items-center gap-2 flex-wrap cursor-text">
                            <span class="text-xs font-bold bg-gray-300 px-3 py-1 rounded flex items-center gap-2">Category <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></span>
                            <span class="text-xs bg-gray-300 px-3 py-1 rounded flex items-center gap-1">Accessories <button type="button" class="hover:text-red-500 focus:outline-none">&times;</button></span>
                            <span class="text-xs bg-gray-300 px-3 py-1 rounded flex items-center gap-1">Beauty <button type="button" class="hover:text-red-500 focus:outline-none">&times;</button></span>
                        </div>
                        <p class="text-[10px] text-gray-500 mt-1">*Multiple choice</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-2">Short Description</label>
                        <textarea name="description" rows="4" class="w-full bg-gray-200 border-none px-4 py-3 focus:ring-2 focus:ring-black transition" required></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-2">Social Media & Website</label>
                        <input type="text" name="social_media" class="w-full bg-gray-200 border-none px-4 py-3 focus:ring-2 focus:ring-black transition">
                    </div>

                    <button type="button" onclick="goToStep2()" class="w-full bg-black text-white font-bold tracking-widest uppercase py-4 mt-4 hover:bg-gray-800 transition">
                        Next
                    </button>
                </div>

                <div id="step-2" class="space-y-6 hidden">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-2">Warehouse Address/ Pickup Point</label>
                        <textarea name="warehouse_address" rows="3" class="w-full bg-gray-200 border-none px-4 py-3 focus:ring-2 focus:ring-black transition" required></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-2">Shipping Methods</label>
                        <input type="text" name="shipping_methods" class="w-full bg-gray-200 border-none px-4 py-3 focus:ring-2 focus:ring-black transition" required>
                    </div>

                    <div class="pt-6">
                        <h2 class="text-lg font-light mb-1 uppercase tracking-wider">Person In Charge (PIC)</h2>
                        <div class="h-1 bg-black w-8 mb-6"></div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-2">Name</label>
                        <input type="text" name="pic_name" class="w-full bg-gray-200 border-none px-4 py-3 focus:ring-2 focus:ring-black transition" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-2">Telephone</label>
                        <input type="tel" name="pic_phone" class="w-full bg-gray-200 border-none px-4 py-3 focus:ring-2 focus:ring-black transition" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-2">Email</label>
                        <input type="email" name="pic_email" class="w-full bg-gray-200 border-none px-4 py-3 focus:ring-2 focus:ring-black transition" required>
                    </div>

                    <div class="flex gap-4 mt-8">
                        <button type="button" onclick="goToStep1()" class="w-1/3 border-2 border-black text-black font-bold tracking-widest uppercase py-4 hover:bg-gray-100 transition">
                            Back
                        </button>
                        <button type="submit" class="w-2/3 bg-black text-white font-bold tracking-widest uppercase py-4 hover:bg-gray-800 transition shadow-lg">
                            Submit
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        function goToStep2() {
            document.getElementById('step-1').classList.add('hidden');
            document.getElementById('step-2').classList.remove('hidden');
            document.getElementById('progress-bar').style.width = '100%';
            document.getElementById('step-title').innerText = 'Operational & Logistics Data';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function goToStep1() {
            document.getElementById('step-2').classList.add('hidden');
            document.getElementById('step-1').classList.remove('hidden');
            document.getElementById('progress-bar').style.width = '4rem'; 
            document.getElementById('step-title').innerText = 'Identity & Business Profile';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</x-layouts.app>