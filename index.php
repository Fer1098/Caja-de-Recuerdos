<?php
// ... (Mantenemos tu lógica de PHP intacta)
$host = 'db';
$user = 'root';
$pass = 'password123';
$db   = 'caja_recuerdos';

$conn = new mysqli($host, $user, $pass, $db);

if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM recuerdos WHERE id = $id");
    header("Location: index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar'])) {
    $frase = $_POST['frase'];
    $nombre_imagen = $_FILES['foto']['name'];
    $destino = "uploads/" . $nombre_imagen;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
        $sql = "INSERT INTO recuerdos (frase, imagen) VALUES ('$frase', '$nombre_imagen')";
        $conn->query($sql);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caja de Recuerdos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6c5ce7;
            --secondary: #a29bfe;
            --card-bg: #ffffff;
            --text: #2d3436;
            --danger: #d63031;
        }

        /* FONDO CORREGIDO: Degradado animado que abarca toda la pantalla */
        body { 
            font-family: 'Poppins', sans-serif; 
            margin: 0;
            padding: 40px 20px; 
            min-height: 100vh;
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            
            /* Degradado de fondo */
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            background-attachment: fixed;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Contenedores con Glassmorphism para que resalten sobre el fondo */
        .form-container { 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(10px);
            padding: 30px; 
            border-radius: 20px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
            width: 100%; 
            max-width: 500px; 
            margin-bottom: 50px; 
        }

        h1 {
            color: var(--primary);
            text-align: center;
            margin-top: 0;
        }

        h2 {
            color: white; /* Blanco para que resalte sobre el fondo de color */
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            font-weight: 600;
            margin-bottom: 30px;
        }

        textarea { 
            width: 100%; 
            height: 100px; 
            margin-bottom: 15px; 
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 12px;
            font-family: inherit;
            resize: none;
            box-sizing: border-box; 
        }

        .btn-submit {
            width: 100%; 
            padding: 12px; 
            background: var(--primary); 
            color: white; 
            border: none; 
            border-radius: 12px; 
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            background: #5649c0;
            transform: scale(1.02);
        }

        .gallery-container {
            width: 100%;
            max-width: 1100px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .recuerdo { 
            background: rgba(255, 255, 255, 0.95); 
            border-radius: 15px; 
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1); 
            position: relative; 
            transition: transform 0.3s;
        }

        .recuerdo:hover {
            transform: translateY(-10px);
        }

        .recuerdo img { 
            width: 100%; 
            height: 200px; 
            object-fit: cover;
        }

        .recuerdo-content { padding: 15px; }

        .btn-borrar { 
            background: var(--danger);
            color: white; 
            text-decoration: none; 
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 0.75em;
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h1>📦 Caja de Recuerdos</h1>
    <form action="index.php" method="POST" enctype="multipart/form-data">
        <textarea name="frase" placeholder="¿Qué quieres recordar hoy?" required></textarea>
        <input type="file" name="foto" accept="image/*" required style="margin-bottom: 20px;">
        <button type="submit" name="agregar" class="btn-submit">Encapsular Recuerdo</button>
    </form>
</div>

<h2>Tus Recuerdos Guardados</h2>

<div class="gallery-container">
    <?php
    $res = $conn->query("SELECT * FROM recuerdos ORDER BY id DESC");
    if($res):
        while ($row = $res->fetch_assoc()):
        ?>
            <div class="recuerdo">
                <?php if($row['imagen']): ?>
                    <img src="uploads/<?php echo $row['imagen']; ?>">
                <?php endif; ?>
                <div class="recuerdo-content">
                    <p><?php echo htmlspecialchars($row['frase']); ?></p>
                </div>
                <a href="?eliminar=<?php echo $row['id']; ?>" class="btn-borrar" onclick="return confirm('¿Eliminar?')">Eliminar</a>
            </div>
        <?php 
        endwhile; 
    endif;
    ?>
</div>

</body>
</html>