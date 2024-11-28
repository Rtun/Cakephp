<div class="row">
    <h1 class="mb-4">Lista de Productos</h1>

    <!-- Filtros -->
    <?= $this->Form->create(null, ['type' => 'get', 'class' => 'mb-4']) ?>
    <fieldset class="border p-4">
        <legend class="w-auto px-2"><?= __('Filtrar Productos') ?></legend>
        <div class="row">
            <div class="col-md-4">
                <?= $this->Form->control('status', [
                    'type' => 'select',
                    'options' => ['activo' => 'Activo', 'inactivo' => 'Inactivo'],
                    'empty' => 'Seleccionar estado',
                    'label' => 'Estado',
                    'default' => $query['status'] ?? null,
                    'class' => 'form-select'
                ]) ?>
            </div>
            <div class="col-md-4">
                <?= $this->Form->control('min_price', [
                    'label' => 'Precio Mínimo',
                    'value' => $query['min_price'] ?? null,
                    'class' => 'form-control'
                ]) ?>
            </div>
            <div class="col-md-4">
                <?= $this->Form->control('max_price', [
                    'label' => 'Precio Máximo',
                    'value' => $query['max_price'] ?? null,
                    'class' => 'form-control'
                ]) ?>
            </div>
        </div>
    </fieldset>
    <div class="mt-3">
        <?= $this->Form->button(__('Filtrar'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?= $this->Form->end() ?>

    <!-- Botón para agregar productos -->
    <div class="d-flex justify-content-end my-3">
    <button class="btn btn-success" type="button" onclick="window.location.href='/agregar/producto/'">Agregar Producto</button>
    </div>

    <!-- Tabla de productos -->
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th><?= __('Nombre') ?></th>
                <th><?= __('Precio') ?></th>
                <th><?= __('Cantidad en Inventario') ?></th>
                <th><?= __('Estado') ?></th>
                <th><?= __('Acciones') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= h($product->name) ?></td>
                <td><?= $this->Number->format($product->price) ?></td>
                <td><?= $this->Number->format($product->stock_quantity) ?></td>
                <td><?= h($product->status) ?></td>
                <td>
                <button class="btn btn-info" type="button" onclick="window.location.href='/detalles/producto/' + <?= $product->id; ?>">Ver</button>
                    <?php if ($product->status === 'activo'): ?>
                        <button class="btn btn-primary" type="button" onclick="window.location.href='/editar/producto/' + <?= $product->id; ?>">Editar</button>
                        <button class="btn btn-warning" type="button" onclick="window.location.href='/agregar/movimiento_stock/' + <?= $product->id; ?>">Mover Stock</button>
                        <button class="btn btn-danger change-status-btn" type="button" data-product-id="<?= $product->id ?>" data-product-name="<?= $product->name ?>">Eliminar</button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="d-flex justify-content-between align-items-center my-4">
        <!-- Contador de resultados -->
        <div class="text-muted">
            <?= $this->Paginator->counter(__('Página {{page}} de {{pages}}, mostrando {{current}} producto(s) de {{count}} total')) ?>
        </div>

        <!-- Controles de paginación -->
        <nav aria-label="Paginación">
            <ul class="pagination pagination-sm mb-0">
                <?= $this->Paginator->first('<< ' . __('Primero'), ['class' => 'page-item', 'linkClass' => 'page-link']) ?>
                <?= $this->Paginator->prev('< ' . __('Anterior'), ['class' => 'page-item', 'linkClass' => 'page-link']) ?>
                <?= $this->Paginator->numbers(['class' => 'page-item', 'linkClass' => 'page-link']) ?>
                <?= $this->Paginator->next(__('Siguiente') . ' >', ['class' => 'page-item', 'linkClass' => 'page-link']) ?>
                <?= $this->Paginator->last(__('Último') . ' >>', ['class' => 'page-item', 'linkClass' => 'page-link']) ?>
            </ul>
        </nav>
    </div>

</div>

<div class="modal fade" id="confirmStatusChangeModal" tabindex="-1" aria-labelledby="confirmStatusChangeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmStatusChangeModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este producto?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmChangeStatusBtn">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
   document.addEventListener('DOMContentLoaded', () => {
    const changeStatusBtns = document.querySelectorAll('.change-status-btn');
    const confirmStatusChangeModal = new bootstrap.Modal(document.getElementById('confirmStatusChangeModal'));
    const confirmChangeStatusBtn = document.getElementById('confirmChangeStatusBtn');
    let productIdToChange = null;

    changeStatusBtns.forEach(btn => {
        btn.addEventListener('click', function (event) {
            event.preventDefault();

            productIdToChange = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const modalTitle = document.querySelector('#confirmStatusChangeModalLabel');

            modalTitle.textContent = `Confirmar Para Eliminar el Producto ${productName}`;
            confirmChangeStatusBtn.setAttribute('data-product-id', productIdToChange);
            confirmStatusChangeModal.show();
        });
    });

    confirmChangeStatusBtn.addEventListener('click', function () {
        const productId = this.getAttribute('data-product-id');
        if (productId) {
            fetch(`/products/delete/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '<?= h($this->request->getAttribute('csrfToken')) ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Eliminado',
                        text: 'El Producto se ha eliminado correctamente',
                        icon: 'success'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un error al eliminar el producto',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error Interno',
                    text: error.message,
                    icon: 'error'
                });
            });
        }

        confirmStatusChangeModal.hide();
    });
});

</script>
