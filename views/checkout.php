<div id="checkout" class="px-5 mt-2" data-shopping-cart-exists="<?= $shoppingCartExists ?>">
    <h1>Checkout</h1>
    <div id="carts-not-equal" class="d-none">
        <div id="carts-not-equal-notice">
            We've noticed that the shopping cart you've just created is different to the one we have saved in our database. This can occur when you create a shopping cart as a non-signed in user and then sign in to an account that previously created a shopping cart that was never checked out. Please select which one you want to checkout below - the other will be deleted.
        </div>
        <div class="row">
            <div id="local-shopping-cart" class="col-6"></div>
            <div id="db-shopping-cart" class="col-6"></div>
        </div>
    </div>
</div>