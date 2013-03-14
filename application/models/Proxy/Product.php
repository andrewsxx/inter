<?php
class Proxy_Product extends Contabilidad_Proxy
{
    protected static $_instance = null;

    /**
     * @return Public Proxy_Primitives
     */
    public static function getInstance ()
    {
        if (null === self::$_instance) {
            self::$_instance = new self('product', 'VO_Product');
        }
        return (self::$_instance);
    }
    
    public function createNew($params){
        $row = $this->createRow();
        $row->name = $params['name'];
        $row->id_category = $params['id_category'];
        $row->description = $params['description'];
        $row->created_date = time();
        $row->id_product = isset($params['id_product']) ? $params['id_product'] : null;
        if (isset($params['picture'])){
            foreach($params['picture'] as $url){
                //create pictures
            }
        }
        $row->save();
        return $row;
    }
    
    public function edit($params){
        $row = $this->findById($params['id']);
        $row->name = $params['name'];
        $row->description = strlen($params['description']) ? $params['description'] : null;
        $row->edited_date = time();
        $row->save();
        return $row;
    }
    
    public function retrieveAll (){
        return $this->getTable()->fetchAll();
    }

    public function editAccount ($account, $params){
        
    }
    

    public function findById ($id){
        return $this->getTable()->fetchRow("id = '$id'");
    }

    /*
     * Create URL from VO_Account
     * 
     * @return string
     * @params VO_Account
     */
    public static function getUrl_ ($product){
        $url = BASE_URL . "/producto-" . $product->id . "-" . $product->name;
        return $url;
    }
    
    public function serializer ($product){
        return $serialized = array('id' => $product->id, 
                                   'name' => $product->name, 
                                   'pictures_url' => $product->getPicturesUrl(), 
                                   'description' => $product->description,
                                   'productUrl' => Proxy_Account::getUrl_($product));
    }
}
?>
