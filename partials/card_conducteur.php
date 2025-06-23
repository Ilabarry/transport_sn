<div class="col">
    <div class="card card-conducteur h-100">
        <img src="<?php echo $conduc['profil']; ?>" class="conducteur-img" alt="Photo du conducteur">
        <div class="conducteur-info">
            <h5 class="conducteur-name"><?php echo $conduc['prenom'] . ' ' . $conduc['nom']; ?></h5>
            <p class="conducteur-details"><i class="fas fa-bus"></i> <?php echo $conduc['nom_transport']; ?></p>
            <p class="conducteur-details"><i class="fas fa-id-card"></i> <?php echo $conduc['type_permi']; ?></p>
            <div class="conducteur-actions">
                <a href="affiche_conduc.php?id=<?php echo $conduc['id']; ?>" class="view-btn action-btn" title="Voir détails">
                    <i class="fas fa-eye"></i>
                </a>
                <?php if (isset($_SESSION['id_users']) && $conduc['id_users'] == $_SESSION['id_users']): ?>
                    <a href="update_conduc.php?id=<?php echo $conduc['id']; ?>" class="edit-btn action-btn" title="Modifier">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <a href="conducteur.php?del=<?php echo $conduc['id']; ?>" class="delete-btn action-btn" title="Supprimer"
                       onclick="return confirm('Vos informations seront définitivement supprimées. Continuer ?')">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                <?php endif; ?>
            </div>
            <small class="text-muted d-block mt-2">
                <i class="far fa-clock"></i> <?php echo date('d/m/Y', strtotime($conduc['created_at'])); ?>
            </small>
        </div>
    </div>
</div>
