<div id="checkout" class="px-5 mt-2" data-shopping-cart-exists="<?= $shoppingCartExists ?>">
    <div id="loading" class="d-flex flex-column mt-5">
        <p class="text-center">Loading...</p>
        <img id="loading-spinner" class="align-self-center" src="/images/spinner-cropped.gif" height="50px">
    </div>
    <div id="carts-not-equal" class="d-none">
        <div id="carts-not-equal-notice">
            We've noticed that the shopping cart you've just created is different to the one we have saved in our database. This can occur when you create a shopping cart as a non-signed in user and then sign in to an account on which a previously-created shopping cart was never checked out. Please select which one you want to checkout below - the other will be deleted.
        </div>
        <div class="row">
            <div id="local-cart-option-container" class="cart-option-container col-6">
                <div class="d-flex justify-content-center">
                    <button id="cart-save-local" class="mb-2 btn btn-secondary">
                        Checkout basket from local storage
                    </button>
                </div>
                <div class="shopping-cart"></div>
            </div>
            <div id="db-cart-option-container" class="cart-option-container col-6">
                <div class="d-flex justify-content-center">
                    <button id="cart-save-db" class="mb-2 btn btn-secondary">
                        Checkout basket from database
                    </button>
                </div>
                <div class="shopping-cart"></div>
            </div>
        </div>
    </div>
</div>