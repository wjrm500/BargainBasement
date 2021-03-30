<div class="container mt-5 text-center">
    <p>Your email address is <strong><?= $email ?></strong> and your country is <strong><?= $country ?></strong>.</p>
    <?php if ($orders): ?>
        <p>You have done a total of <strong><?= count($orders) ?></strong> shops with us. See below for details...</p>
        <div class="row m-4">
            <div class="col-12 col-sm-6 p-2">
                <table id="orders-table" class="table table-striped text-center mb-0" style="table-layout: fixed;">
                    <th>Datetime</th>
                    <th>Num items</th>
                    <th>Price paid</th>
                    <?php foreach ($orders as $order): ?>
                        <tr data-products="<?= htmlspecialchars(json_encode(array_map(fn($product) => ['name' => str_replace(' ', '_', $product->name()), 'quantity' => $product->quantity], $order->getItems())), ENT_QUOTES, 'UTF-8') ?>">
                            <td>
                                <?= $order->payment()->payment_made_at ?>
                            </td>
                            <td>
                                <?= $order->getNumItems() ?>
                            </td>
                            <td>
                                <?= '£' . (string) number_format($order->getOverallPrice(), 2, '.', '') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="col-12 col-sm-6 border border-primary rounded p-2">
                <div id="order-products">
                    <div class="d-flex flex-column justify-content-center h-100 p-5">
                        <h2 class="text-secondary">Click on an order to see which products it contained</h2>
                    </div>
                </div>
            </div>
        <div class="row">
    <?php endif; ?>
</div>