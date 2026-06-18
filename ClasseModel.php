//->
<?php
 
 require_once 'ConnexionBd.php';    
class ClasseModel {
    
    private PDO $pdo;
    private ?string $idclasse;
    private ?string $niveau;
    private ?string $annee;
    private ?string $parcours;
    private ?string $groupe;

public function __construct($idclasse, $annee, $parcours, $groupe) {

    $this->pdo->getConnexion();
    $this->idclasse = $idclasse;
    $this->annee = $annee;
    $this->parcours = $parcours;
    $this->groupe = $groupe;
    $this->niveau = $niveau;
    
    }
//getters et setters
public function getIdClasse(): ?string {
        return $this->idclasse;
    }

    /* public function setIdClasse(int $idclasse): void {
        $this->idclasse = $idclasse;
    }*/
    public function getAnnee(): ?string {
        return $this->annee;
    }   
    public function setAnnee(string $annee): void {
        $this->annee = $annee;
    }
    
    public function getParcours(): ?string {
        return $this->parcours;
    }

    public function setParcours(string $parcours): void {
        $this->parcours = $parcours;
    }

    public function getGroupe(): ?string {
        return $this->groupe;
    }

    public function setGroupe(string $groupe): void {
        $this->groupe = $groupe;
    }

    public function getNiveau(): ?string {
        return $this->niveau;
    }
    
    public function setNiveau(string $niveau): void {
       $niveau = $this->annee . $this->parcours . $this->groupe; 
        $this->niveau = $niveau;
    }
    
    
    // Méthodes CRUD
    public function create(array $data): bool {
        $sql = "INSERT INTO Classe (idClasse, annee, parcours, groupe) 
                VALUES (:idClasse, :annee, :parcours, :groupe)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':idClasse' => $data['idClasse'],
            ':annee' => $data['annee'],
            ':parcours' => $data['parcours'],
            ':groupe' => $data['groupe']
        ]);
    }

    public function read(string $idclasse): ?array {
        $sql = "SELECT * FROM Classe WHERE idClasse = :idclasse";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idclasse' => $idclasse]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function readAll(): array {
        $sql = "SELECT * FROM Classe";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(string $idclasse, array $data): bool {
        $sql = "UPDATE Classe SET annee = :annee, parcours = :parcours, groupe = :groupe WHERE idClasse = :idclasse";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':annee' => $data['annee'],
            ':parcours' => $data['parcours'],
            ':groupe' => $data['groupe'],
            ':idclasse' => $idclasse
        ]);
    }

    public function delete(string $idclasse): bool {
        $sql = "DELETE FROM Classe WHERE idClasse = :idclasse";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':idclasse' => $idclasse]);
    }

    public function search(string $keyword): array {
        $sql = "SELECT * FROM Client WHERE $idclasse LIKE :keyword 
                OR $niveau LIKE :keyword";
        $stm   
        $stmt->execute([':keyword' => "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>
