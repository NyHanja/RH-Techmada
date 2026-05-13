<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>Gestion des départements<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>Admin > Départements<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="background: white; border: 1px solid #dde8e1; border-radius: 12px; overflow: hidden;">
    <div style="padding: 1.5rem; border-bottom: 1px solid #dde8e1;">
        <h2 style="margin: 0; color: #2d5a3d; font-size: 1.1rem;">Liste des départements</h2>
    </div>
    
    <?php if (empty($departements)): ?>
        <div style="padding: 2rem; text-align: center; color: #7a8f80;">
            <p>Aucun département enregistré</p>
        </div>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
            <thead>
                <tr style="background: #f8f6f1; border-bottom: 1px solid #dde8e1;">
                    <th style="padding: 12px; text-align: left; font-weight: 500; color: #7a8f80;">ID</th>
                    <th style="padding: 12px; text-align: left; font-weight: 500; color: #7a8f80;">Nom</th>
                    <th style="padding: 12px; text-align: left; font-weight: 500; color: #7a8f80;">Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departements as $dept): ?>
                    <tr style="border-bottom: 1px solid #dde8e1;">
                        <td style="padding: 12px; color: #7a8f80;"><?= $dept['id'] ?></td>
                        <td style="padding: 12px;"><strong><?= $dept['nom'] ?></strong></td>
                        <td style="padding: 12px; color: #7a8f80;"><?= $dept['description'] ?? '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
