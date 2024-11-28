<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= $this->request->getAttribute('csrfToken'); ?>">
    <title>Añadir Producto</title>
    <style>
        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
            border-color: red;
        }
        .success {
            color: green;
            font-size: 1em;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="row">
        <div class="col-md-12">
        <h1>Añadir Producto</h1>
    <form id="productForm">
        <fieldset>
            
            <div class="form-group">
                <!-- Nombre del Producto -->
                <label for="name">Nombre del Producto</label>
                <input class="form-control" type="text" id="name" name="name">
                <div id="nameError" class="error"></div>
            </div>
            
            <div class="form-group">
                <!-- Nombre del Producto -->
                <label for="name">Descripción del Producto</label>
                <input class="form-control" type="text" id="description" name="description">
                <div id="nameError" class="error"></div>
            </div>
            
            <div class="form-group">
                <!-- Precio -->
                <label for="price">Precio</label>
                <input class="form-control" type="number" id="price" name="price" min="0.01" step="0.01">
                <div id="priceError" class="error"></div>
            </div>
            
            <div class="form-group">
                <!-- Cantidad en Stock -->
                <label for="stock_quantity">Cantidad en Stock</label>
                <input class="form-control" type="number" id="stock_quantity" name="stock_quantity" min="0" step="1">
                <div id="stockQuantityError" class="error"></div>
            </div>
        </fieldset>
        
        <button class="btn btn-success" type="submit">Guardar</button>
        <button class="btn btn-warning" type="button" onclick="window.location.href='/products'">Regresar al listado</button>
        <div id="formSuccess" class="success"></div>
    </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('productForm');
        const nameInput = document.getElementById('name');
        const descriptionInput = document.getElementById('description');
        const priceInput = document.getElementById('price');
        const stockInput = document.getElementById('stock_quantity');
        const nameError = document.getElementById('nameError');
        const priceError = document.getElementById('priceError');
        const stockError = document.getElementById('stockQuantityError');
        const formSuccess = document.getElementById('formSuccess');
        const cancel = document.getElementById('cancelar');
        priceInput.addEventListener('input', function(e) {
            const value = parseFloat(priceInput.value);
            if (value <= 0 || value >= 10000) {
                priceInput.classList.add('is-invalid');
                priceError.textContent = 'El precio debe ser mayor a 0.';
            } else {
                priceInput.classList.remove('is-invalid');
                priceError.textContent = '';
            }
        });
        stockInput.addEventListener('input', function(e) {
            const value = parseFloat(stockInput.value);
            if (value <= 0 || isNaN(value)) {
                stockInput.classList.add('is-invalid');
                stockError.textContent = 'El stock debe ser un numero entero y mayor a 0';
            } else {
                stockInput.classList.remove('is-invalid');
                stockError.textContent = '';
            }
        })

        // Función de validación
        function validateForm() {
            let isValid = true;

            // Validación del nombre
            if (nameInput.value.trim() === '') {
                nameError.textContent = 'El nombre del producto es obligatorio.';
                isValid = false;
            } else {
                nameError.textContent = '';
            }

            // Validación del precio
            const price = parseFloat(priceInput.value);
            if (isNaN(price) || price <= 0) {
                priceError.textContent = 'El precio debe ser un número mayor que 0.';
                isValid = false;
            } else {
                priceError.textContent = '';
            }

            // Validación de la cantidad en stock
            const stock = parseInt(stockInput.value);
            if (isNaN(stock) || stock < 0) {
                stockError.textContent = 'La cantidad en stock debe ser un número entero no negativo.';
                isValid = false;
            } else {
                stockError.textContent = '';
            }

            return isValid;
        }

       // Manejo del envío del formulario
       form.addEventListener('submit', async function (e) {
            e.preventDefault(); // Evitar recargar la página

            if (validateForm()) {
                // Datos a enviar
                const productData = {
                    name: nameInput.value,
                    description: descriptionInput.value,
                    price: parseFloat(priceInput.value),
                    stock_quantity: parseInt(stockInput.value)
                };

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                try {
                    // Enviar datos al servidor
                    const response = await fetch('/agregar/producto', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(productData)
                    });

                    if (response.ok) {
                        Swal.fire({
                            title: 'Guardado',
                            text: 'El producto se guardo correctamente',
                            icon: 'success'
                        })
                        form.reset();
                    } else {
                        const errorData = await response.json();
                        Swal.fire({
                            title: 'Error',
                            text: `Error: ${errorData.message || 'No se pudo guardar el producto.'}`,
                            icon: 'error'
                        })
                    }
                } catch (error) {
                    Swal.fire({
                            title: 'Error',
                            text: `Error: ${error.message || 'No se pudo guardar el producto.'}`,
                            icon: 'error'
                        })
                }
            } 
        });
    </script>
</body>
</html>

