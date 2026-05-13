<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>Gestion des employés<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>Admin > Employés<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (session()->has('success')): ?>
    <div style="background: #efe; border: 1px solid #9c9; border-radius: 8px; color: #3c3; padding: 12px 15px; margin-bottom: 1.5rem;">
        <strong>Succès:</strong> <?= session('success') ?>
    </div>
<?php endif; ?>

<div style="background: white; border: 1px solid #dde8e1; border-radius: 12px; overflow: hidden;">
    <div style="padding: 1.5rem; border-bottom: 1px solid #dde8e1;">
        <h2 style="margin: 0; color: #2d5a3d; font-size: 1.1rem;">Liste des employés</h2>
    </div>
    
    <?php if (empty($employes)): ?>
        <div style="padding: 2rem; text-align: center; color: #7a8f80;">
            <p>Aucun employé enregistré</p>
        </div>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
            <thead>
                <tr style="background: #f8f6f1; border-bottom: 1px solid #dde8e1;">
                    <th style="padding: 12px; text-align: left; font-weight: 500; color: #7a8f80;">Nom</th>
                    <th style="padding: 12px; text-align: left; font-weight: 500; color: #7a8f80;">Email</th>
                    <th style="padding: 12px; text-align: left; font-weight: 500; color: #7a8f80;">Rôle</th>
                    <th style="padding: 12px; text-align: left; font-weight: 500; color: #7a8f80;">Département</th>
                    <th style="padding: 12px; text-align: center; font-weight: 500; color: #7a8f80;">Statut</th>
                    <th style="padding: 12px; text-align: center; font-weight: 500; color: #7a8f80;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employes as $emp): ?>
                    <tr style="border-bottom: 1px solid #dde8e1;">
                        <td style="padding: 12px;"><strong><?= $emp['prenom'] ?> <?= $emp['nom'] ?></strong></td>
                        <td style="padding: 12px; color: #7a8f80;"><?= $emp['email'] ?></td>
                        <td style="padding: 12px;">
                            <span style="display: inline-block; padding: 4px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 500; background: #edf7f2; color: #1e6b3f;">
                                <?= ucfirst($emp['role']) ?>
                            </span>
                        </td>
                        <td style="padding: 12px; color: #7a8f80;"><?= $emp['departement'] ?? 'N/A' ?></td>
                        <td style="padding: 12px; text-align: center;">
                            <?php if ($emp['actif']): ?>
                                <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 0.7rem; font-weight: 500; padding: 3px 8px; border-radius: 10px; background: #edf7f2; color: #1e6b3f;">
                                    <span style="width: 4px; height: 4px; border-radius: 50%; background: #1e6b3f;"></span> Actif
                                </span>
                            <?php else: ?>
                                <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 0.7rem; font-weight: 500; padding: 3px 8px; border-radius: 10px; background: #f1efe8; color: #7a8f80;">
                                    <span style="width: 4px; height: 4px; border-radius: 50%; background: #b4b2a9;"></span> Inactif
                                </span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <a href="/admin/employes/<?= $emp['id'] ?>/edit" style="background: #2d5a3d; color: white; border: none; border-radius: 6px; padding: 4px 10px; font-size: 0.7rem; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 3px; cursor: pointer;">
                                    <i class="bi bi-pencil"></i> Éditer
                                </a>
                                <form method="POST" action="/admin/employes/<?= $emp['id'] ?>/deactivate" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" style="background: <?= $emp['actif'] ? '#c0392b' : '#b8750a' ?>; color: white; border: none; border-radius: 6px; padding: 4px 10px; font-size: 0.7rem; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 3px;">
                                        <i class="bi <?= $emp['actif'] ? 'bi-x-circle' : 'bi-check-circle' ?>"></i> 
                                        <?= $emp['actif'] ? 'Désactiver' : 'Activer' ?>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
