<?php

class Admin_CategoriasController extends Zend_Controller_Action
{

    public function indexAction()
    {
        $this->view->categories = Proxy_Category::getInstance()->retrieveAll("name asc");
    }
    
    public function crearAction()
    {
        if($this->getRequest()->isPost()){
            $category = Proxy_Category::getInstance()->createNew($this->getAllParams());
            $this->redirect("index");
        }
    }
    
    public function editarAction()
    {
        if($this->getRequest()->isPost()){
            $category = Proxy_Category::getInstance()->edit($this->getAllParams());
            $this->redirect("index");
        } else {
            $id = $this->getRequest()->getParam("id");
            if($id){
                $this->view->category = Proxy_Category::getInstance()->findById($id);
            } else {
                $this->redirect("index");
            }
        }
    }


}

