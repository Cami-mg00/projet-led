<?php


ob_start(); // Démarre la mise en tampon de sortie

ini_set('display_errors', 1);
error_reporting(E_ALL);

//echo "📌 `router.php` est bien exécuté ! <br>";
require_once 'autoload.php';

class Router {
    public function handleRequest() {
        //echo "📌 `handleRequest()` est appelé ! <br>";
        $controller = $_GET['controller'] ?? 'user';
        $action = $_GET['action'] ?? 'list';

        $controllerName = ucfirst($controller) . 'Controller';
        $controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";

        //echo "📌 Recherche du contrôleur : $controllerFile <br>";

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            //echo "✅ Contrôleur trouvé ! <br>";
            $controllerInstance = new $controllerName();

            if (method_exists($controllerInstance, $action)) {
                //echo "🔹 Action trouvée : $action <br>";
                $controllerInstance->$action();
            } else {
                echo "❌ Action non trouvée : $action";
            }
        } else {
            echo "❌ Contrôleur non trouvé : $controllerFile";
        }
    }
}

$router = new Router();
$router->handleRequest();

ob_end_flush(); // Vide le tampon et affiche le contenu
