<?php
class Proxy_Category extends Contabilidad_Proxy
{
    
    protected static $_instance = null;

    /**
     * @return Public Proxy_Primitives
     */
    public static function getInstance ()
    {
        if (null === self::$_instance) {
            self::$_instance = new self('category', 'VO_Category');
        }
        return (self::$_instance);
    }
    
    public function createNew($params){
        $row = $this->createRow();
        $row->name = $params['name'];
        $row->details = strlen($params['details']) ? $params['details'] : null;
        $row->created_date = time();
        $row->save();
        return $row;
    }
    
    public function edit($params){
        $row = $this->findById($params['id']);
        $row->name = $params['name'];
        $row->details = strlen($params['details']) ? $params['details'] : null;
        $row->edited_date = time();
        $row->save();
        return $row;
    }
    
    public function retrieveAll ($order = "id asc"){
        return $this->getTable()->fetchAll(null, $order);
    }
    
    public function findById($id){
        return $this->getTable()->fetchRow("id = '$id'");
    }
}
?>
