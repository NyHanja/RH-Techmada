<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>Éditer employé<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>Admin > Employés > Éditer<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (session()->has('error')): ?>
    <div style="background: #fee; border: 1px solid #fc9999; border-radius: 8px; color: #c33; padding: 12px 15px; margin-bottom: 1.5rem;">
        <strong>Erreur:</strong> <?= session('error') ?>
    </div>
<?php endif; ?>

<div style="background: white; border: 1px solid #dde8e1; border-radius: 12px; padding: 1.5rem; max-width: 600px;">
    <form method="POST" action="/admin/employes/<?= $employe['id'] ?>">
        <?= csrf_field() ?>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Prénom *</label>
            <input type="text" name="prenom" value="<?= $employe['prenom'] ?>" style="width: 100%; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem;" required>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Nom *</label>
            <input type="text" name="nom" value="<?= $employe['nom'] ?>" style="width: 100%; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem;" required>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Email *</label>
            <input type="email" name="email" value="<?= $employe['email'] ?>" style="width: 100%; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem;" required>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Mot de passe (laisser vide pour ne pas changer)</label>
            <input type="password" name="password" style="width: 100%; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem;">
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Rôle *</label>
            <select name="role" style="width: 100%; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem;" required>
                <option value="employe" <?= $employe['role'] === 'employe' ? 'selected' : '' ?>>Employé</option>
                <option value="rh" <?= $employe['role'] === 'rh' ? 'selected' : '' ?>>RH</option>
                <option value="admin" <?= $employe['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Département *</label>
            <select name="departement_id" style="width: 100%; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem;" required>
                <option value="">Sélectionner...</option>
                <?php foreach ($departements ?? [] as $dept): ?>
                    <option value="<?= $dept['id'] ?>" <?= $employe['departement_id'] == $dept['id'] ? 'selected' : '' ?>>
                        <?= $dept['nom'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Date d'embauche *</label>
            <input type="date" name="date_embauche" value="<?= $employe['date_embauche'] ?>" style="width: 100%; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem;" required>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: #2d5a3d; color: white; border: none; border-radius: 8px; padding: 11px 20px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                <i class="bi bi-check-circle"></i> Mettre à jour
            </button>
            <a href="/admin/employes" style="background: white; color: #7a8f80; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 9px 16px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                Annuler
            </a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
