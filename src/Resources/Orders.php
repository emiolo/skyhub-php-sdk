<?php

namespace Skyhub\Resources;

class Orders extends \Skyhub\Marketplace {

    private $params,
        $id;

    public function __construct($data) {
        $this->conf = $data->conf;
    }

    /**
     * Definir ID padrão
     * @param type $id
     * @return type
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    public function get($page=1, $per_page=25, $sale_system=null, $statuses=[]) {

        if (isset($this->id) && !is_null($this->id))
            return $this->apiCall('GET', '/orders/'.urlencode($this->id));

        $this->params = [
            'page'=>$page,
            'per_page'=>$per_page,
            'filters'=>[
                'sale_system'=>$sale_system,
                'statuses'=>$statuses
            ]
        ];
        return $this->apiCall('GET', '/orders', $this->params);
    }

    public function next() {
        if (isset($this->params['page'])):
            $this->params['page']++;
        else:
            $this->params['page']=2;
        endif;
        return $this->apiCall('GET', '/orders', $this->params);
    }

    /**
     * Inserir pedido
     * @param type $product
     * @return type
     */
    public function insert($order) {

        if (!is_array($order))
            return ['error'=>'Pedido deve ser um array.'];

        return $this->apiCall('POST', '/orders', ['order'=>$order]);

    }

    /**
     * Aprovar Pedido
     * @param $status
     * @return array|mixed|string
     */
    public function approval($status) {

        if (is_null($this->id))
            return ['error'=>'Informe o ID do pedido'];

        return $this->apiCall('POST', '/orders/'.rawurlencode($this->id).'/approval',['status' => $status]);

    }

    /**
     * Faturar pedido
     * @param type $id
     * @return type
     */
    public function invoice($status) {

        if (is_null($this->id))
            return ['error'=>'Informe o ID do pedido'];

        return $this->apiCall('POST', '/orders/'.rawurlencode($this->id).'/invoice', ['status' => $status]);

    }

    /**
     * Cancelar pedido
     * @return type
     */
    public function cancel($status) {

        if (is_null($this->id))
            return ['error'=>'Informe o ID do pedido'];

        return $this->apiCall('POST', '/orders/'.rawurlencode($this->id).'/cancel', ['status' => $status]);

    }

    /**
     * Confirmar entrega pedido
     * @return type
     */
    public function delivery($status) {

        if (is_null($this->id))
            return ['error'=>'Informe o ID do pedido'];

        return $this->apiCall('POST', '/orders/'.rawurlencode($this->id).'/delivery', ['status' => $status]);

    }

    /**
     * Enviar dados de entrega do pedido
     * @return type
     */
    public function shipments($status) {

        if (is_null($this->id))
            return ['error'=>'Informe o ID do pedido'];

        return $this->apiCall('POST', '/orders/'.rawurlencode($this->id).'/shipments', ['status' => $status]);

    }

    /**
     * Obter etiqueta de frete do pedido
     * @return type
     */
    public function shipment_labels() {

        if (is_null($this->id))
            return ['error'=>'Informe o ID do pedido'];

        return $this->apiCall('GET', '/orders/'.rawurlencode($this->id).'/shipment_labels');

    }

    /**
     * Exceção de transporte
     * @return type
     */
    public function shipment_exception($shipment_exception) {

        if (is_null($this->id))
            return ['error'=>'Informe o ID do pedido'];

        return $this->apiCall('POST', '/orders/'.rawurlencode($this->id).'/shipment_exception', ['shipment_exception' => $shipment_exception]);

    }
}
