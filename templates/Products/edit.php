<div class="row">
    <h1 class="my-4">Editar Producto <?= $product->name?></h1>

    <!-- Formulario para editar el producto -->
    <?= $this->Form->create($product, ['class' => 'needs-validation', 'id' => 'edit-product-form', 'novalidate' => true]) ?>
    <fieldset>
        <div class="mb-3">
            <?= $this->Form->control('name', [
                'label' => 'Nombre del Producto',
                'class' => 'form-control',
                'required' => true,
            ]) ?>
        </div>
        <div class="mb-3">
            <?= $this->Form->control('price', [
                'label' => 'Precio',
                'type' => 'number',
                'step' => '0.01',
                'class' => 'form-control',
                'required' => true,
            ]) ?>
        </div>
        <div class="mb-3">
            <?= $this->Form->control('stock_quantity', [
                'label' => 'Cantidad en Stock',
                'type' => 'number',
                'class' => 'form-control',
                'required' => true,
            ]) ?>
        </div>
    </fieldset>

    <!-- Botones de acción -->
    <div class="mt-4">
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <button class="btn btn-warning" type="button" onclick="window.location.href='/'">Cancelar</button>
    </div>
    <?= $this->Form->end() ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('edit-product-form');

        form.addEventListener('submit', async function (event) {
            event.preventDefault(); 

            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                    },
                });

                const result = await response.json();

                if (response.ok) {
                    Swal.fire({
                        title:'Actualizado',
                        text: 'Las modificaciones al formulario se guardaron exitosamente.',
                        icon: 'success'
                    })
                } else {
                    Swal.fire({
                        title:'Hubo un error',
                        text: response.message,
                        icon: 'error'
                    })
                }
            } catch (error) {
                Swal.fire({
                        title:'Hubo un error',
                        text: error,
                        icon: 'error'
                    })
                console.error(error);
            }
        });
    });
</script>
