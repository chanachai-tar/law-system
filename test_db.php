<?php try { $pdo = new PDO("mysql:host=db;port=3306;dbname=law_system_db", "root", "0000"); echo "Connected!"; } catch (Exception $e) { echo $e->getMessage(); } ?>
