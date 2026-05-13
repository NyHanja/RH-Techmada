<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>
Mes demandes de congé
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
Employé > Demandes
<?= $this->endSection() ?>

<?= $this->section('topbar_actions') ?>
<a href="/employe/demandes/new" style="background: #2d5a3d; color: white; border: none; border-radius: 8px; padding: 7px 14px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; font-size: 0.82rem;">
    <i class="bi bi-plus-circle"></i> Nouvelle
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (empty($demandes)): ?>
    <div style="text-align: center; padding: 2rem; background: white; border: 1px solid #dde8e1; border-radius: 12px; color: #7a8f80;">
        <i class="bi bi-inbox" style="font-size: 2.5rem; display: block; margin-bottom: 1rem; opacity: 0.3;"></i>
        <p style="margin: 0;">Aucune demande pour le moment.</p>
    </div>
<?php else: ?>
    <div style="background: white; border: 1px solid #dde8e1; border-radius: 12px; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
            <thead>
                <tr style="background: #f8f6f1; border-bottom: 1px solid #dde8e1;">
                    <th style="padding: 9px 14px; text-align: left; font-size: 0.68rem; font-weight: 500; text-transform: uppercase; color: #7a8f80;">Type</th>
                    <th style="padding: 9px 14px; text-align: left; font-size: 0.68rem; font-weight: 500; text-transform: uppercase; color: #7a8f80;">Dates</th>
                    <th style="padding: 9px 14px; text-align: left; font-size: 0.68rem; font-weight: 500; text-transform: uppercase; color: #7a8f80;">Statut</th>
                    <th style="padding: 9px 14px; text-align: right; font-size: 0.68rem; font-weight: 500; text-transform: uppercase; color: #7a8f80;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($demandes as $demande): ?>
                    <tr style="border-bottom: 1px solid #dde8e1;">
                        <td style="padding: 12px 14px;"><strong><?= $demande['type_nom'] ?? 'N/A' ?></strong></td>
                        <td style="padding: 12px 14px; color: #7a8f80;">
                            <?= date('d/m/Y', strtotime($demande['date_debut'])) ?> → <?= date('d/m/Y', strtotime($demande['date_fin'])) ?>
                        </td>
                        <td style="padding: 12px 14px;">
                            <?php 
                                $statut_bg = '#fef9ee'; $statut_fg = '#b8750a'; $statut_dot = '#b8750a'; $statut_label = 'En attente';
                                if ($demande['statut'] === 'approuvee') { $statut_bg = '#edf7f2'; $statut_fg = '#1e6b3f'; $statut_dot = '#1e6b3f'; $statut_label = 'Approuvée'; }
                                elseif ($demande['statut'] === 'refusee') { $statut_bg = '#fdf0ee'; $statut_fg = '#c0392b'; $statut_dot = '#c0392b'; $statut_label = 'Refusée'; }
                                elseif ($demande['statut'] === 'annulee') { $statut_bg = '#f1efe8'; $statut_fg = '#7a8f80'; $statut_dot = '#b4b2a9'; $statut_label = 'Annulée'; }
                            ?>
                            <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 0.7rem; font-weight: 500; padding: 4px 9px; border-radius: 12px; background: <?= $statut_bg ?>; color: <?= $statut_fg ?>;">
                                <span style="width: 5px; height: 5px; border-radius: 50%; background: <?= $statut_dot ?>;"></span>
                                <?= $statut_label ?>
                            </span>
                        </td>
                        <td style="padding: 12px 14px; text-align: right;">
                            <?php if ($demande['statut'] === 'en_attente'): ?>
                                <form method="POST" action="/employe/demandes/annuler/<?= $demande['id'] ?>" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" style="background: white; color: #7a8f80; border: 1px solid #dde8e1; border-radius: 6px; padding: 5px 10px; font-size: 0.72rem; cursor: pointer; font-weight: 500;">
                                        Annuler
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
