<?php
class VO_Product extends Zend_Db_Table_Row {
    private $_pictures = null;
    
    public function getPictures(){
        if(!$this->_pictures){
            $this->_pictures = Proxy_Picture::getInstance()->fetchByProductId($this->id);
        }
        return $this->_pictures;
    }
    
    public function getPicturesUrl(){
        $pics = array();
        $pictures = $this->getPictures();
        foreach($pictures as $picture){
            $pics[] = $picture->url;
        }
        return $pics;
    }
    
    public function delete() {
        $pictures = $this->getPictures();
        foreach ($pictures as $picture){
            $picture->delete();
        }
        return parent::delete();
    }
}
?>
