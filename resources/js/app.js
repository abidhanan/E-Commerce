import './bootstrap';
// Di dalam resources/js/app.js

window.showLoginModal = function(e) {
    if(e) e.preventDefault(); 
    const modal = document.getElementById('auth-modal');
    const content = document.getElementById('auth-modal-content');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }, 10);
};

window.hideLoginModal = function() {
    const modal = document.getElementById('auth-modal');
    const content = document.getElementById('auth-modal-content');
    
    content.classList.remove('scale-100');
    content.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 150);
};

document.addEventListener('DOMContentLoaded', function() {
    const sellerForm = document.getElementById('seller-registration-form');
    
    if(sellerForm) {
        sellerForm.addEventListener('submit', async function(e) {
            e.preventDefault(); // Mencegah form memuat ulang halaman
            
            const submitBtn = document.getElementById('submit-btn');
            const spinner = document.getElementById('submit-spinner');
            const btnText = submitBtn.querySelector('span');
            
            // 1. Ubah UI ke status Loading
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
            btnText.innerText = 'Processing...';
            spinner.classList.remove('hidden');

            // 2. Kumpulkan data dari form
            const formData = new FormData(sellerForm);
            // Konversi FormData ke JSON (standar API modern)
            const payload = Object.fromEntries(formData.entries());

            try {
                // 3. Tembak ke API Backend temanmu (GANTI URL INI NANTI)
                const response = await fetch('/api/v1/sellers/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Buka komentar ini jika backend butuh CSRF
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Terjadi kesalahan pada server.');
                }

                // 4. Tangani Sukses
                alert('Registrasi berhasil dikirim! Tim kami akan menghubungi Anda.');
                window.location.href = '/'; // Arahkan kembali ke halaman utama

            } catch (error) {
                // 5. Tangani Error
                console.error('Error:', error);
                alert('Gagal: ' + error.message);
                
                // Kembalikan UI ke semula
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                btnText.innerText = 'Submit Application';
                spinner.classList.add('hidden');
            }
        });
    }
});