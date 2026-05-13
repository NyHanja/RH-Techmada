<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>Liste des employés<?= $this->endSection() ?>

<?= $this->section('topbar_actions') ?>
<a href="/admin/employes/new" class="btn-forest">Ajouter un employé</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="data-card">
    <div class="data-card-head"><h3>Employés</h3></div>
    <table class="tbl">
        <thead>
            <tr><th>Nom</th><th>Prénom</th><th>Email</th><th>Département</th><th>Rôle</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php if (empty($employes)): ?>
                <tr><td colspan="6" class="empty">Aucun employé.</td></tr>
            <?php else: ?>
                <?php foreach ($employes as $e): ?>
                <tr>
                    <td><?= esc($e['nom']) ?></td>
                    <td><?= esc($e['prenom']) ?></td>
                    <td><?= esc($e['email']) ?></td>
                    <td><?= esc($e['departement'] ?? $e['departement_nom'] ?? '') ?></td>
                    <td><?= esc($e['role']) ?></td>
                    <td>
                        <a class="btn-sm btn-edit" href="/admin/employes/edit/<?= $e['id'] ?>">Modifier</a>
                        <form method="post" action="/admin/employes/desactiver/<?= $e['id'] ?>" style="display:inline">
                            <?= csrf_field() ?>
                            <button class="btn-sm btn-del" type="submit">Désactiver</button>
                        </form>
                        <form method="post" action="/admin/employes/supprimer/<?= $e['id'] ?>" style="display:inline" onsubmit="return confirm('Supprimer définitivement cet employé ? Cette action est irréversible.')">
                            <?= csrf_field() ?>
                            <button class="btn-sm btn-refuse" type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
