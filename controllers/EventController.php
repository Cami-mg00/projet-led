<?php

// Vérifie si la session est déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Démarre la session uniquement si elle n'est pas déjà active
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");  // Redirige vers la page de connexion
    exit;
}

require_once __DIR__ . '/../managers/EventManager.php';

class EventController {
    // Affiche la liste des événements
    public function list() {
        $eventManager = new EventManager();
        $events = $eventManager->findAll();
        require __DIR__ . '/../templates/events/list.phtml';
    }

    // Affiche le formulaire de création d'un événement
    public function create() {
        require __DIR__ . '/../templates/events/create.phtml';
    }

    // Gère la création d'un nouvel événement
    public function store() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Vérification des données
            if (empty($_POST['name']) || empty($_POST['date']) || empty($_POST['location']) || empty($_POST['category'])) {
                echo "Tous les champs doivent être remplis.";
                return;
            }

            // Créer l'événement
            $eventManager = new EventManager();
            $eventManager->createEvent(
                $_POST['name'],
                $_POST['date'],
                $_POST['location'],
                $_POST['category'],
                $_POST['created_by'] // Si tu as un utilisateur connecté ou une autre info
            );

            // Rediriger après création
            header("Location: index.php?controller=event&action=list");
            exit;
        }
    }

    // Affiche le formulaire de mise à jour d'un événement
    public function update() {
        if (!isset($_GET['id'])) {
            die("Erreur : aucun ID spécifié.");
        }

        $eventManager = new EventManager();
        $event = $eventManager->findOne((int)$_GET['id']);

        if (!$event) {
            die("Événement non trouvé.");
        }

        // Si le formulaire a été soumis
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Validation des champs
            if (empty($_POST['name']) || empty($_POST['date']) || empty($_POST['location']) || empty($_POST['category'])) {
                die("Tous les champs doivent être remplis.");
            }

            // Mettre à jour l'événement
            $eventManager->updateEvent(
                $event->getId(),
                $_POST['name'],
                $_POST['date'],
                $_POST['location'],
                $_POST['category'],
                $_POST['created_by']
            );

            // Rediriger après mise à jour
            header("Location: index.php?controller=event&action=list");
            exit;
        }

        // Passer les données de l'événement à la vue
        $template = "./templates/events/update.phtml";
        $title = "Modification de l'événement";

        // Charger la vue avec les données de l'événement
        require "./templates/layout.phtml";
    }

    // Supprimer un événement
    public function delete() {
        if (!isset($_GET['id'])) {
            die("Erreur : aucun ID spécifié.");
        }

        $eventManager = new EventManager();
        $eventManager->deleteEvent((int)$_GET['id']);

        // Rediriger après suppression
        header("Location: index.php?controller=event&action=list");
        exit;
    }

    //Fonction pour FullCalendar
    public function getEvents() {
        // Récupérer les événements de la base de données
        $eventManager = new EventManager();
        $events = $eventManager->findAll();

        // Préparer les événements pour FullCalendar
        $formattedEvents = [];
        foreach ($events as $event) {
            $formattedEvents[] = [
                'title' => $event['name'], // Titre de l'événement
                'start' => $event['date'], // Date de l'événement
                'url' => "/event/" . $event['id'] // Lien vers la page de l'événement
            ];
        }

        // Retourner les événements au format JSON
        header('Content-Type: application/json');
        echo json_encode($formattedEvents);
        exit;
    }

}
