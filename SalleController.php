<?php
require_once "../models/SalleModel.php";

class SalleController {
    private SalleModel $salleModel;
    
    public function __construct() {
        $this->salleModel = new SalleModel();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    private function checkAuth(): void {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit();
        }
    }
    
    public function list(): void {
        $this->checkAuth();
        $salles = $this->salleModel->readAll();
        include "views/salle/list.php";
    }
    
    public function add(array $data): void {
        $this->checkAuth(); 
        
        if ($this->salleModel->create($data)) {
            header('Location: index.php?action=salleList&success=add');
        } else {
            header('Location: index.php?action=salleList&error=add');
        }
        exit();
    }
    
    public function edit(): void {
        $this->checkAuth();
        
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;
        if (!$id) {
            header('Location: index.php?action=salleList');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'design' => htmlspecialchars(trim($_POST['design'] ?? '')),
                'occupation' => htmlspecialchars(trim($_POST['occupation'] ?? 'Libre'))
            ];
            
            if ($this->salleModel->update($id, $data)) {
                header('Location: index.php?action=salleList&success=edit');
                exit();
            } else {
                $error = "Erreur lors de la modification";
            }
        }
        
        $salle = $this->salleModel->read($id);
        if (!$salle) {
            header('Location: index.php?action=salleList');
            exit();
        }
        
        include "views/salle/edit.php";
    }
    
    public function detail(): void {
        $this->checkAuth();
        
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? 0;
        $salle = $this->salleModel->read($id);
        
        if (!$salle) {
            header('Location: index.php?action=salleList');
            exit();
        }
        
        include "views/salle/detail.php";
    }
    
    public function delete($id): void {
        $this->checkAuth(); 
        
        if ($id && $this->salleModel->delete($id)) {
            header('Location: index.php?action=salleList&success=delete');
        } else {
            header('Location: index.php?action=salleList&error=delete');
        }
        exit();
    }
    
    
    public function search(): void {
        $this->checkAuth();
        
        $datetime = htmlspecialchars($_GET['datetime'] ?? ''); 
        $sallesLibres = [];
        
        if (!empty($datetime)) {
            $sallesLibres = $this->salleModel->searchSallesLibres($datetime);
        }
        
        include "views/salle/search_libre.php";
    }
    
    public function import(): void {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel'])) {
            $data = []; 
            
            if ($this->salleModel->importFromExcel($data)) {
                header('Location: index.php?action=salleList&success=import');
            } else {
                header('Location: index.php?action=salleList&error=import');
            }
            exit();
        }
    }
    
    public function export(): void {
        $this->checkAuth();
        
        $salles = $this->salleModel->exportToExcel() ?: $this->salleModel->readAll(); 
        
        if (ob_get_length()) ob_end_clean();
        
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="salles.xls"');
        
        echo "ID\tDesignation\tOccupation\n";
        foreach ($salles as $salle) {
            $design = str_replace(["\r", "\n", "\t"], ' ', $salle['design']);
            $occupation = str_replace(["\r", "\n", "\t"], ' ', $salle['occupation']);
            
            echo $salle['idsalle'] . "\t" . $design . "\t" . $occupation . "\n";
        }
        exit();
    }
}
?>
