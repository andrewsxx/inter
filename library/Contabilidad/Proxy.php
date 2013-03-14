<?php

class Contabilidad_Proxy
{
    protected $_table = null;

    protected function __construct ($name, $rowClass)
    {
        $this->_table = new Contabilidad_Table($name, $rowClass);
    }

    protected function createRow ()
    {
        return $this->_table->createRow();
    }

//    public function findById ($id)
//    {
//        if (isset($id))
//            return $this->_table->findById($id);
//        else
//            return null;
//    }
    
    public function fetchAll()
    {
        return $this->_table->fetchAll();
    }

   public function getTable ()
    {
        return $this->_table;
    }
}