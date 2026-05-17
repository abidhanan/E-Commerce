<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consent_documents', function (Blueprint $table) {
            $table->id();
            $table->string('type', 80);
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        DB::table('consent_documents')->insert([
            [
                'type' => 'terms_privacy',
                'slug' => 'terms-privacy',
                'title' => 'Terms, Conditions & Privacy Policy',
                'summary' => 'Ketentuan penggunaan layanan, pengelolaan akun, dan kebijakan privasi pelanggan.',
                'content' => "Terms and Conditions\n\nDengan membuat akun, pengguna setuju untuk memberikan informasi yang benar, menjaga keamanan akun, dan menggunakan layanan sesuai aturan yang berlaku.\n\nPrivacy Policy\n\nData pribadi seperti nama, email, alamat, dan riwayat transaksi digunakan untuk memproses pesanan, mengelola akun, meningkatkan layanan, dan mengirim komunikasi terkait akun. Kami menjaga data pelanggan sesuai kebutuhan operasional dan tidak menjual data pribadi kepada pihak ketiga.",
                'is_active' => true,
                'position' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'newsletter',
                'slug' => 'newsletter-offers',
                'title' => 'Newsletter & Exclusive Offers',
                'summary' => 'Informasi tentang email promosi, update produk, dan penawaran khusus.',
                'content' => "Newsletter and Offers\n\nDengan berlangganan newsletter, pengguna dapat menerima update produk, campaign, promosi, rekomendasi, dan penawaran eksklusif melalui email atau channel komunikasi lain yang tersedia.\n\nPengguna dapat berhenti berlangganan dari komunikasi marketing jika fitur unsubscribe tersedia atau dengan menghubungi customer support.",
                'is_active' => true,
                'position' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('consent_documents');
    }
};
