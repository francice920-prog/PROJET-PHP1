<?php

require_once 'ClasseModel.php';

class ClasseController
{
    private ClasseModel $classeModel;

    public function __construct()
    {
       
        $this->classeModel = new ClasseModel();
    }

    private function checkAuth(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit();
        }
    }
    
    public function list(): void {
        $this->checkAuth();
       
        $classes = $this->classeModel->readAll();
        include "views/classe/list.php";
    }
    
    public function add(): void {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
               

                'idClasse' => $_POST['idClasse'] ?? null, 
                'annee' => $_POST['annee'],
                'parcours' => $_POST['parcours'],
                'groupe' => $_POST['groupe']
            ];
            
            if ($this->classeModel->create($data)) {
                header('Location: index.php?action=classeList&success=1');
                exit();
            } else {
                $error = "Erreur lors de l'ajout de la classe";
            }
        }
        
        include "views/classe/add.php";
    }
    
    public function edit(): void {
        $this->checkAuth();
        
      
        $idclasse = $_GET['id'] ?? 0;
        if (!$idclasse) {
            header('Location: index.php?action=classeList');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'annee' => $_POST['annee'],
                'parcours' => $_POST['parcours'],
                'groupe' => $_POST['groupe']
            ];
            
            if ($this->classeModel->update($idclasse, $data)) {
                header('Location: index.php?action=classeList&success=1');
                exit();
            } else {
                $error = "Erreur lors de la modification";
            }
        }
        
        $classe = $this->classeModel->read($idclasse);
        if (!$classe) {
            header('Location: index.php?action=classeList');
            exit();
        }
        
        include "views/classe/edit.php";
    }
    
    public function detail(): void {
        $this->checkAuth();
        
        $idclasse = $_GET['id'] ?? 0;
        $classe = $this->classeModel->read($idclasse);
        
        if (!$classe) {
            header('Location: index.php?action=classeList');
            exit();
        }
        
        include "views/classe/detail.php";
    }
    
    public function delete(): void {
        $this->checkAuth();
        
     
        $idclasse = $_GET['id'] ?? 0; 
        
        if ($idclasse && $this->classeModel->delete($idclasse)) {
            header('Location: index.php?action=classeList&success=1');
        } else {
            header('Location: index.php?action=classeList&error=1');
        }
        exit();
    }
    
    public function search(): void {
        $this->checkAuth();
        
        $keyword = $_GET['keyword'] ?? '';
        
        $classes = $this->classeModel->search($keyword);
        
        include "views/classe/search.php";
    }
    
    public function import(): void {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel'])) {
            $data = []; 

            if ($this->classeModel->importFromExcel($data)) {
                header('Location: index.php?action=classeList&success=1');
            } else {
                header('Location: index.php?action=classeList&error=1');
            }
            exit();
        }
    }
    
    public function export(): void {
        $this->checkAuth();
        
      
        $classes = $this->classeModel->readAll();
        
        header('Content-Type: application/vnd.ms-excel'); 
        header('Content-Disposition: attachment; filename="classes.xls"'); 
        
        echo "ID\tAnnée\tParcours\tGroupe\n";
        foreach ($classes as $classe) {
            
        
            echo $classe['idClasse'] . "\t" .
                 $classe['annee'] . "\t" .
                 $classe['parcours'] . "\t" .
                 $classe['groupe']  . "\n";
        }
        exit();
    }
}
?>