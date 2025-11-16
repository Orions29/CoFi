<?php
// Alert Section
foreach ($_SESSION['alert'] as $alerta):
?>
    <div class="alerta-container">
        <div class="alert alert-danger alert-dismissible fade show alerta" role="alert">
            <strong>
                <?php
                echo htmlspecialchars($alerta['type']);
                ?>
            </strong>
            <p>
                <?php
                echo htmlspecialchars($alerta['message']);
                ?>
            </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <?php
endforeach;
unset($_SESSION['alert']);
if (isset($_SESSION['alert_success'])):
    foreach ($_SESSION['alert_success'] as $alerta):
    ?>
        <div class="alerta-container">
            <div class="alert alert-success alerta" role="alert">
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
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
<?php
    endforeach;
endif;
unset($_SESSION['alert_success']);
?>