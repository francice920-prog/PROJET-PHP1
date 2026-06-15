<?php
require_once "ConnexionBd.php";

class ProffesseurModel {
    private ?PDO $pdo;
    
    // Propriétés
    private ?string $idProffesseur;
    private ?string $nomProffesseur;
    private ?string $prenomProffesseur;
    private ?string $gradeProffesseur;
    
    public function __construct() {
        $this->pdo = (new ConnexionBd())->getConnexion();
    }
    
    // Getters et Setters
    public function getidProffesseur(): ?string {
        return $this->idProffesseur;
    }
    
    public function setidProffesseur(string $idProffesseur): void {
        $this->idProffesseur = $idProffesseur;
    }
    
    public function getnomProffesseur(): ?string {
        return $this->nomProffesseur;
    }
    
    public function setnomProffesseur(string $nomProffesseur): void {
        $this->nomProffesseur = $nomProffesseur;
    }
    
    public function getprenomProffesseur(): ?string {
        return $this->prenomProffesseur;
    }
    
    public function setprenomProffesseur(string $prenomProffesseur): void {
        $this->prenomProffesseur = $prenomProffesseur;
    }
    
    public function getgradeProffesseur(): ?string {
        return $this->gradeProffesseur;
    }
    
    public function setgradeProffesseur(string $gradeProffesseur): void {
        $this->gradeProffesseur = $gradeProffesseur;
    }
    
    // Méthodes CRUD
    public function create(array $data): bool {
        $sql = "INSERT INTO Proffesseur (idProffesseur, nomProffesseur, prenomProffesseur, gradeProffesseur) 
                VALUES (:id, :nom, :prenom, :grade)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'    => $data['idProffesseur'],
            ':nom'   => $data['nomProffesseur'],
            ':prenom'=> $data['prenomProffesseur'],
            ':grade' => $data['gradeProffesseur']
        ]);
    }
    
    public function read(string $id): ?array {
        $sql = "SELECT * FROM Proffesseur WHERE idProffesseur = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $prof = $stmt->fetch(PDO::FETCH_ASSOC);
        return $prof ?: null;
    }
    
    public function readAll(): array {
        $sql = "SELECT * FROM Proffesseur";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function update(string $id, array $data): bool {
        $sql = "UPDATE Proffesseur 
                SET nomProffesseur = :nom, prenomProffesseur = :prenom, gradeProffesseur = :grade 
                WHERE idProffesseur = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nom'    => $data['nomProffesseur'],
            ':prenom' => $data['prenomProffesseur'],
            ':grade'  => $data['gradeProffesseur'],
            ':id'     => $id
        ]);
    }
    
    public function delete(string $id): bool {
        $sql = "DELETE FROM Proffesseur WHERE idProffesseur = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function search(string $keyword): array {
        $sql = "SELECT * FROM Proffesseur WHERE nomProffesseur LIKE :keyword 
                OR prenomProffesseur LIKE :keyword";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':keyword' => "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>