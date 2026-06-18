<?php
require_once "ConnexionBd.php";

class ProfesseurModel {
    private ?PDO $pdo;
    
    // Propriétés
    private ?string $idProfesseur;
    private ?string $nomProfesseur;
    private ?string $prenomProfesseur;
    private ?string $gradeProfesseur;
    
    public function __construct() {
        $this->pdo = (new ConnexionBd())->getConnexion();
    }
    
    // Getters et Setters
    public function getidProfesseur(): ?string {
        return $this->idProfesseur;
    }
    
  /*  public function setidProfesseur(string $idProfesseur): void {
        $this->idProfesseur = $idProfesseur;
    }*/
    
    public function getnomProfesseur(): ?string {
        return $this->nomProfesseur;
    }
    
    public function setnomProfesseur(string $nomProfesseur): void {
        $this->nomProfesseur = $nomProfesseur;
    }
    
    public function getprenomProfesseur(): ?string {
        return $this->prenomProfesseur;
    }
    
    public function setprenomProfesseur(string $prenomProfesseur): void {
        $this->prenomProfesseur = $prenomProfesseur;
    }
    
    public function getgradeProfesseur(): ?string {
        return $this->gradeProfesseur;
    }
    
    public function setgradeProfesseur(string $gradeProfesseur): void {
        $this->gradeProfesseur = $gradeProfesseur;
    }
    
    // Méthodes CRUD
    public function create(array $data): bool {
        $sql = "INSERT INTO Professeur (idProfesseur, nomProfesseur, prenomProfesseur, gradeProfesseur) 
                VALUES (:id, :nom, :prenom, :grade)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'    => $data['idProfesseur'],
            ':nom'   => $data['nomProfesseur'],
            ':prenom'=> $data['prenomProfesseur'],
            ':grade' => $data['gradeProfesseur']
        ]);
    }
    
    public function read(string $id): ?array {
        $sql = "SELECT * FROM Professeur WHERE idProfesseur = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $prof = $stmt->fetch(PDO::FETCH_ASSOC);
        return $prof ?: null;
    }
    
    public function readAll(): array {
        $sql = "SELECT * FROM Professeur";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function update(string $id, array $data): bool {
        $sql = "UPDATE Professeur 
                SET nomProfesseur = :nom, prenomProfesseur = :prenom, gradeProfesseur = :grade 
                WHERE idProfesseur = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nom'    => $data['nomProfesseur'],
            ':prenom' => $data['prenomProfesseur'],
            ':grade'  => $data['gradeProfesseur'],
            ':id'     => $id
        ]);
    }
    
    public function delete(string $id): bool {
        $sql = "DELETE FROM Professeur WHERE idProfesseur = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function search(string $keyword): array {
        $sql = "SELECT * FROM Professeur WHERE nomProfesseur LIKE :keyword 
                OR prenomProfesseur LIKE :keyword";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':keyword' => "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
