<?php
require_once "ConnexionBd.php";

class SalleModel {
    private ?PDO $pdo;
    private ?int $idsalle;
    private ?string $design;
    private ?string $occupation; 
    
    public function __construct() {
        $this->pdo = (new ConnexionBd())->getConnexion();
    }
    

    public function getIdsalle(): ?int {
        return $this->idsalle;
    }
    
    public function getDesign(): ?string {
        return $this->design;
    }
    
    public function setDesign(string $design): void {
        $this->design = $design;
    }
    
    public function getOccupation(): ?string {
        return $this->occupation;
    }
    
    public function setOccupation(string $occupation): void {
        $this->occupation = $occupation;
    }
    
   
    public function create(array $data): bool {
        $sql = "INSERT INTO SALLE (design, occupation) VALUES (:design, :occupation)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':design' => $data['design'],
            ':occupation' => $data['occupation'] ?? 'Libre'
        ]);
    }
    
    public function read(int $id): ?array {
        $sql = "SELECT * FROM SALLE WHERE idsalle = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $salle = $stmt->fetch(PDO::FETCH_ASSOC);
        return $salle ?: null;
    }
    
    public function readAll(): array {
        $sql = "SELECT * FROM SALLE";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function update(int $id, array $data): bool {
        $sql = "UPDATE SALLE SET design = :design, occupation = :occupation WHERE idsalle = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':design' => $data['design'],
            ':occupation' => $data['occupation'],
            ':id' => $id
        ]);
    }
    
    public function delete(int $id): bool {
        $sql = "DELETE FROM SALLE WHERE idsalle = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
   
    public function searchSallesLibres(string $datetime): array {
        $sql = "SELECT * FROM SALLE 
                WHERE occupation = 'Libre' 
                AND idsalle NOT IN (
                    SELECT idsalle 
                    FROM EMPLOI_DU_TEMPS 
                    WHERE date = :datetime
                )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':datetime' => $datetime]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
