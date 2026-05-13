<?php
session_start();
include('inc/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    try {
        // Get programme details
        $sql = "SELECT * FROM phd_programmes WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $programme = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($programme) {
            // Get documents
            $sql_docs = "SELECT * FROM phd_documents WHERE phd_programme_id = :programme_id ORDER BY id DESC";
            $query_docs = $dbh->prepare($sql_docs);
            $query_docs->bindParam(':programme_id', $id);
            $query_docs->execute();
            $documents = $query_docs->fetchAll(PDO::FETCH_ASSOC);
            
            $programme['documents'] = $documents;
        }
        
        echo json_encode($programme);
        
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No ID provided']);
}
?>