<?php
require_once "connexionBd.php"
class EdtModel {
private ?PDO $pdo;

//proprietes
private ?int $idsalle;
private ?string $idprof;
private ?string $idclasse;
private ?string $cours;
private ?datetime $date;

public function __construct(){
    $this->pdo = (new ConnexionBd())->getConnexion();
}
//getters et setters 
public function getIdSalle(): ?int{
    return $this->idsalle;
}
public function getIdProf(): ?string{
    return $this->idprof;
}
public function getIdClasse(): ?string{
    return $this->idclasse;
}
public function getCours(): ?string{
    return $this->cours;
}
public function setCours(string $cours): void {
    $this->cours:$cours;
}
public function getDate(): ?datetime{
    retun this->date;
}
public function setDate(datetime $date): void{
    $this->date:$date;
}
//method crud 
public function create(array $data): bool{
    $sql="INSERT INTO Edt(idsalle, idprof, idclasse, cours, date)
    VALUES(:idsalle,:idprof,:idclasse,:cours,:date)";
    $stmt = this->pdo->prepare($sql);
    return $stmt->execute([
        ':idsalle' =>$data['idsalle'],
        ':idprof' =>$data['idprof'],
        'idclasse' =>$data['idclasse'],
        'cours' =>$data['cours'],
        'date' =>$data['date'] ?? date('Y-m-d h:i:s')
    
        ]);
}
public function read(int id): ?array {
    $sql="SELECT e.*,s.design ,p.nom,c.niveau
     FROM EMPLOI_DU_TEMPS e
            JOIN SALLE s ON s.idsalle=e.idsalle
            JOIN PROFESSEUR p ON p.idprof=e.idprof
            JOIN CLASSE c ON c.idclasse=e.idclasse
            ORDER BY e.date";
            $stmt = this->pdo->prepare($sql);
             $stmt->execute([':date' =>$date]);
             $Edt = $stmt ->fetch(PDO::FETCH_ASSOC);
             return $Edt ?: null;
}
public function 
}

