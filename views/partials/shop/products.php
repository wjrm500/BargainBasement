<div id="products" data-product-data=<?= $productData ?>>
    <div id="products-loading" class="d-flex flex-column mt-5">
        <p class="text-center">Loading...</p>
        <img id="loading-spinner" class="align-self-center" src="/images/spinner-cropped.gif" height="50px">
    </div>
    <div id="products-grid" class="row d-none">
        <?php
        
        while (count($productWidgets) > 0) {
            $productWidget = array_shift($productWidgets);
            echo '<div class="product-widget-container">' . $productWidget . '</div>';
        }

        ?>
    </div>
</div>