<?php

class Admin_ProductosController extends Zend_Controller_Action
{

    public function indexAction()
    {
        $this->view->products = Proxy_Product::getInstance()->retrieveAll();
    }
    
    public function crearAction()
    {
        $this->view->categories = Proxy_Category::getInstance()->retrieveAll("name asc");
        if($this->getRequest()->isPost()){
            $product = Proxy_Product::getInstance()->createNew($this->getParam("product"));
            $this->redirect("index");
        }
    }
    
    public function editarAction()
    {
        if($this->getRequest()->isPost()){
            $category = Proxy_Product::getInstance()->edit($this->getParam("product"));
            $this->redirect("admin/productos");
        } else {
            $id = $this->getRequest()->getParam("id");
            if($id){
                $this->view->categories = Proxy_Category::getInstance()->retrieveAll("name asc");
                $this->view->product = Proxy_Product::getInstance()->findById($id);
            } else {
                $this->redirect("admin/productos");
            }
        }
    }


}

