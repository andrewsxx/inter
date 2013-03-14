<?php

class Contabilidad_Table extends Zend_Db_Table
{
    public function __construct ($name, $rowClass)
    {
        if (!$rowClass) $rowClass = "Zend_Db_Table_Row";
        parent::__construct(array("name" => $name , "rowClass" => $rowClass , "sequence" => true));
    }

    public function findById ($id)
    {
        $ret = $this->fetchRow("id" . " = " . $id);
        return $ret;
    }
}