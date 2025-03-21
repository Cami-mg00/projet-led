<?php
require_once 'managers/AbstractManager.php';

class EventManager extends AbstractManager {

    // Récupérer tous les événements
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM events");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un événement par son ID
    public function findOne($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM events WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $eventData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($eventData) {
            return new Event(
                $eventData['id'],
                $eventData['name'],
                $eventData['date'],
                $eventData['location'],
                $eventData['created_by'],
                $eventData['category']
            );
        }

        return null;
    }

    // Créer un nouvel événement
    public function createEvent($name, $date, $location, $category, $createdBy)
    {
        $stmt = $this->db->prepare("
            INSERT INTO events (name, date, location, category, created_by) 
            VALUES (:name, :date, :location, :category, :created_by)
        ");
        $stmt->execute([
            'name' => $name,
            'date' => $date,
            'location' => $location,
            'category' => $category,
            'created_by' => $createdBy,
        ]);
    }

    // Mettre à jour un événement
    public function updateEvent($id, $name, $date, $location, $category, $createdBy)
    {
        $stmt = $this->db->prepare("
            UPDATE events 
            SET name = :name, date = :date, location = :location, category = :category, created_by = :created_by
            WHERE id = :id
        ");
        $stmt->execute([
            'name' => $name,
            'date' => $date,
            'location' => $location,
            'category' => $category,
            'created_by' => $createdBy,
            'id' => $id,
        ]);
    }

    // Supprimer un événement
    public function deleteEvent($id)
    {
        $stmt = $this->db->prepare("DELETE FROM events WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
