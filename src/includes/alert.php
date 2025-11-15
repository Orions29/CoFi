<?php
// Alert Section
foreach ($_SESSION['alert'] as $alerta):
?>
    <div class="alerta-container">
        <div class="alert alert-danger alerta" role="alert">
            <strong>
                <?php
                echo $alerta['type']
                ?>
            </strong>
            <p>
                <?php
                echo $alerta['message']
                ?>
            </p>
        </div>
    </div>
<?php
endforeach;
unset($_SESSION['alert']);
// Kodemu Disini 
?>