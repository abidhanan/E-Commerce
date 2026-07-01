<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UsersController\UserController;
use App\Http\Controllers\ProductController\DashboardController;
use App\Http\Controllers\ProductController\ProductController;
use App\Http\Controllers\ProductController\CategoryController;
use App\Http\Controllers\ProductController\CollectionsController;
use App\Http\Controllers\ProductController\TemperatureController;
use App\Http\Controllers\ProductController\IntensitiesController;
use App\Http\Controllers\ProductController\InsulationController;
use App\Http\Controllers\ProductController\BreathabilitiesController;
use App\Http\Controllers\ProductController\SizeGuideController;
use App\Http\Controllers\ProductController\MaterialsController;
use App\Http\Controllers\BlogController\BlogController;
use App\Http\Controllers\BlogController\CategoryController as BlogCategoryController;
use App\Http\Controllers\BlogController\TagController;
use App\Http\Controllers\MainController\LandingpageController;
use App\Http\Controllers\MainController\PaymentTesterController;
use App\Http\Controllers\MainController\AccountController;
use App\Http\Controllers\MainController\WishlistController;
use App\Http\Controllers\MainController\CartController;
use App\Http\Controllers\MainController\OrderHistoryController;
use App\Http\Controllers\Admin\OrderComplaintController as AdminOrderComplaintController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\ErrorLogController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PerformanceController;
use App\Http\Controllers\Admin\RoleAccessController;
use App\Http\Controllers\LandingpageController\DisplayController;
use App\Http\Controllers\LandingpageController\BestsellersController;
use App\Http\Controllers\LandingpageController\FAQController;
use App\Http\Controllers\LandingpageController\AboutUsController;
use App\Http\Controllers\LandingpageController\CustomCollectionsDisplayController;
use App\Http\Controllers\LandingpageController\SocialLinkController;
use App\Http\Controllers\LandingpageController\StepController;
use App\Http\Controllers\LandingpageController\CareGuideController;
use App\Http\Controllers\LandingpageController\DisplayLoginController;
use App\Http\Controllers\LandingpageController\CrashReplacementController;
use App\Http\Controllers\LandingpageController\ConsentDocumentController;
use App\Http\Controllers\MainController\PostController;
use App\Http\Controllers\MainController\CategoriesController;
use App\Http\Controllers\MainController\CollectionsController as MainCollectionsController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingpageController::class, 'index'])->name('home');
Route::get('/shop', [LandingpageController::class, 'shop'])->name('shop.index');
Route::get('/product/{product}', [LandingpageController::class, 'product'])->name('product.show');
Route::get('/about', [LandingpageController::class, 'about'])->name('about');
Route::get('/faq', [LandingpageController::class, 'faq'])->name('faq');
Route::get('/care-guide', [LandingpageController::class, 'careGuide'])->name('care-guide');
Route::get('/explore', [PostController::class, 'index'])->name('explore.index');
Route::get('/return-policy', [LandingpageController::class, 'returnPolicy'])->name('return-policy');
Route::get('/how-to-buy', [LandingpageController::class, 'howToBuy'])->name('how-to-buy');
Route::get('/crash-replacement', [LandingpageController::class, 'crashReplacement'])->name('crash-replacement');
Route::get('/legal/{slug}', [ConsentDocumentController::class, 'showPublic'])->name('legal.show');
Route::get('/category/{category}', [CategoriesController::class, 'index'])->name('category.show');
Route::get('/collection/{collection}', [MainCollectionsController::class, 'show'])->name('collection.show');
Route::get('/post',[PostController::class,'index'])->name('post');
Route::get('/post/{slug}',[PostController::class,'show'])->name('post.show');   
Route::get('/search', [LandingpageController::class, 'search'])->name('search.index');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
Route::get('/password/reset', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('throttle:5,1');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.update')->middleware('throttle:5,1');

/*
|--------------------------------------------------------------------------
| EMAIL VERIFICATION (TIDAK WAJIB LOGIN)
|--------------------------------------------------------------------------
*/

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware('signed')
    ->name('verification.verify');

Route::post('/payment/callback', [PaymentController::class, 'callback'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class])
    ->name('payment.callback');
Route::get('/payment/return', [PaymentController::class, 'returnView'])->name('payment.return');

Route::middleware('auth')->group(function () {

   Route::get('/email/verify', [AuthController::class, 'showVerify'])
    ->middleware('auth')
    ->name('verification.notice');

    Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])
        ->middleware(['auth', 'throttle:6,1'])
        ->name('verification.send');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::get('/wishlist/status', [WishlistController::class, 'status'])->name('wishlist.status');
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/checkout', [CheckoutController::class, 'review'])->name('checkout.index');
    Route::post('/checkout/order', [CheckoutController::class, 'placeOrder'])->name('checkout.order');
    Route::get('/checkout/{variantId}', [CheckoutController::class, 'checkout'])->name('checkout.show');
    Route::get('/orders', [OrderHistoryController::class, 'index'])->name('user.orders.index');
    Route::post('/orders/{orderCode}/complete', [OrderHistoryController::class, 'complete'])->name('user.orders.complete');
    Route::post('/orders/{orderCode}/review', [OrderHistoryController::class, 'storeReview'])->name('user.orders.review');
    Route::post('/orders/{orderCode}/complaints', [OrderHistoryController::class, 'storeComplaint'])->name('user.orders.complaints.store');
    Route::get('/orders/{orderCode}', [OrderHistoryController::class, 'show'])->name('user.orders.show');
    Route::get('/payments/{orderCode}/status', [PaymentController::class, 'status'])->name('payments.status');
    Route::post('/payments/{orderCode}/retry', [PaymentController::class, 'retry'])->name('payments.retry');
});

/*
|--------------------------------------------------------------------------
| PROTECTED AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:superadmin|admin|editor|finance|staff')
        ->name('dashboard');
    Route::get('/payment-tester', [PaymentTesterController::class, 'index'])->name('payments.tester');

    Route::prefix('address')->controller(AccountController::class)->group(function () {
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::put('/{id}', 'updateAddress');
        Route::delete('/{id}', 'destroy');
        Route::post('/{id}/primary', 'setPrimary');
    });

    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::put('/account', [AccountController::class, 'update'])->name('account.update');

    Route::post('/account/verify-password', [App\Http\Controllers\MainController\AccountController::class, 'verifyPassword'])->name('account.verify-password');

    Route::middleware(['role:superadmin|admin|editor|finance|staff', 'admin.activity'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::middleware('role:superadmin')->group(function () {
                Route::get('role-access', [RoleAccessController::class, 'index'])->name('role-access.index');
                Route::put('role-access', [RoleAccessController::class, 'update'])->name('role-access.update');
                Route::get('performance', [PerformanceController::class, 'index'])->name('performance.index');
                Route::get('performance/{staff}', [PerformanceController::class, 'show'])->name('performance.show');
            });

            Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');

            Route::middleware('role:superadmin|admin')
                ->prefix('error-logs')
                ->name('error-logs.')
                ->group(function () {
                    Route::get('/', [ErrorLogController::class, 'index'])->name('index');
                    Route::get('/download', [ErrorLogController::class, 'download'])->name('download');
                });

            Route::middleware('role:superadmin|admin|finance')
                ->prefix('finance')
                ->name('finance.')
                ->group(function () {
                    Route::get('/', [FinanceController::class, 'index'])->name('index');
                    Route::get('orders', [FinanceController::class, 'orders'])->name('orders');
                    Route::get('export', [FinanceController::class, 'export'])->name('export');
                });

            Route::middleware('permission:manage displays')->group(function () {
                Route::resource('displays', DisplayController::class);
                Route::get('bestsellers/load', [BestsellersController::class, 'load'])
                    ->name('bestsellers.load');
                Route::post('bestsellers/update-all', [BestsellersController::class, 'updateAll'])
                    ->name('bestsellers.updateAll');
                Route::get('bestsellers', [BestsellersController::class, 'index'])
                    ->name('bestsellers.index');

                Route::get('custom-collections-display', [CustomCollectionsDisplayController::class, 'index'])
                    ->name('custom-collections-display.index');
                Route::get('custom-collections-display/{collection}/load', [CustomCollectionsDisplayController::class, 'load'])
                    ->name('custom-collections-display.load');
                Route::post('custom-collections-display/{collection}/update-all', [CustomCollectionsDisplayController::class, 'updateAll'])
                    ->name('custom-collections-display.updateAll');
                Route::get('custom-collections-display/{collection}/choose', [CustomCollectionsDisplayController::class, 'choose'])
                    ->name('custom-collections-display.choose');
                Route::resource('social-links', SocialLinkController::class)
                    ->parameters(['social-links' => 'socialLink'])
                    ->except('show');
                Route::patch('faqs/{faq}/toggle-status', [FAQController::class, 'toggleStatus'])->name('faqs.toggle-status');
                Route::post('faqs/reorder', [FAQController::class, 'reorder'])->name('faqs.reorder');
                Route::resource('faqs', FAQController::class);
                Route::patch('crash-replacements/{crashReplacement}/toggle-status', [CrashReplacementController::class, 'toggleStatus'])->name('crash-replacements.toggle-status');
                Route::post('crash-replacements/reorder', [CrashReplacementController::class, 'reorder'])->name('crash-replacements.reorder');
                Route::resource('crash-replacements', CrashReplacementController::class);

                Route::patch('display-logins/{displayLogin}/toggle-status', [DisplayLoginController::class, 'toggleStatus'])->name('display-logins.toggle-status');
                Route::post('display-logins/reorder', [DisplayLoginController::class, 'reorder'])->name('display-logins.reorder');
                Route::resource('display-logins', DisplayLoginController::class);
                Route::resource('consent-documents', ConsentDocumentController::class)
                    ->parameters(['consent-documents' => 'consentDocument'])
                    ->except('show');
                Route::resource('aboutus', AboutUsController::class);
                Route::post('aboutus/upload', [AboutUsController::class, 'upload'])
                    ->name('aboutus.upload');
                Route::get('return-steps', [StepController::class, 'returnindex'])->name('return-steps');
                Route::get('return-steps/create', [StepController::class, 'returncreate'])->name('return-steps.create');
                Route::post('return-steps', [StepController::class, 'returnstore'])->name('return-steps.store');
                Route::get('return-steps/{step}/edit', [StepController::class, 'returnedit'])->name('return-steps.edit');
                Route::put('return-steps/{step}', [StepController::class, 'returnupdate'])->name('return-steps.update');
                Route::delete('return-steps/{step}', [StepController::class, 'returndestroy'])->name('return-steps.destroy');

                Route::get('how-to-buy-steps', [StepController::class, 'howToBuyIndex'])->name('how-to-buy-steps');
                Route::get('how-to-buy-steps/create', [StepController::class, 'howToBuyCreate'])->name('how-to-buy-steps.create');
                Route::patch('how-to-buy-steps/{step}/toggle-status', [StepController::class, 'howToBuyToggleStatus'])->name('how-to-buy.toggle-status');
                Route::post('how-to-buy-steps', [StepController::class, 'howToBuyStore'])->name('how-to-buy-steps.store');
                Route::get('how-to-buy-steps/{step}/edit', [StepController::class, 'howToBuyEdit'])->name('how-to-buy-steps.edit');
                Route::put('how-to-buy-steps/{step}', [StepController::class, 'howToBuyUpdate'])->name('how-to-buy-steps.update');
                Route::delete('how-to-buy-steps/{step}', [StepController::class, 'howToBuyDestroy'])->name('how-to-buy-steps.destroy');
                Route::get('care-guides', [CareGuideController::class, 'index'])->name('care-guides.index');
                Route::get('care-guides/create', [CareGuideController::class, 'create'])->name('care-guides.create');
                Route::post('care-guides/reorder', [CareGuideController::class, 'reorder'])->name('care-guides.reorder');
                Route::patch('care-guides/{guide}/toggle-status', [CareGuideController::class, 'toggleStatus'])->name('care-guides.toggle-status');
                Route::post('care-guides', [CareGuideController::class, 'store'])->name('care-guides.store');
                Route::get('care-guides/{guide}/edit', [CareGuideController::class, 'edit'])->name('care-guides.edit');
                Route::put('care-guides/{guide}', [CareGuideController::class, 'update'])->name('care-guides.update');
                Route::delete('care-guides/{guide}', [CareGuideController::class, 'destroy'])->name('care-guides.destroy');

            });

            Route::middleware('permission:manage users')->group(function () {
                Route::get('/users', [UserController::class, 'index'])->name('users.index');
                Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
                Route::post('/users', [UserController::class, 'store'])->name('users.store');
                Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
                Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
                Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
                Route::get('/users/loglogin', [UserController::class, 'loglogin'])->name('users.loglogin');
            });

            Route::middleware('permission:manage orders')->group(function () {
                Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
                Route::get('/order-complaints', [AdminOrderComplaintController::class, 'index'])->name('order-complaints.index');
                Route::get('/order-complaints/{complaint}', [AdminOrderComplaintController::class, 'show'])->name('order-complaints.show');
                Route::patch('/order-complaints/{complaint}', [AdminOrderComplaintController::class, 'update'])->name('order-complaints.update');
                Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
                Route::put('/orders/{order}/quote', [AdminOrderController::class, 'quote'])->name('orders.quote');
                Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
            });

            Route::middleware('permission:manage products')->group(function () {
                Route::resource('products', ProductController::class);
                Route::delete('products/image/{id}', [ProductController::class,'deleteImage'])
                    ->name('products.image.delete');
                Route::post('products/image/{id}/primary', [ProductController::class,'setPrimary'])
                    ->name('products.image.primary');
                Route::post('products/image/{id}/hover', [ProductController::class,'setHover'])
                    ->name('products.image.hover');
            });

            Route::resource('categories', CategoryController::class)
                ->middleware('permission:manage categories');

            Route::resource('collections', CollectionsController::class)
                ->middleware('permission:manage collections');

            Route::middleware('permission:manage blogs')->group(function () {
                Route::resource('blog-categories', BlogCategoryController::class);
                Route::resource('tags', TagController::class);
                Route::resource('blogs', BlogController::class);
                Route::get('blogs/{blog}/publish', [BlogController::class, 'relaseblog'])->name('blogs.publish');
            });

            Route::middleware('permission:manage product attributes')->group(function () {
                Route::resource('temperatures', TemperatureController::class);
                Route::resource('intensities', IntensitiesController::class);
                Route::resource('insulations', InsulationController::class);
                Route::resource('breathabilities', BreathabilitiesController::class);
                Route::resource('materials', MaterialsController::class);
            });

            Route::resource('size-guides', SizeGuideController::class)
                ->middleware('permission:manage size guides');
        });

    Route::middleware('role:admin')->get('/admin-area', fn() => "Halaman Admin");
    Route::middleware('role:editor')->get('/produk', fn() => "Halaman Produk");
    Route::middleware('role:finance')->get('/laporan', fn() => "Halaman Laporan");
    Route::middleware('role:staff')->get('/order', fn() => "Halaman Order");
    Route::middleware('role:user')->get('/profile', fn() => "Halaman Profile");

    Route::put('/account/reviews/{id}', [\App\Http\Controllers\MainController\UserReviewController::class, 'update'])->name('user.reviews.update');
    Route::delete('/account/reviews/{id}', [\App\Http\Controllers\MainController\UserReviewController::class, 'destroy'])->name('user.reviews.destroy');
});
