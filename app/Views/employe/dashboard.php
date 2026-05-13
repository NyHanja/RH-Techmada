<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>
Mon Tableau de bord
<?= $this->endSection() ?>

<?= $this->section('topbar_actions') ?>
<a href="/employe/demandes/new" style="background: #2d5a3d; color: white; border: none; border-radius: 8px; padding: 7px 14px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; font-size: 0.82rem;">
    <i class="bi bi-plus-circle"></i> Nouvelle demande
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h3 style="margin-bottom: 1.5rem; font-family: 'Playfair Display', serif; font-size: 0.95rem; color: #1c2b1e;">Mon solde de congés</h3>

<?php if (empty($soldes)): ?>
    <div style="text-align: center; padding: 2rem; background: white; border: 1px solid #dde8e1; border-radius: 12px; color: #7a8f80;">
        <i class="bi bi-inbox" style="font-size: 2.5rem; display: block; margin-bottom: 1rem; opacity: 0.3;"></i>
        <p>Aucun solde disponible pour le moment.</p>
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        <?php foreach ($soldes as $solde): ?>
            <div style="background: white; border: 1px solid #dde8e1; border-radius: 12px; padding: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                    <h4 style="margin: 0; font-size: 0.9rem; font-weight: 600; color: #1c2b1e; font-family: 'Playfair Display', serif;">
                        <?= $solde['type_nom'] ?>
                    </h4>
                </div>
                
                <div style="height: 6px; background: #d4ede0; border-radius: 3px; overflow: hidden; margin-bottom: 0.75rem;">
                    <?php 
                        $percent = ($solde['jours_pris'] / $solde['jours_attribues']) * 100;
                        $color = $percent > 80 ? '#c0392b' : ($percent > 50 ? '#b8750a' : '#3d7a52');
                    ?>
                    <div style="height: 100%; width: <?= $percent ?>%; background: <?= $color ?>; border-radius: 3px;"></div>
                </div>
                
                <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: #7a8f80; margin-bottom: 0.5rem;">
                    <span><?= $solde['jours_pris'] ?> / <?= $solde['jours_attribues'] ?> jours</span>
                    <span style="font-weight: 500; color: #1c2b1e;"><?= $solde['jours_attribues'] - $solde['jours_pris'] ?> restants</span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
