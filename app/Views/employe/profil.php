<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>
Mon profil
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
Employé > Profil
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<form method="POST" action="/employe/profil" style="max-width: 600px;">
    <?= csrf_field() ?>

    <div style="background: white; border: 1px solid #dde8e1; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
        <h4 style="margin: 0 0 1.25rem 0; font-family: 'Playfair Display', serif; font-size: 0.95rem; font-weight: 600; color: #1c2b1e;">Informations personnelles</h4>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
            <div>
                <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Prénom *</label>
                <input type="text" name="prenom" value="<?= old('prenom', $employe['prenom'] ?? '') ?>" style="width: 100%; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem;" required>
            </div>
            <div>
                <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Nom *</label>
                <input type="text" name="nom" value="<?= old('nom', $employe['nom'] ?? '') ?>" style="width: 100%; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem;" required>
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Email</label>
            <input type="email" value="<?= $employe['email'] ?? '' ?>" style="width: 100%; border: 1px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem; background: #f8f6f1; color: #7a8f80;" disabled>
            <div style="font-size: 0.75rem; color: #7a8f80; margin-top: 4px;">Non modifiable</div>
        </div>

        <hr style="border: none; border-top: 1px solid #dde8e1; margin: 1.5rem 0;">

        <h4 style="margin: 0 0 1.25rem 0; font-family: 'Playfair Display', serif; font-size: 0.95rem; font-weight: 600; color: #1c2b1e;">Changer le mot de passe</h4>

        <div style="margin-bottom: 1rem;">
            <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Nouveau mot de passe</label>
            <input type="password" name="password" placeholder="Laisser vide pour ne pas changer" style="width: 100%; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem;">
            <div style="font-size: 0.75rem; color: #7a8f80; margin-top: 4px;">Minimum 8 caractères</div>
        </div>

        <div>
            <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Confirmer</label>
            <input type="password" name="password_confirm" placeholder="Confirmer le nouveau mot de passe" style="width: 100%; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem;">
        </div>
    </div>

    <div style="display: flex; gap: 10px;">
        <button type="submit" style="background: #2d5a3d; color: white; border: none; border-radius: 8px; padding: 11px 20px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem;">
            <i class="bi bi-check-circle"></i> Enregistrer
        </button>
        <a href="/employe/dashboard" style="background: white; color: #7a8f80; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 9px 16px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            Retour
        </a>
    </div>
</form>

<?= $this->endSection() ?>
