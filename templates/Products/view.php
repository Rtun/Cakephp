<div class="row">
    <h1 class="my-4">Detalles del Producto "<?= $product->name?>"</h1>

    <!-- Información del Producto -->
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="h4 mb-0">Información General</h2>
        </div>
        <div class="card-body">
            <table class="table table-bordered mb-0">
                <tbody>
                    <tr>
                        <th scope="row" class="w-25">Nombre</th>
                        <td><?= h($product->name) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Precio</th>
                        <td>$<?= number_format(h($product->price), 2) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Cantidad en Stock</th>
                        <td><?= h($product->stock_quantity) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Estado</th>
                        <td>
                            <span class="badge <?= $product->status === 'activo' ? 'bg-success' : 'bg-danger' ?>">
                                <?= h($product->status) ?>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Historial de Movimientos de Stock -->
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="h4 mb-0">Historial de Movimientos de Stock</h2>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo de Movimiento</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($product->stock_movements as $movement): ?>
                        <tr>
                            <td><?= h($movement->created->format('d-m-Y H:i')) ?></td>
                            <td><?= h($movement->reason) ?></td>
                            <td><?= h($movement->quantity_changed) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Enlaces de Acción -->
    <div class="d-flex justify-content-between">
        <button class="btn btn-primary" type="button" onclick="window.location.href='/editar/producto/' + <?= $product->id; ?>">Editar</button>
        <button class="btn btn-warning" type="button" onclick="window.location.href='/'">Regresar al listado</button>

    </div>
</div>
