<div class="row">
    <h1 class="my-4">Modificar Stock para <span id="product-name">"<?= $product->name?>"</span></h1>

    <!-- Formulario para el movimiento de stock -->
    <form id="stock-movement-form" class="needs-validation" novalidate data-product-id="<?= $product->id; ?>">
        <fieldset>
            <!-- Selección del tipo de movimiento (Agregar o Quitar) -->
            <div class="mb-3">
                <label class="form-label">Tipo de Movimiento</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="movement_type" id="movement-add" value="add" checked>
                    <label class="form-check-label" for="movement-add">Agregar</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="movement_type" id="movement-remove" value="remove">
                    <label class="form-check-label" for="movement-remove">Quitar</label>
                </div>
            </div>

            <!-- Cantidad de stock a agregar o quitar -->
            <div class="mb-3">
                <label for="quantity-changed" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="quantity-changed" name="quantity_changed" min="1" required>
                <div class="invalid-feedback">Por favor, ingrese una cantidad válida.</div>
            </div>

            <!-- Motivo del movimiento -->
            <div class="mb-3">
                <label for="reason" class="form-label">Motivo</label>
                <input type="text" class="form-control" id="reason" name="reason" required>
                <div class="invalid-feedback">Por favor, ingrese un motivo.</div>
            </div>
        </fieldset>

        <!-- Botones de acción -->
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Registrar Movimiento</button>
            <button class="btn btn-warning" type="button" onclick="window.location.href='/products'">Regresar al listado</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('stock-movement-form');
        const productId = form.getAttribute('data-product-id'); // Obtener el id del producto desde el atributo data

        // Validaciones en tiempo real
        form.addEventListener('input', function (event) {
            const target = event.target;
            if (target.checkValidity()) {
                target.classList.remove('is-invalid');
                target.classList.add('is-valid');
            } else {
                target.classList.remove('is-valid');
                target.classList.add('is-invalid');
            }
        });

        // Validación al enviar el formulario y solicitud AJAX
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                form.classList.add('was-validated');
            } else {
                event.preventDefault(); // Prevenir el envío tradicional del formulario

                // Obtener los datos del formulario
                const formData = new FormData(form);
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });

                // Agregar el ID del producto a los datos
                data.product_id = productId;

                // Realizar la solicitud AJAX
                fetch(`/agregar/movimiento_stock/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Movimiento Registrado',
                            text: 'El movimiento de stock se ha registrado correctamente.',
                            icon: 'success'
                        })
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un error al registrar el movimiento.',
                            icon: 'error'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error Interno',
                        text: 'Ocurrió un error al procesar la solicitud.',
                        icon: 'error'
                    });
                });
            }
        }, false);
    });
</script>

<!-- Incluye SweetAlert2 si aún no lo has hecho -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
