<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\I18n\FrozenDate;

class ProductsController extends AppController
{
    public function index()
    {
        // Obtener parámetros de filtro desde la URL
        $query = $this->request->getQuery();
        $conditions = [];
    
        // Filtro por status (activo/inactivo)
        if (!empty($query['status'])) {
            $conditions['Products.status'] = $query['status'];
        }
        else {
            $conditions['Products.status ='] = 'Activo';
        }
    
        // Filtro por rango de precio
        if (!empty($query['min_price'])) {
            $conditions['Products.price >='] = (float)$query['min_price'];
        }
    
        if (!empty($query['max_price'])) {
            $conditions['Products.price <='] = (float)$query['max_price'];
        }
    
        // Configurar paginación con filtros
        $this->paginate = [
            'fields' => ['id', 'name', 'price', 'stock_quantity', 'status'],
            'conditions' => $conditions,
            'limit' => 10, 
            'order' => ['Products.id' => 'desc'], 
        ];
    
        // Obtener los productos paginados
        $products = $this->paginate($this->Products);
    
        // Pasar datos a la vista
        $this->set(compact('products', 'query')); 
    }
    
    public function view($id = null)
    {
        // Obtener el producto por ID
        $product = $this->Products->get($id, [
            'fields' => ['id', 'name', 'price', 'stock_quantity', 'status'],
            'contain' => ['StockMovements'], 
        ]);
    
        if (!$product) {
            $this->Flash->error('Producto no encontrado');
            return $this->redirect(['action' => 'index']);
        }
    
        // Pasar los datos a la vista
        $this->set(compact('product'));
    }
    
    

    public function add()
    {
        $product = $this->Products->newEmptyEntity();

        if ($this->request->is('post')) {
            $product = $this->Products->patchEntity($product, $this->request->getData());
            if($this->Products->save($product)) {
                return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['success' => true]));
            }
            return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['success' => false, 'message' => 'No se pudo agregar el producto']));
        }

        $this->set(compact('product'));
    }

    public function edit($id = null)
    {
        $product = $this->Products->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $product = $this->Products->patchEntity($product, $this->request->getData());
            if ($this->Products->save($product)) {
                return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['success' => true]));
            }
            return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['success' => false, 'message' => 'No se pudo actualizar el producto']));
        }

        $this->set(compact('product'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']); 

        $product = $this->Products->get($id); 

        
        $product->status = 'inactivo';

        if ($this->Products->save($product)) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['success' => true]));
        } 
        
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['success' => false, 'message' => 'No se pudo eliminar el producto']));
    }
    
   

public function stockMovement($productId)
{
    // Obtener el producto por su ID
    $product = $this->Products->get($productId);

    // Crear una nueva entidad para el movimiento de stock
    $stockMovement = $this->Products->StockMovements->newEmptyEntity();

    if ($this->request->is('post')) {
        // Obtener los datos del formulario
        $data = $this->request->getData();
        $stockMovement = $this->Products->StockMovements->patchEntity($stockMovement, $data);
        $stockMovement->product_id = $productId; 

        // Verificar si el movimiento se guardó correctamente
        if ($this->Products->StockMovements->save($stockMovement)) {
            // Actualizar el stock del producto
            if ($data['movement_type'] === 'add') {
                $product->stock_quantity += $data['quantity_changed'];
            } elseif ($data['movement_type'] === 'remove') {
                $product->stock_quantity -= $data['quantity_changed'];
            }
            $this->Products->save($product);

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['success' => true]));
        } else {
            return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['success' => false, 'message' => 'No se pudo registrar el movimiento']));
        }
    }

    // Pasar los datos a la vista
    $this->set(compact('product', 'stockMovement'));
}

public function stockDetails() {

    $producto = $this->Products->find()
    ->select(['id', 'name', 'stock_quantity'])
    ->order(['stock_quantity' => 'DESC'])
    ->first();


    $lastMonth = (new FrozenDate())->subMonth(1);

    $movimiento = $this->Products->StockMovements->find()
        ->select([
            'Products.id',
            'Products.name',
            'total_sold' => 'SUM(StockMovements.quantity)'
        ])
        ->contain(['Products'])
        ->where([
            'StockMovements.movement_type' => 'venta',
            'StockMovements.movement_date >=' => $lastMonth
        ])
        ->group(['Products.id', 'Products.name'])
        ->order(['total_sold' => 'DESC']);

        dd($producto, $movimiento);


}


}
