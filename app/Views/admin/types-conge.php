<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>Gestion des types de congé<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>Admin > Types de congé<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="background: white; border: 1px solid #dde8e1; border-radius: 12px; overflow: hidden;">
    <div style="padding: 1.5rem; border-bottom: 1px solid #dde8e1;">
        <h2 style="margin: 0; color: #2d5a3d; font-size: 1.1rem;">Liste des types de congé</h2>
    </div>
    
    <?php if (empty($types)): ?>
        <div style="padding: 2rem; text-align: center; color: #7a8f80;">
            <p>Aucun type de congé enregistré</p>
        </div>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
            <thead>
                <tr style="background: #f8f6f1; border-bottom: 1px solid #dde8e1;">
                    <th style="padding: 12px; text-align: left; font-weight: 500; color: #7a8f80;">ID</th>
                    <th style="padding: 12px; text-align: left; font-weight: 500; color: #7a8f80;">Libellé</th>
                    <th style="padding: 12px; text-align: center; font-weight: 500; color: #7a8f80;">Jours annuels</th>
                    <th style="padding: 12px; text-align: center; font-weight: 500; color: #7a8f80;">Déductible</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($types as $type): ?>
                    <tr style="border-bottom: 1px solid #dde8e1;">
                        <td style="padding: 12px; color: #7a8f80;"><?= $type['id'] ?></td>
                        <td style="padding: 12px;"><strong><?= $type['libelle'] ?></strong></td>
                        <td style="padding: 12px; text-align: center;">
                            <strong><?= $type['jours_annuels'] ?></strong>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <?php if ($type['deductible']): ?>
                                <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 0.7rem; font-weight: 500; padding: 3px 8px; border-radius: 10px; background: #edf7f2; color: #1e6b3f;">
                                    <span style="width: 4px; height: 4px; border-radius: 50%; background: #1e6b3f;"></span> Oui
                                </span>
                            <?php else: ?>
                                <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 0.7rem; font-weight: 500; padding: 3px 8px; border-radius: 10px; background: #fdf0ee; color: #c0392b;">
                                    <span style="width: 4px; height: 4px; border-radius: 50%; background: #c0392b;"></span> Non
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
