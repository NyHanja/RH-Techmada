<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>Gestion des soldes<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>Admin > Soldes<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="background: white; border: 1px solid #dde8e1; border-radius: 12px; overflow: hidden;">
    <div style="padding: 1.5rem; border-bottom: 1px solid #dde8e1;">
        <h2 style="margin: 0; color: #2d5a3d; font-size: 1.1rem;">Soldes de congé (<?= date('Y') ?>)</h2>
    </div>
    
    <?php if (empty($soldes)): ?>
        <div style="padding: 2rem; text-align: center; color: #7a8f80;">
            <p>Aucun solde enregistré</p>
        </div>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
            <thead>
                <tr style="background: #f8f6f1; border-bottom: 1px solid #dde8e1;">
                    <th style="padding: 12px; text-align: left; font-weight: 500; color: #7a8f80;">Employé</th>
                    <th style="padding: 12px; text-align: left; font-weight: 500; color: #7a8f80;">Type de congé</th>
                    <th style="padding: 12px; text-align: center; font-weight: 500; color: #7a8f80;">Attribués</th>
                    <th style="padding: 12px; text-align: center; font-weight: 500; color: #7a8f80;">Utilisés</th>
                    <th style="padding: 12px; text-align: center; font-weight: 500; color: #7a8f80;">Restants</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($soldes as $solde): ?>
                    <?php 
                        $restants = $solde['jours_attribues'] - $solde['jours_pris'];
                        $pct = $solde['jours_attribues'] > 0 ? ($solde['jours_pris'] / $solde['jours_attribues'] * 100) : 0;
                        $bg_color = $restants <= 2 ? '#fdf0ee' : ($restants <= 5 ? '#fef9ee' : '#edf7f2');
                        $fg_color = $restants <= 2 ? '#c0392b' : ($restants <= 5 ? '#b8750a' : '#1e6b3f');
                    ?>
                    <tr style="border-bottom: 1px solid #dde8e1;">
                        <td style="padding: 12px;"><strong><?= $solde['prenom'] ?> <?= $solde['nom'] ?></strong></td>
                        <td style="padding: 12px;"><?= $solde['type_conge'] ?? 'N/A' ?></td>
                        <td style="padding: 12px; text-align: center;"><?= $solde['jours_attribues'] ?></td>
                        <td style="padding: 12px; text-align: center;">
                            <div style="background: #f8f6f1; border-radius: 6px; overflow: hidden; height: 20px; display: flex; align-items: center;">
                                <div style="width: <?= $pct ?>%; background: #2d5a3d; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; color: white; font-weight: 500;">
                                    <?php if ($pct > 20): ?><?= $solde['jours_pris'] ?><?php endif; ?>
                                </div>
                                <?php if ($pct <= 20 && $solde['jours_pris'] > 0): ?>
                                    <span style="font-size: 0.7rem; margin-left: 3px;"><?= $solde['jours_pris'] ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="display: inline-block; padding: 4px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 500; background: <?= $bg_color ?>; color: <?= $fg_color ?>;">
                                <?= $restants ?> j.
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
