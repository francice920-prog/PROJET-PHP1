<?php
require_once "ProfModel.php";

class ProfesseurController {
    private ProfesseurModel $ProfesseurModel;

    public function __construct() {
        $this->ProfesseurModel = new ProfesseurModel();
    }

    public function index(): void {
        if (isset($_GET['keyword']) && !empty(trim($_GET['keyword']))) {
            $professeurs = $this->ProfesseurModel->search(trim($_GET['keyword']));
        } else {
            $professeurs = $this->ProfesseurModel->readAll();
        }
        require_once "views/professeurs/index.php";
    }

    public function show(string $id): void {
        $professeur = $this->ProfesseurModel->read($id);

        if (!$professeur) {
            die("Erreur 404 : Ce professeur n'existe pas.");
        }

        require_once "views/professeurs/show.php";
    }

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nomProfesseur'    => htmlspecialchars(trim($_POST['nomProfesseur'] ?? '')),
                'prenomProfesseur' => htmlspecialchars(trim($_POST['prenomProfesseur'] ?? '')),
                'gradeProfesseur'  => htmlspecialchars(trim($_POST['gradeProfesseur'] ?? ''))
            ];

            if (!empty($data['nomProfesseur']) && !empty($data['prenomProfesseur'])) {
                if ($this->ProfesseurModel->create($data)) {
                    header("Location: index.php?action=list&success=create");
                    exit();
                } else {
                    $error = "Une erreur est survenue lors de l'enregistrement.";
                }
            } else {
                $error = "Le nom et le prénom sont obligatoires.";
            }
        }
        require_once "views/professeurs/create.php";
    }

    public function edit(string $id): void {
        $professeur = $this->ProfesseurModel->read($id);
        if (!$professeur) {
            die("Erreur : Professeur introuvable.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nomProfesseur'    => htmlspecialchars(trim($_POST['nomProfesseur'] ?? '')),
                'prenomProfesseur' => htmlspecialchars(trim($_POST['prenomProfesseur'] ?? '')),
                'gradeProfesseur'  => htmlspecialchars(trim($_POST['gradeProfesseur'] ?? ''))
            ];

            if (!empty($data['nomProfesseur']) && !empty($data['prenomProfesseur'])) {
                if ($this->ProfesseurModel->update($id, $data)) {
                    header("Location: index.php?action=list&success=update");
                    exit();
                } else {
                    $error = "Une erreur est survenue lors de la mise à jour.";
                }
            } else {
                $error = "Le nom et le prénom ne peuvent pas être vides.";
            }
        }
        require_once "views/professeurs/edit.php";
    }

    public function delete(string $id): void {
        if ($this->model->delete($id)) {
            header("Location: index.php?action=list&success=delete");
            exit();
        } else {
            die("Erreur lors de la suppression.");
        }
    }
}
?>
