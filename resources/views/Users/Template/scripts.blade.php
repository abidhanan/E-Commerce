<script>
    document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            const header = document.querySelector('header');
            const shopSidebar = document.getElementById('shopSidebar');
            const shopOverlay = document.getElementById('shopOverlay');
            const cartSidebar = document.getElementById('cartSidebar');
            const cartOverlay = document.getElementById('cartOverlay');
            const searchBox = document.getElementById('searchBox');
            const searchInput = document.getElementById('searchInput');
            const searchDropdown = document.getElementById('searchDropdown');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

            const setBodyLock = () => {
                body.classList.toggle(
                    'body-lock',
                    shopSidebar?.classList.contains('open') || cartSidebar?.classList.contains('open')
                );
            };

            const openPanel = (panel, overlay, toggle) => {
                panel?.classList.add('open');
                overlay?.classList.add('open');
                panel?.setAttribute('aria-hidden', 'false');
                toggle?.setAttribute('aria-expanded', 'true');
                setBodyLock();
            };

            const closePanel = (panel, overlay, toggle) => {
                panel?.classList.remove('open');
                overlay?.classList.remove('open');
                panel?.setAttribute('aria-hidden', 'true');
                toggle?.setAttribute('aria-expanded', 'false');
                setBodyLock();
            };

            const shopToggle = document.getElementById('shopToggle');
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const shopClose = document.getElementById('shopClose');
            const openShop = (event) => {
                event?.preventDefault();
                openPanel(shopSidebar, shopOverlay, event?.currentTarget ?? shopToggle);
            };

            window.closeShopSidebar = () => closePanel(shopSidebar, shopOverlay, shopToggle);
            shopToggle?.addEventListener('click', openShop);
            mobileMenuToggle?.addEventListener('click', openShop);
            shopClose?.addEventListener('click', window.closeShopSidebar);
            shopOverlay?.addEventListener('click', window.closeShopSidebar);

            const cartToggle = document.getElementById('cartToggle');
            const cartClose = document.getElementById('cartClose');
            const cartContentArea = document.getElementById('cartContentArea');
            const cartItemCount = document.getElementById('cartItemCount');
            const cartCount = document.getElementById('cartCount');
            const closeCart = () => closePanel(cartSidebar, cartOverlay, cartToggle);
            cartToggle?.addEventListener('click', (event) => {
                event.preventDefault();
                openPanel(cartSidebar, cartOverlay, cartToggle);
                loadCart?.();
            });
            cartClose?.addEventListener('click', closeCart);
            cartOverlay?.addEventListener('click', closeCart);

            const searchToggle = document.getElementById('searchToggle');
            const searchClose = document.getElementById('searchClose');
            const closeSearch = () => {
                searchBox?.classList.remove('active');
                searchDropdown?.classList.remove('active');
                searchToggle?.setAttribute('aria-expanded', 'false');
            };

            searchToggle?.addEventListener('click', (event) => {
                event.preventDefault();
                searchBox?.classList.toggle('active');
                searchToggle.setAttribute('aria-expanded', searchBox?.classList.contains('active') ?
                    'true' : 'false');
                if (searchBox?.classList.contains('active')) {
                    setTimeout(() => searchInput?.focus(), 80);
                }
            });

            searchClose?.addEventListener('click', closeSearch);
            document.addEventListener('click', (event) => {
                if (searchBox && !event.target.closest('.search-container')) {
                    closeSearch();
                }
            });

            const searchUrl = @json(route('search.index'));
            const escapeHtml = (value = '') => String(value)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
            const buildSearchUrl = (keyword, ajax = false) => {
                const url = new URL(searchUrl, window.location.origin);

                if (keyword) {
                    url.searchParams.set('q', keyword);
                }

                if (ajax) {
                    url.searchParams.set('ajax', '1');
                }

                return url.toString();
            };
            let searchTimer = null;
            let searchRequest = null;

            searchInput?.addEventListener('input', () => {
                const keyword = searchInput.value.trim();

                window.clearTimeout(searchTimer);

                if (!keyword) {
                    searchRequest?.abort();
                    searchDropdown?.classList.remove('active');
                    if (searchDropdown) searchDropdown.innerHTML = '';
                    return;
                }

                if (!searchDropdown) return;

                searchDropdown.innerHTML = '<div class="search-no-results">Searching...</div>';
                searchDropdown.classList.add('active');

                searchTimer = window.setTimeout(async () => {
                    searchRequest?.abort();
                    searchRequest = new AbortController();

                    try {
                        const response = await fetch(buildSearchUrl(keyword, true), {
                            headers: {
                                'Accept': 'application/json',
                            },
                            signal: searchRequest.signal,
                        });

                        if (!response.ok) {
                            searchDropdown.innerHTML =
                                '<div class="search-no-results">Search is unavailable</div>';
                            return;
                        }

                        const payload = await response.json();
                        const products = payload.products || [];
                        searchDropdown.innerHTML = products.length ?
                            products.map((product) => `
                            <a class="search-result-item" href="${product.url}">
                                <img class="search-result-image" src="${product.image}" alt="">
                                <span class="search-result-info">
                                    <span class="search-result-name">${escapeHtml(product.name)}</span>
                                    <span class="search-result-variant">${escapeHtml(product.variant || 'Available now')}</span>
                                </span>
                                <span class="search-result-price">${escapeHtml(product.price)}</span>
                            </a>
                        `).join('') + `<div class="search-footer"><a class="search-see-all" href="${buildSearchUrl(keyword)}">See all</a></div>` :
                            '<div class="search-no-results">No products found</div>';
                    } catch (error) {
                        if (error.name !== 'AbortError') {
                            searchDropdown.innerHTML =
                                '<div class="search-no-results">Search is unavailable</div>';
                        }
                    }
                }, 250);
            });

            searchInput?.addEventListener('keydown', (event) => {
                if (event.key !== 'Enter') {
                    return;
                }

                const keyword = searchInput.value.trim();
                if (keyword) {
                    event.preventDefault();
                    window.location.href = buildSearchUrl(keyword);
                }
            });

            const cartIndexUrl = @json(route('cart.index'));
            const cartStoreUrl = @json(route('cart.store'));
            const cartUpdateUrl = @json(route('cart.update', ['cartItem' => '__CART_ITEM__']));
            const cartDestroyUrl = @json(route('cart.destroy', ['cartItem' => '__CART_ITEM__']));
            const setCartCount = (count) => {
                const normalizedCount = Number(count || 0);

                if (cartCount) {
                    cartCount.textContent = normalizedCount;
                }

                if (cartItemCount) {
                    cartItemCount.textContent = normalizedCount;
                }
            };
            const parseCartPayload = async (response) => {
                const contentType = response.headers.get('content-type') || '';
                return contentType.includes('application/json') ? response.json().catch(() => ({})) : {};
            };
            const renderCart = (payload = {}) => {
                setCartCount(payload.count);

                if (!cartContentArea) {
                    return;
                }

                const items = payload.items || [];

                if (!items.length) {
                    cartContentArea.innerHTML = `
                    <div class="cart-empty">
                        <div class="cart-empty-title">Your cart is empty</div>
                        <div class="cart-empty-text">Start with a piece from the latest collection.</div>
                    </div>
                `;
                    return;
                }

                cartContentArea.innerHTML = `
                <div class="cart-content">
                    ${items.map((item) => `
                        <div class="cart-item">
                            <a href="${item.url}">
                                <img class="cart-item-image" src="${item.image}" alt="">
                            </a>
                            <div class="cart-item-details">
                                <div class="cart-item-name">${escapeHtml(item.product_name)}</div>
                                <div class="cart-item-variant">Size ${escapeHtml(item.variant_name || '-')}</div>
                                <div class="cart-item-price">${escapeHtml(item.line_total_formatted)}</div>
                                <div class="cart-item-actions">
                                    <div class="cart-quantity">
                                        <button type="button" data-cart-action="decrease" data-cart-id="${item.id}" data-cart-qty="${item.qty}">−</button>
                                        <span>${item.qty}</span>
                                        <button type="button" data-cart-action="increase" data-cart-id="${item.id}" data-cart-qty="${item.qty}">+</button>
                                    </div>
                                    <button type="button" class="cart-item-remove" data-cart-action="remove" data-cart-id="${item.id}">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
                <div class="cart-footer">
                    <div class="cart-subtotal">
                        <span>Subtotal</span>
                        <strong>${escapeHtml(payload.subtotal_formatted || 'Rp 0')}</strong>
                    </div>
                    <div class="cart-payment-methods">
                        <span>Ongkir dan total akhir dikonfirmasi admin setelah pesan.</span>
                    </div>
                    <a href="${payload.checkout_url}" class="cart-checkout">Checkout</a>
                </div>
            `;
            };
            const loadCart = async () => {
                @guest
                setCartCount(0);
                return;
            @endguest

            try {
                const response = await fetch(cartIndexUrl, {
                    headers: {
                        'Accept': 'application/json',
                    },
                });

                if (response.status === 401) {
                    setCartCount(0);
                    return;
                }

                if (!response.ok) {
                    return;
                }

                renderCart(await parseCartPayload(response));
            } catch (error) {
                console.error('Unable to load cart.', error);
            }
        };
        const sendCartRequest = async (url, options = {}) => {
            const response = await fetch(url, {
                ...options,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    ...(options.headers || {}),
                },
            });
            const payload = await parseCartPayload(response);

            if (response.status === 401) {
                window.location.href = @json(route('login'));
                return null;
            }

            if (!response.ok) {
                window.appNotify?.error(window.appNotify.extractMessage(payload,
                    'Cart belum bisa diperbarui.'));
                return null;
            }

            renderCart(payload);
            return payload;
        };

        @auth loadCart();
    @else
        setCartCount(0);
    @endauth

    document.addEventListener('click', async (event) => {
        const addButton = event.target.closest('.add-cart-btn');

        if (!addButton) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        if (!addButton.dataset.variantId) {
            window.appNotify?.warning('Pilih size dulu sebelum menambahkan ke cart.', 'Size belum dipilih');
            document.dispatchEvent(new CustomEvent('product:size-required'));
            return;
        }

        @guest
        window.location.href = @json(route('login'));
        return;
    @endguest

    addButton.disabled = true;

    try {
        const payload = await sendCartRequest(cartStoreUrl, {
            method: 'POST',
            body: JSON.stringify({
                variant_id: addButton.dataset.variantId,
                qty: 1,
            }),
        });

        if (payload) {
            openPanel(cartSidebar, cartOverlay, cartToggle);
            window.appNotify?.success(payload.message, 'Cart');
        }
    } finally {
        addButton.disabled = false;
    }
    });

    document.addEventListener('click', async (event) => {
        const actionButton = event.target.closest('[data-cart-action][data-cart-id]');

        if (!actionButton) {
            return;
        }

        event.preventDefault();

        const action = actionButton.dataset.cartAction;
        const cartId = actionButton.dataset.cartId;
        const currentQty = Number(actionButton.dataset.cartQty || 1);

        if (action === 'remove') {
            await sendCartRequest(cartDestroyUrl.replace('__CART_ITEM__', cartId), {
                method: 'DELETE',
                body: JSON.stringify({}),
            });
            return;
        }

        const nextQty = action === 'increase' ? currentQty + 1 : Math.max(1, currentQty - 1);
        await sendCartRequest(cartUpdateUrl.replace('__CART_ITEM__', cartId), {
            method: 'PATCH',
            body: JSON.stringify({
                qty: nextQty,
            }),
        });
    });

    const wishlistHeaderCount = document.getElementById('wishlistHeaderCount');
    const wishlistPageCount = document.getElementById('wishlistPageCount');
    const wishlistButtons = () => [...document.querySelectorAll('.wishlist-btn[data-product-id]')];
    const setWishlistCount = (count) => {
        const normalizedCount = Number(count || 0);

        if (wishlistHeaderCount) {
            wishlistHeaderCount.textContent = normalizedCount;
        }

        if (wishlistPageCount) {
            wishlistPageCount.textContent = `${normalizedCount} product${normalizedCount === 1 ? '' : 's'}`;
        }
    };
    const setWishlistButtonState = (productId, wishlisted) => {
        wishlistButtons()
            .filter((button) => String(button.dataset.productId) === String(productId))
            .forEach((button) => {
                button.classList.toggle('active', wishlisted);
                button.setAttribute('aria-label', wishlisted ? 'Remove from wishlist' : 'Add to wishlist');
                button.setAttribute('aria-pressed', wishlisted ? 'true' : 'false');
            });
    };
    const parseJsonPayload = async (response) => {
        const contentType = response.headers.get('content-type') || '';

        if (!contentType.includes('application/json')) {
            return {};
        }

        return response.json().catch(() => ({}));
    };
    const handleWishlistClick = async (event) => {
        const button = event.currentTarget;

        event.preventDefault();
        event.stopPropagation();

        @guest
        window.location.href = @json(route('login'));
        return;
    @endguest

    const productId = button.dataset.productId;
    const toggleUrl = @json(route('wishlist.toggle', ['product' => '__PRODUCT_ID__'])).replace('__PRODUCT_ID__', productId);
    button.disabled = true;

    try {
        const response = await fetch(toggleUrl, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({}),
        });
        const payload = await parseJsonPayload(response);

        if (response.status === 401) {
            window.location.href = @json(route('login'));
            return;
        }

        if (!response.ok) {
            window.appNotify?.error(window.appNotify.extractMessage(payload, 'Wishlist belum bisa diperbarui.'));
            return;
        }

        setWishlistButtonState(productId, payload.wishlisted);
        setWishlistCount(payload.count);

        if (!payload.wishlisted) {
            document.querySelector(`[data-wishlist-card="${productId}"]`)?.remove();
            if (document.getElementById('wishlistGrid') && Number(payload.count || 0) === 0) {
                window.location.reload();
                return;
            }
        }

        window.appNotify?.success(payload.message, 'Wishlist');
    } catch (error) {
        window.appNotify?.error('Wishlist belum bisa diperbarui.');
    } finally {
        button.disabled = false;
    }
    };
    const bindWishlistButtons = () => {
        wishlistButtons().forEach((button) => {
            if (button.dataset.wishlistBound === 'true') {
                return;
            }

            button.dataset.wishlistBound = 'true';
            button.addEventListener('pointerdown', (event) => event.stopPropagation());
            button.addEventListener('mousedown', (event) => event.stopPropagation());
            button.addEventListener('touchstart', (event) => event.stopPropagation(), {
                passive: true
            });
            button.addEventListener('click', handleWishlistClick);
        });
    };

    bindWishlistButtons();

    @auth
    const loadWishlistState = async () => {
        try {
            const response = await fetch(@json(route('wishlist.status')), {
                headers: {
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                return;
            }

            const payload = await parseJsonPayload(response);
            const productIds = new Set((payload.product_ids || []).map(String));

            wishlistButtons().forEach((button) => {
                setWishlistButtonState(button.dataset.productId, productIds.has(String(button.dataset
                    .productId)));
            });
            setWishlistCount(payload.count);
        } catch (error) {
            console.error('Unable to load wishlist state.', error);
        }
    };

    loadWishlistState();
    @else
        setWishlistCount(0);
    @endauth

    const darkModeToggle = document.getElementById('darkModeToggle');
    if (localStorage.getItem('theme') === 'dark') {
        body.classList.add('dark-mode');
    }
    darkModeToggle?.addEventListener('click', () => {
        darkModeToggle.classList.add('spinning');
        body.classList.toggle('dark-mode');
        localStorage.setItem('theme', body.classList.contains('dark-mode') ? 'dark' : 'light');
        setTimeout(() => darkModeToggle.classList.remove('spinning'), 600);
    });

    let lastScrollY = window.scrollY;
    const updateHeader = () => {
        if (!header) return;
        const currentScrollY = window.scrollY;
        header.classList.toggle('at-top', currentScrollY <= 8);
        header.classList.toggle('scrolled', currentScrollY > 8 && currentScrollY < lastScrollY);
        header.classList.toggle('scrolling-down', currentScrollY > 8 && currentScrollY > lastScrollY);
        lastScrollY = Math.max(currentScrollY, 0);
    };
    updateHeader();
    window.addEventListener('scroll', updateHeader, {
        passive: true
    });

    window.switchTab = (button, tab) => {
        const section = button.closest('.pns-categories-section');
        section?.querySelectorAll('.pns-tab').forEach((item) => item.classList.remove('active'));
        button.classList.add('active');
        section?.querySelectorAll('.pns-tab-content').forEach((content) => content.classList.remove('active'));
        section?.querySelector(`#tab-${tab}`)?.classList.add('active');
    };

    document.querySelectorAll('.block-wrapper-outer').forEach((outer) => {
        const scroller = outer.querySelector('.block-wrapper');
        const thumb = outer.querySelector('.pns-scrollbar-thumb');
        const prev = outer.previousElementSibling?.querySelector('.collection-prev');
        const next = outer.previousElementSibling?.querySelector('.collection-next');

        if (!scroller) return;

        const scrollByCard = (direction) => {
            const card = scroller.querySelector('.product-block');
            const amount = card ?
                card.getBoundingClientRect().width + 12 :
                scroller.clientWidth * 0.85;

            scroller.scrollBy({
                left: amount * direction,
                behavior: 'smooth'
            });
        };

        prev?.addEventListener('click', () => scrollByCard(-1));
        next?.addEventListener('click', () => scrollByCard(1));

        const updateThumb = () => {
            if (!thumb) return;

            const maxScroll = scroller.scrollWidth - scroller.clientWidth;
            const trackWidth = thumb.parentElement.clientWidth;

            const thumbWidth = Math.max(
                20,
                trackWidth * (scroller.clientWidth / scroller.scrollWidth)
            );

            thumb.style.width = `${thumbWidth}px`;

            thumb.style.left = `${
            maxScroll <= 0
                ? 0
                : (scroller.scrollLeft / maxScroll) * (trackWidth - thumbWidth)
        }px`;
        };

        // ==========================
        // FIX CLICK + DRAG ISSUE
        // ==========================
        let isDragging = false;
        let moved = false;
        let startX = 0;
        let startScroll = 0;

        scroller.addEventListener('pointerdown', (event) => {
            startX = event.clientX;
            startScroll = scroller.scrollLeft;
            moved = false;
            isDragging = false;
        });

        scroller.addEventListener('pointermove', (event) => {
            const distance = event.clientX - startX;

            // baru dianggap drag kalau geser > 10px
            if (Math.abs(distance) > 10) {
                isDragging = true;
                moved = true;
                scroller.classList.add('grabbing');

                scroller.scrollLeft = startScroll - distance;
            }
        });

        const stopDragging = () => {
            isDragging = false;
            scroller.classList.remove('grabbing');
        };

        scroller.addEventListener('pointerup', stopDragging);
        scroller.addEventListener('pointerleave', stopDragging);
        scroller.addEventListener('pointercancel', stopDragging);

        // prevent link click hanya kalau user benar-benar drag
        scroller.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', (event) => {
                if (moved) {
                    event.preventDefault();
                    moved = false;
                }
            });
        });

        scroller.addEventListener('scroll', updateThumb, {
            passive: true
        });

        window.addEventListener('resize', updateThumb);

        updateThumb();
    });

    window.openNewsletterSignup = () => {
        searchBox?.classList.add('active');
        searchInput?.focus();
    };

    document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;
    window.closeShopSidebar();
    closeCart();
    closeSearch();
    });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        function setupLoadMore(hiddenClass, buttonId) {
            const button = document.getElementById(buttonId);

            if (!button) return;

            button.addEventListener('click', function() {
                const hiddenItems = document.querySelectorAll('.' + hiddenClass);

                let shown = 0;

                hiddenItems.forEach(item => {
                    if (shown < 5) {
                        item.style.display = 'flex';
                        item.classList.remove(hiddenClass);
                        shown++;
                    }
                });

                if (document.querySelectorAll('.' + hiddenClass).length === 0) {
                    button.style.display = 'none';
                }
            });
        }

        setupLoadMore('hidden-category', 'loadMoreCategory');
        setupLoadMore('hidden-collection', 'loadMoreCollection');

    });
</script>
@include('Shared.disable-submit-script')
